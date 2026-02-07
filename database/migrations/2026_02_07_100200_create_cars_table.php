<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            // Basic Info
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->boolean('negotiable')->default(false);

            // Car Details
            $table->year('year');
            $table->string('make', 100);
            $table->string('model', 100);
            $table->string('trim', 100)->nullable();
            $table->string('vin', 17)->nullable()->unique();
            $table->enum('condition', ['new', 'used', 'certified'])->default('used');
            $table->unsignedInteger('mileage')->nullable();
            $table->string('registration_number', 50)->nullable();

            // Specifications
            $table->enum('transmission', ['automatic', 'manual', 'cvt', 'semi-automatic']);
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid', 'cng', 'lpg']);
            $table->string('engine_capacity', 20)->nullable();
            $table->unsignedInteger('horsepower')->nullable();
            $table->unsignedTinyInteger('cylinders')->nullable();
            $table->string('body_type', 50)->nullable();
            $table->string('exterior_color', 50)->nullable();
            $table->string('interior_color', 50)->nullable();
            $table->unsignedTinyInteger('doors')->nullable();
            $table->unsignedTinyInteger('seats')->nullable();
            $table->enum('drivetrain', ['front', 'rear', 'all', '4x4'])->nullable();

            // Features (JSON)
            $table->json('features')->nullable();

            // Location
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->default('UAE');
            $table->string('postal_code', 20)->nullable();

            // Contact
            $table->string('whatsapp_number', 20);
            $table->string('phone_number', 20)->nullable();
            $table->string('email')->nullable();

            // Status & Visibility
            $table->enum('status', ['available', 'sold', 'pending', 'reserved'])->default('available');
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_until')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Tracking
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('favorites_count')->default(0);
            $table->unsignedInteger('inquiries_count')->default(0);

            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['latitude', 'longitude']);
            $table->index('status');
            $table->index('is_published');
            $table->index('price');
            $table->index('year');
            $table->index(['make', 'model']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
