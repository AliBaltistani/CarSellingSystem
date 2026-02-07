<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('whatsapp', 20)->nullable()->after('phone');
            $table->string('avatar')->nullable()->after('whatsapp');
            $table->decimal('latitude', 10, 7)->nullable()->after('avatar');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('city', 100)->nullable()->after('longitude');
            $table->string('state', 100)->nullable()->after('city');
            $table->string('country', 100)->nullable()->after('state');
            $table->boolean('is_active')->default(true)->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'whatsapp', 'avatar', 
                'latitude', 'longitude', 'city', 'state', 'country',
                'is_active'
            ]);
        });
    }
};
