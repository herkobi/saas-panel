<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Settings\SettingsUpdateRequest;
use App\Models\Admin\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.settings.index', [
            'settings' => $settings
        ]);
    }

    public function update(SettingsUpdateRequest $request): RedirectResponse
    {

        $data = [];

        $data['title'] = $request->title;
        $data['slogan'] = $request->slogan;

        if($request->hasFile('logo')) {
            Storage::delete(config('panel.logo'));
            $logo_name = 'logo.' . $request->logo->extension();
            $request->logo->storeAs('logo', $logo_name);
            $data['logo'] = 'logo/' . $logo_name;
        } else {
            $data['logo'] = config('panel.logo');
        }

        if($request->hasFile('favicon')) {
            Storage::delete(config('panel.favicon'));
            $favicon_name = 'favicon.' . $request->favicon->extension();
            $request->favicon->storeAs('favicon', $favicon_name);
            $data['favicon'] = 'favicon/' . $favicon_name;
        } else {
            $data['favicon'] = config('panel.favicon');
        }

        $value = json_encode($data);

        Setting::firstWhere('key', 'settings')->update([
            'value' => $value
        ]);

        return Redirect::route('panel.settings.general')->with('success', __('admin/settings/general.update.success'));

    }
}
