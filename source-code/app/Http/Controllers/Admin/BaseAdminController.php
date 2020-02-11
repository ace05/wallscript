<?php

namespace App\Http\Controllers\Admin;

use View;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\SettingCategoryRepository;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class BaseAdminController extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function __construct(SettingCategoryRepository $settingCategories)
	{
		View::share('settingCategories', $settingCategories->getCategories());	    
	}

}
