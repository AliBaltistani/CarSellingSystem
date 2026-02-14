<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'nullable|array',
            'settings.*' => 'nullable',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,ico,svg|max:1024',
        ]);

        // Handle File Uploads
        $fileKeys = ['site_logo', 'site_favicon'];
        
        foreach ($fileKeys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $path = $file->store('settings', 'public');
                
                Setting::set($key, $path, 'string', 'general');
            }
        }

        // Handle Text Settings
        if (isset($validated['settings'])) {
            foreach ($validated['settings'] as $key => $value) {
                // Skip if key is in fileKeys to avoid overwriting with null/string if not uploaded
                if (in_array($key, $fileKeys)) continue;

                $setting = Setting::where('key', $key)->first();
                if ($setting) {
                    $setting->update(['value' => $value]);
                    Cache::forget("setting.{$key}");
                }
            }
        }

        // Clear all caches
        Setting::clearCache();

        return back()->with('success', 'Settings updated successfully!');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:255',
        ]);

        $adminEmail = $request->test_email;

        try {
            $siteName = Setting::get('site_name') ?? config('app.name', 'Xenon Motors');

            Mail::raw(
                "This is a test email from {$siteName}.\n\nIf you're reading this, your SMTP settings are configured correctly!\n\nSent at: " . now()->format('M d, Y h:i A'),
                function ($message) use ($adminEmail, $siteName) {
                    $message->to($adminEmail)
                            ->subject("✅ Test Email from {$siteName}");
                }
            );

            return response()->json(['success' => true, 'message' => "Test email sent successfully to {$adminEmail}!"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()], 422);
        }
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'nullable|string',
            'type' => 'required|in:string,integer,boolean,text,json',
            'group' => 'required|string|max:100',
        ]);

        Setting::create($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting created successfully!');
    }

    public function destroy(Setting $setting)
    {
        Cache::forget("setting.{$setting->key}");
        $setting->delete();

        return back()->with('success', 'Setting deleted successfully!');
    }
}
