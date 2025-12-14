<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `products` MODIFY COLUMN `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `products` MODIFY COLUMN `id` BIGINT(20) UNSIGNED NOT NULL");
    }
};
