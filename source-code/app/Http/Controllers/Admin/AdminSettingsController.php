<?php

namespace App\Http\Controllers\Admin;

use Cache;
use Illuminate\Http\Request;
use App\Repositories\SettingRepository;
use App\Repositories\SettingCategoryRepository;
use App\Http\Controllers\Admin\BaseAdminController;

class AdminSettingsController extends BaseAdminController
{
    public function getSettings(
    	$slug, Request $request, SettingRepository $settingRepo,
        SettingCategoryRepository $settingCategoryRepo
    ){	
       $category = $settingCategoryRepo->getSettingCategory($slug);
       
       return view('admin.settings.settings', ['settingCategory' => $category]);
    }

    public function updateSettings($slug, Request $request, SettingRepository $settings)
    {
    	if($settings->updateSettings($request->except(['_token']))){
    		Cache::forget('settings');
    		return redirect(route('adminSettings', ['slug' => $slug]))->with('success', trans('message.setting_updated_successfully'));
    	}

    	return redirect(route('adminSettings', ['slug' => $slug]))->with('error', trans('message.setting_updated_failed'));
    }
}
