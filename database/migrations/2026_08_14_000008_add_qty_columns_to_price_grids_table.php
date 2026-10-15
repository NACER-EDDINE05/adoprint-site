<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('price_grids', function (Blueprint $table) {
            if (! Schema::hasColumn('price_grids', 'min_qty')) {
                $table->integer('min_qty')->default(0)->after('name');
            }
            if (! Schema::hasColumn('price_grids', 'max_qty')) {
                $table->integer('max_qty')->default(0)->after('min_qty');
            }
        });
    }

    public function down(): void
    {
        Schema::table('price_grids', function (Blueprint $table) {
            if (Schema::hasColumn('price_grids', 'max_qty')) {
                $table->dropColumn('max_qty');
            }
            if (Schema::hasColumn('price_grids', 'min_qty')) {
                $table->dropColumn('min_qty');
            }
        });
    }
};
