<?php

// Set container env
$container->set('env', function () use ($env) {
    if (!empty($env)) return $env;
    throw new Exception("Error Processing Env ...", 1);
});

// Set container Redbean
$container->set('rb-setup', function () use ($env) {
    if (!empty($env['DB_USERNAME'])) {
        R::setup($env['DB_CONNECT_DNS'], $env['DB_USERNAME'], '');
        R::debug(false);
        R::freeze(false);
        R::ext('xdispense', fn ($type) => R::getRedBean()->dispense($type));
        return;
    }
    throw new Exception("Error Processing Redbean ...", 1);
});

// Set container PDO
$container->set('pdo', function () use ($env) { 
    if (!empty($env)) {
        try {
            $conn = new PDO($env['DB_CONNECT_DNS'], $env['DB_USERNAME'], $env['DB_PASSWORD']);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
    throw new Exception("Error Processing PDO ...", 1);
});

// Set Resource Path
$container->set('resource_path', function () { 
    return __DIR__ . '../../../../resource/';
});
