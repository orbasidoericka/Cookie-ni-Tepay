<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix migrations table id to be auto-increment primary key if needed
        DB::statement("ALTER TABLE `migrations` MODIFY COLUMN `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
    }

    public function down(): void
    {
        // Revert to non-auto increment if required — keep safe
        // This is potentially destructive but we provide a basic revert
        DB::statement("ALTER TABLE `migrations` MODIFY COLUMN `id` INT UNSIGNED NOT NULL");
    }
};
