<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('address')->nullable()->after('display_name');
            $table->string('county')->nullable()->after('country');
            $table->string('neighbourhood')->nullable()->after('county');
            $table->string('postcode', 20)->nullable()->after('neighbourhood');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['address', 'county', 'neighbourhood', 'postcode']);
        });
    }
};
