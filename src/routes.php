<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/uploads';
// Routes
$app->get('/', function(Request $request, Response $response) {
    return $this->renderer->render($response, 'index.phtml');
});

$app->post('/', function(Request $request, Response $response) {
    $directory = $this->get('upload_directory');

    $uploadedFiles = $request->getUploadedFiles();

    // handle single input with single file upload
    // $uploadedFile = $uploadedFiles['example1'];

    // if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
    //     $filename = moveUploadedFile($directory, $uploadedFile);
    //     $response->write('uploaded ' . $filename . '<br/>');
    // }


    // // handle multiple inputs with the same key
    // foreach ($uploadedFiles['example2'] as $uploadedFile) {
    //     if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
    //         $filename = moveUploadedFile($directory, $uploadedFile);
    //         $response->write('uploaded ' . $filename . '<br/>');
    //     }
    // }

    // handle single input with multiple file uploads
    foreach ($uploadedFiles['example3'] as $uploadedFile) {
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = moveUploadedFile($directory, $uploadedFile);
            $response->write('upload成功： ' . $filename . '<br/>');
        }else{
            $response->write('upload失敗　FileName:'.$uploadedFile->getClientFilename() . "<br />");
        }
    }

});

/**
 * Moves the uploaded file to the upload directory and assigns it a unique name
 * to avoid overwriting an existing uploaded file.
 *
 * @param string $directory directory to which the file is moved
 * @param UploadedFile $uploaded file uploaded file to move
 * @return string filename of moved file
 */
function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    //$basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $basename = uniqid();
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}
