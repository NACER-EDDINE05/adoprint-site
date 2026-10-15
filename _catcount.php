<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

echo "Categories ordered by NAME (as shown in menu):\n";
foreach (Category::where('active', true)->orderBy('name')->get() as $c) {
    $cnt = $c->products()->count();
    echo str_pad($c->id, 3)." | ".str_pad($cnt, 2)." prod | ".$c->name.PHP_EOL;
}
