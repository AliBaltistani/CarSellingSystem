<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE cars MODIFY COLUMN fuel_type ENUM('petrol', 'diesel', 'electric', 'hybrid', 'plugin-hybrid', 'cng', 'lpg')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE cars MODIFY COLUMN fuel_type ENUM('petrol', 'diesel', 'electric', 'hybrid', 'cng', 'lpg')");
    }
};
