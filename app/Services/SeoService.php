<?php

namespace App\Services;

use App\Models\Car;

class SeoService
{
    /**
     * Generate meta tags for a car detail page
     */
    public function generateCarMeta(Car $car): array
    {
        $title = $car->meta_title ?: 
            "{$car->year} {$car->make} {$car->model} for Sale - {$car->formatted_price}";
        
        $description = $car->meta_description ?: 
            "Buy {$car->year} {$car->make} {$car->model} in {$car->city}. " .
            ($car->mileage ? "{$car->mileage} km, " : "") .
            "{$car->transmission} transmission, {$car->fuel_type}. " .
            "Price: {$car->formatted_price}. Contact via WhatsApp.";
        
        $keywords = implode(', ', array_filter([
            $car->make,
            $car->model,
            $car->year,
            $car->condition . ' car',
            $car->city . ' cars',
            'buy ' . $car->make,
            'used cars ' . $car->city,
            $car->body_type,
            $car->fuel_type . ' cars',
        ]));

        return [
            'title' => substr($title, 0, 70),
            'description' => substr($description, 0, 160),
            'keywords' => $car->meta_keywords ?: $keywords,
            'og_image' => $car->main_image,
        ];
    }

    /**
     * Generate JSON-LD structured data for a car
     */
    public function generateStructuredData(Car $car): array
    {
        $images = $car->images->map(fn($img) => $img->url)->toArray();
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Car',
            'name' => $car->title,
            'description' => $car->description,
            'brand' => [
                '@type' => 'Brand',
                'name' => $car->make
            ],
            'model' => $car->model,
            'productionDate' => (string) $car->year,
            'vehicleConfiguration' => $car->trim,
            'mileageFromOdometer' => $car->mileage ? [
                '@type' => 'QuantitativeValue',
                'value' => $car->mileage,
                'unitCode' => 'KMT'
            ] : null,
            'offers' => [
                '@type' => 'Offer',
                'price' => $car->price,
                'priceCurrency' => 'AED',
                'availability' => $car->status === 'available' 
                    ? 'https://schema.org/InStock' 
                    : 'https://schema.org/OutOfStock',
                'url' => route('cars.show', $car->slug),
                'seller' => [
                    '@type' => 'Organization',
                    'name' => config('app.name')
                ]
            ],
            'image' => $images ?: [$car->main_image],
            'vehicleTransmission' => ucfirst($car->transmission),
            'fuelType' => ucfirst($car->fuel_type),
            'bodyType' => ucfirst($car->body_type ?? ''),
            'color' => $car->exterior_color,
            'numberOfDoors' => $car->doors,
            'seatingCapacity' => $car->seats,
        ];
    }

    /**
     * Generate meta for listing page
     */
    public function generateListingMeta(array $filters = []): array
    {
        $title = 'Cars for Sale';
        $description = 'Browse quality cars for sale. ';
        
        if (!empty($filters['make'])) {
            $title = "{$filters['make']} Cars for Sale";
            $description .= "Find {$filters['make']} vehicles. ";
        }
        
        if (!empty($filters['body_type'])) {
            $title .= " - " . ucfirst($filters['body_type']);
        }
        
        if (!empty($filters['city'])) {
            $title .= " in {$filters['city']}";
            $description .= "Located in {$filters['city']}. ";
        }
        
        $description .= "Contact sellers directly via WhatsApp.";

        return [
            'title' => substr($title . ' | ' . config('app.name'), 0, 70),
            'description' => substr($description, 0, 160),
            'keywords' => implode(', ', array_filter([
                'cars for sale',
                $filters['make'] ?? null,
                $filters['city'] ?? null,
                'buy car',
                'used cars',
                'new cars'
            ])),
        ];
    }
}
