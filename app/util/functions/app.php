<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Views\Twig;

if (!function_exists('json')) {

    /**
     * @param Response $response
     * @param array $data
     * @param integer $status
     * @return Response
     */
    function json(Response $response, array $data = [], int $status = 200): Response
    {
        $response->getBody()->write(json_encode(arr_upr(['data' => $data]), JSON_PRETTY_PRINT));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}

if (!function_exists('arr_upr')) {

    /**
     * @param array|object $input
     * @param int $case
     * @return array
     */
    function arr_upr($input, $case = MB_CASE_TITLE)
    {
        $convToCamel = function ($str) {
            return str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
        };

        if (is_object($input)) {
            $input = json_decode(json_encode($input), true);
        }

        $newArray = array();
        foreach ($input as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $newArray[$convToCamel($key)] = arr_upr($value, $case);
            } else {
                $newArray[$convToCamel($key)] = $value;
            }
        }
        return $newArray;
    }
}

if (!function_exists('esc')) {

    /**
     * @param string $data
     * @param boolean $cut_underscores
     * @return string
     */
    function esc(string $data, bool $cut_underscores = false): string
    {
        if (!is_string($data)) {
            $data = (string)$data;
        }
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return ($cut_underscores) ? str_replace('_', '', $data) : $data;
    }
}

if (!function_exists('view')) {

    /**
     * @override Twig
     *
     * @param Request $request
     * @return Twig
     */
    function view(Request $request): Twig
    {
        $view = Twig::fromRequest($request);
        return $view;
    }
}

if (!function_exists('fileUploaded')) {

    /**
     * 
     * @param string $directory
     * @param UploadedFileInterface $uploadedFile
     * @return string
     */
    function fileUploaded(string $directory, UploadedFileInterface $uploadedFile): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = date('Ymdhis') . bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}

if (!function_exists('fileResponse')) {

    /**
     * <code>
     *  $directory = $this->container->get('load_directory');
     *  $imageName = $args['filename'] ?? '';
     *
     *  return fileResponse($response, $directory . scan_filename($directory, $imageName));
     * </code>
     * 
     * @param Response $response
     * @param string $path
     * @param boolean $download
     * @return Response
     */
    function fileResponse(Response $response, string $path, bool $download = false): Response
    {
        if (!file_exists($path)) {
            return $response->withStatus(404, 'File Not Found');
        }

        $fileContent = file_get_contents($path);
        $contentType = mime_content_type($path);
        $filename = basename($path);

        $response = $response->withHeader('Content-Type', $contentType);
        if ($download) {
            $response = $response->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }
        $response->getBody()->write($fileContent);

        return $response;
    }
}

if (!function_exists('scan_filename')) {

    /**
     * @param string $directory
     * @param string $inputFilename
     * @return string
     */
    function scan_filename(string $directory, string $inputFilename): string
    {
        $name = "";
        $files = scandir($directory, SCANDIR_SORT_DESCENDING);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $fileInfo = pathinfo($file);
                if (isset($fileInfo['filename'])) {
                    if ($inputFilename == $fileInfo['filename']) {
                        $name = $fileInfo['basename'];
                        break;
                    }
                }
            }
        }
        return $name;
    }
}

if (!function_exists('public_path')) {

    /**
     * public_path("folder=upload&filename={$user['file_name']}");
     *
     * @param string $path
     * @return string
     */
    function public_path(string $path = ''): string
    {
        global $env;
        $path = $env['APP_URL'] . "resource/public/?" . $path;
        return $path; 
    }
}
