<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the attributes table for defining custom fields.
     * Supports multiple field types: text, number, select, multiselect, boolean, etc.
     */
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('attribute_group_id')->nullable()->constrained()->nullOnDelete();
            
            // Field type: text, textarea, number, select, multiselect, boolean, date, range, color
            $table->enum('type', [
                'text',
                'textarea',
                'number',
                'select',
                'multiselect',
                'boolean',
                'date',
                'range',
                'color',
            ])->default('text');
            
            // Validation rules
            $table->boolean('is_required')->default(false);
            $table->string('validation_rules')->nullable(); // e.g., 'min:0|max:9999'
            
            // Display settings
            $table->boolean('show_in_filters')->default(false);
            $table->boolean('show_in_card')->default(false);
            $table->boolean('show_in_details')->default(true);
            $table->boolean('show_in_comparison')->default(false);
            
            // UI settings
            $table->string('placeholder')->nullable();
            $table->string('help_text')->nullable();
            $table->string('suffix')->nullable(); // e.g., 'km', 'hp', 'L'
            $table->string('prefix')->nullable(); // e.g., 'AED'
            $table->string('icon')->nullable();
            
            // Default value
            $table->string('default_value')->nullable();
            
            // For range type
            $table->decimal('min_value', 10, 2)->nullable();
            $table->decimal('max_value', 10, 2)->nullable();
            $table->decimal('step', 10, 2)->nullable();
            
            // Status and ordering
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('type');
            $table->index('show_in_filters');
            $table->index('attribute_group_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
