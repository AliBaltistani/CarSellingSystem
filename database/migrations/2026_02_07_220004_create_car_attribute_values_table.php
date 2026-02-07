<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the car_attribute_values table storing actual attribute values for each car.
     * Uses a polymorphic-like design with a text value column that can store any type.
     */
    public function up(): void
    {
        Schema::create('car_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            
            // Single value storage - cast to appropriate type based on attribute type
            $table->text('value')->nullable();
            
            // For number/range types - store numeric for filtering/sorting
            $table->decimal('numeric_value', 15, 4)->nullable();
            
            $table->timestamps();
            
            $table->unique(['car_id', 'attribute_id']);
            $table->index('car_id');
            $table->index('attribute_id');
            $table->index('numeric_value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_attribute_values');
    }
};
