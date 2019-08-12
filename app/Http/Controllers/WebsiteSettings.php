<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WebsiteSetting;

use Arr;
class WebsiteSettings extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $webSettings = WebsiteSetting::where('key', 'LIKE', 'CLASSROOM.%')
            ->orWhere('key', 'LIKE', 'STUDENT.%')
            ->orWHere('key', 'LIKE', 'TEACHER.%')
            ->get();

        $assocWebSettings = $this->parseWebsiteSettings($webSettings);

        return view('website_settings.index', [
            'webSettings' => $assocWebSettings
        ]);
    }

    public function save(Request $request)
    {
        $input = $request->validate([
            'CLASSROOM.price_per_class' => 'required|numeric',
            'CLASSROOM.duration' => 'required|integer',
            'TEACHER.salary_per_class' => 'required|numeric'
        ]);
        
        foreach ($input as $group => $settings) {
            foreach ($settings as $setting => $value) {
                $key = $group .'.'. $setting;
                WebsiteSetting::updateOrInsert(['key' => $key], ['value' => $value]);
            }
        }

        return redirect(route('settings.index'))->with('saveSuccess', true);
    }

    private function parseWebsiteSettings($settings)
    {
        $builder = [];
        foreach ($settings as $setting) {
            $keys = explode('.', $setting->key);
            $builder[$keys[0]][$keys[1]] = $setting->value;
        }

        return $builder;
    }
}
