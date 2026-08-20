<?php

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';

use Bref\LaravelBridge\Http\NetlifyHandler;

return new NetlifyHandler($app);