<?php

use Hazzard\Filepicker\Handler;
use Hazzard\Filepicker\Uploader;
use Intervention\Image\ImageManager;
use Hazzard\Config\Repository as Config;

// Include composer autoload
require __DIR__.'/../vendor/autoload.php';
require $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";

$app = new wbApp();

$uploader = new Uploader($config = new Config, new ImageManager(array('driver' => 'imagick')));
$handler = new Handler($uploader);

// Configuration

$config['debug'] = true;
$config['upload_url'] = '/uploads';
if ($app->vars("_req.upload_ext") > "") $config['accept_file_types'] = $app->vars("_req.upload_ext");
if ($app->vars("_req.upload_url") > "") $config['upload_url'] = $app->vars("_req.upload_url");
$config['upload_dir'] = $_SERVER["DOCUMENT_ROOT"].$config['upload_url'];
$config['script_url'] = 'http://work10.loc/engine/modules/filepicker/uploader/index.php';
$config['overwrite'] = true;
$config['max_file_size'] = $app->vars("_sett.max_upload_size");
$config['auto_orient'] = true;
$config['image_file_types'] = 'gif|jpg|jpeg|png';
$config['inline_file_types'] = 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx|zip|rar|gzip';
$_ENV['upload_url'] = $config['upload_url'];
$_ENV["app"] = &$app;


/*
$config['image_versions.thumb'] = array(
    'width' => 200,
    'height' => 200
);
*/
// Events

/**
 * Fired before the file upload starts.
 *
 * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
 */
$handler->on('upload.before', function (&$file) {
    $_ENV["original"] = $file->getClientOriginalName();
    $file->save = wbNewId();
    // throw new \Hazzard\Filepicker\Exception\AbortException('Error message!');
});

/**
 * Fired on upload success.
 *
 * @param \Symfony\Component\HttpFoundation\File\File $file
 */
$handler->on('upload.success', function (&$file) {
  $app = $_ENV["app"];
  $ext = $file->getExtension();
  if (!in_array($ext,["jpg","png","gif"])) return;
  $resize = intval($app->vars("_sett.modules.filepicker.resizeto"));
  $quality = intval($app->vars("_sett.modules.filepicker.quality"));
  if ($resize > 0) {
      $manager = new ImageManager(array('driver' => 'imagick'));
      $pathname = $file->getRealPath();
      $image = $manager->make($pathname);
      if ($image->width() > $resize) {
          $image->resize($resize, null, function ($constraint) {
              $constraint->aspectRatio();
          });
          if ($quality > 0) {
              $image->save($pathname,$quality);
          } else {
              $image->save();
          }
      }
  }
});

/**
 * Fired on upload error.
 *
 * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
 */
$handler->on('upload.error', function ($file) {

});

/**
 * Fired when fetching files.
 *
 * @param array &$files
 */
$handler->on('files.fetch', function (&$files) {
    // Set the array of files to be returned.
    // $files = array('file1', 'file2', 'file3');
});

/**
 * Fired on file filtering.
 *
 * @param array &$files
 * @param int   &$total
 */
$handler->on('files.filter', function (&$files, &$total) {
    if (!isset($_REQUEST["upload_ext"])) return;
    $exts = explode("|",$_REQUEST["upload_ext"]);
    foreach ($files as $i => $file) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if (!in_array($ext,$exts)) {
            unset($files[$i]);
            $total--;
        }
    }
});

/**
 * Fired on file download.
 *
 * @param \Symfony\Component\HttpFoundation\File\File $file
 * @param string $version
 */
$handler->on('file.download', function ($file, $version) {

});

/**
 * Fired on file deletion.
 *
 * @param \Symfony\Component\HttpFoundation\File\File $file
 */
$handler->on('file.delete', function ($file) {

});

/**
 * Fired before cropping.
 *
 * @param \Symfony\Component\HttpFoundation\File\File $file
 * @param \Intervention\Image\Image $image
 */
$handler->on('crop.before', function ($file, $image) {

});

/**
 * Fired after cropping.
 *
 * @param \Symfony\Component\HttpFoundation\File\File $file
 * @param \Intervention\Image\Image $image
 */
$handler->on('crop.after', function ($file, $image) {

});

// Handle the request.
$handler->handle()->send();
