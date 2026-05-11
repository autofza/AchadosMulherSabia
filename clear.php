<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$commands = [
    'config:clear',
    'cache:clear',
    'view:clear',
    'route:clear',
];

foreach ($commands as $cmd) {
    echo "Executando: php artisan $cmd <br>";
    $status = $kernel->call($cmd);
    echo nl2br($kernel->output()) . "<hr>";
}

echo "✅ Cache limpo com sucesso!";
