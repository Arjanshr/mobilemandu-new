<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::paginate(100);
        return view('setting.index', compact('settings'));
    }

    public function create()
    {
        return view('setting.form');
    }

    public function insert(SettingRequest $request)
    {
        $setting = Setting::create($request->validated());
        return redirect()->route('settings')->withSuccess('Setting Created Successfully!');;
    }

    public function show(Setting $setting)
    {
        return view('setting.show', compact('setting'));
    }

    public function edit(Setting $setting)
    {
        return view('setting.form', compact('setting'));
    }

    public function update(Setting $setting, SettingRequest $request)
    {
        $setting->field = $request->field;
        $setting->type = $request->type;
        $setting->default_value = $request->default_value;
        $setting->initial_value = $setting->current_value;
        $setting->current_value = $request->current_value;
        $setting->help_text = $request->help_text;
        $setting->status = $request->status;
        $setting->save();
        return redirect()->route('settings')->withSuccess('Setting Edited Successfully!');;
    }

    public function delete(Setting $setting)
    {
        if (File::exists(storage_path("app/public/settings/$setting->initial_value")))
            File::delete(storage_path("app/public/settings/$setting->initial_value"));
        if (File::exists(storage_path("app/public/settings/$setting->current_value")))
            File::delete(storage_path("app/public/settings/$setting->current_value"));
        $setting->delete();
        return redirect()->route('settings')->withSuccess('Setting Deleted Successfully!');
    }

    public function generalSettings()
    {
        $settings = Setting::where('status', 1)->get();
        return view('setting.general-settings', compact('settings'));
    }

    public function updateGeneralSettings(Request $request)
    {
        foreach ($request->except(['_token', '_method']) as $element => $value) {
            $setting = Setting::where('field', $element)->first();
            if (!$request->hasFile($element)) {
                if ($setting->current_value != $value)
                    $setting->initial_value = $setting->current_value;
                $setting->current_value = $value;
            } else {
                $request->validate([
                    $element => 'required|mimes:jpeg,png,jpg,gif,svg,ico|max:2048',
                ]);
                $image_name = rand(0, 99999) . time() . '.' . $request->$element->extension();
                $request->$element->move(storage_path('app/public/settings'), $image_name);

                if (File::exists(storage_path("app/public/settings/$setting->initial_value")))
                    File::delete(storage_path("app/public/settings/$setting->initial_value"));
                $setting->initial_value = $setting->current_value;
                $setting->current_value = $image_name;
            }

            $setting->save();
        }
        return redirect()->back()->withSuccess('General Setting Updated Successfully!');
    }
}
