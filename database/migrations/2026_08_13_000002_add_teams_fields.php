<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (! empty($columnNames['team_foreign_key'] ?? null)) {
            Schema::table($tableNames['roles'], static function (Blueprint $table) use ($columnNames) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
            });

            Schema::table($tableNames['model_has_permissions'], static function (Blueprint $table) use ($columnNames) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
            });

            Schema::table($tableNames['model_has_roles'], static function (Blueprint $table) use ($columnNames) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (! empty($columnNames['team_foreign_key'] ?? null)) {
            Schema::table($tableNames['roles'], static function (Blueprint $table) use ($columnNames) {
                $table->dropColumn($columnNames['team_foreign_key']);
            });

            Schema::table($tableNames['model_has_permissions'], static function (Blueprint $table) use ($columnNames) {
                $table->dropColumn($columnNames['team_foreign_key']);
            });

            Schema::table($tableNames['model_has_roles'], static function (Blueprint $table) use ($columnNames) {
                $table->dropColumn($columnNames['team_foreign_key']);
            });
        }
    }
};
