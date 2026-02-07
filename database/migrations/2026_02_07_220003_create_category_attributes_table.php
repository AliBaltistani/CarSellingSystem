<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the category_attributes pivot table linking categories to their attributes.
     * Allows different categories to have different sets of attributes.
     */
    public function up(): void
    {
        Schema::create('category_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            
            // Overrides for this category-attribute combo
            $table->boolean('is_required')->nullable(); // Null = use attribute default
            $table->boolean('show_in_filters')->nullable();
            $table->boolean('show_in_card')->nullable();
            $table->boolean('show_in_details')->nullable();
            $table->integer('order')->default(0);
            
            $table->timestamps();
            
            $table->unique(['category_id', 'attribute_id']);
            $table->index('category_id');
            $table->index('attribute_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_attributes');
    }
};
