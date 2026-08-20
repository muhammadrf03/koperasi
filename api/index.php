<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Konfigurasi folder /tmp untuk Vercel
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

$app->useStoragePath($storagePath);
$app->useBootstrapPath($storagePath . '/bootstrap');

// Set environment variable untuk menyimpan cache file di /tmp
$_ENV['APP_CONFIG_CACHE'] = $storagePath . '/bootstrap/config.php';
$_ENV['APP_ROUTES_CACHE'] = $storagePath . '/bootstrap/routes.php';
$_ENV['APP_SERVICES_CACHE'] = $storagePath . '/bootstrap/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $storagePath . '/bootstrap/packages.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);