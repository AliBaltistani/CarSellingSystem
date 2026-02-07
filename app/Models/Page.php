<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    /**
     * Available page templates
     */
    public const TEMPLATES = [
        'default' => 'Default',
        'contact' => 'Contact Page',
        'faq' => 'FAQ Page',
        'sidebar' => 'With Sidebar',
        'full-width' => 'Full Width',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'template',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'show_in_header',
        'show_in_footer',
        'header_order',
        'footer_order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'show_in_header' => 'boolean',
        'show_in_footer' => 'boolean',
        'header_order' => 'integer',
        'footer_order' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            // Ensure unique slug
            $originalSlug = $page->slug;
            $count = 1;
            while (static::where('slug', $page->slug)->exists()) {
                $page->slug = $originalSlug . '-' . $count++;
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title') && !$page->isDirty('slug')) {
                $page->slug = Str::slug($page->title);
                // Ensure unique slug
                $originalSlug = $page->slug;
                $count = 1;
                while (static::where('slug', $page->slug)->where('id', '!=', $page->id)->exists()) {
                    $page->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    /**
     * Scope: Active pages only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Header navigation pages
     */
    public function scopeInHeader($query)
    {
        return $query->where('show_in_header', true)->orderBy('header_order');
    }

    /**
     * Scope: Footer navigation pages
     */
    public function scopeInFooter($query)
    {
        return $query->where('show_in_footer', true)->orderBy('footer_order');
    }

    /**
     * Get the template label
     */
    public function getTemplateLabelAttribute(): string
    {
        return self::TEMPLATES[$this->template] ?? 'Default';
    }

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return null;
    }

    /**
     * Get SEO meta title (fallback to title)
     */
    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    /**
     * Get URL for the page
     */
    public function getUrlAttribute(): string
    {
        return route('page.show', $this->slug);
    }
}
