<?php

namespace App\Repositories;

use App\Entities\EmailTemplate;

/**
 * Class EmailTemplateRepository
 * @package namespace App\Repositories;
 */
class EmailTemplateRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EmailTemplate::class;
    }

    public function getEmailTemplate($slug)
    {
        return $this->model->where('slug', '=', $slug)
                    ->first();
    }
}
