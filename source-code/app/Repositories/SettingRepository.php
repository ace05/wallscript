<?php

namespace App\Repositories;

use App\Entities\Setting;

/**
 * Class SettingRepository
 * @package namespace App\Repositories;
 */
class SettingRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Setting::class;
    }

    public function getSettings()
    {
    	return $this->model->lists('value', 'code')
    						->toArray();
    }

    public function updateSettings(array $settings)
    {
        if(empty($settings) === false){
            foreach ($settings as $key => $setting) {
                $update = $this->model->where('trans_key', '=', $key)
                                ->update(['value' => $setting]);
            }
        }

        return true;
    }
}
