<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('badge')->nullable();
            $table->string('price_label')->nullable();
            $table->decimal('price_from', 10, 2)->nullable();
            $table->decimal('price_upgrade', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->string('cta_text')->default('Read More');
            $table->string('cta_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->date('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
