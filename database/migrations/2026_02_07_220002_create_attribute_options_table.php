<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the attribute_options table for select/multiselect types.
     * Each option belongs to an attribute and has a label and value.
     */
    public function up(): void
    {
        Schema::create('attribute_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('value');
            $table->string('color')->nullable(); // For color swatches
            $table->string('icon')->nullable();
            $table->boolean('is_default')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index('attribute_id');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_options');
    }
};
