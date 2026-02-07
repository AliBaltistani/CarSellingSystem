<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified page.
     */
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Get the appropriate view based on template
        $view = 'pages.' . $page->template;
        
        // Fallback to default if template view doesn't exist
        if (!view()->exists($view)) {
            $view = 'pages.default';
        }

        // SEO data
        $seo = [
            'title' => $page->seo_title . ' - ' . config('app.name'),
            'description' => $page->meta_description ?? $page->excerpt,
            'keywords' => $page->meta_keywords,
        ];

        return view($view, compact('page', 'seo'));
    }
}
