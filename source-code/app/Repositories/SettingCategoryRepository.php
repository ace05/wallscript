<?php

namespace App\Repositories;

use App\Entities\SettingCategory;

/**
 * Class SettingCategoryRepository
 * @package namespace App\Repositories;
 */
class SettingCategoryRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SettingCategory::class;
    }

    public function getCategories()
    {
        return $this->model->get();
    }

    public function getSettingCategory($slug)
    {
        return $this->model->where('slug', '=', $slug)
                    ->firstOrFail();
    }
}
