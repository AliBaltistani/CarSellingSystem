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
        Schema::create('dropdown_options', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->index();        // e.g., 'make', 'condition', 'transmission'
            $table->string('slug', 100);                // URL/value-friendly identifier
            $table->string('label');                    // Display label
            $table->string('value')->nullable();        // Stored value (defaults to slug if null)
            $table->string('icon')->nullable();         // Icon path or SVG
            $table->string('color', 20)->nullable();    // Hex color for UI
            $table->text('description')->nullable();    // Optional description/tooltip
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['type', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropdown_options');
    }
};
