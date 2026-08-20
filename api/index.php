<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Konfigurasi direktori /tmp untuk Vercel Serverless
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    mkdir($storagePath . '/framework/views', 0755, true);
    mkdir($storagePath . '/framework/sessions', 0755, true);
    mkdir($storagePath . '/framework/cache/data', 0755, true);
    mkdir($storagePath . '/bootstrap/cache', 0755, true);
    mkdir($storagePath . '/logs', 0755, true);
}

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Overwrite path storage dan bootstrap cache bawaan
$app->useStoragePath($storagePath);
$app->useBootstrapPath($storagePath . '/bootstrap');

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);