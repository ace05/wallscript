<?php

namespace App\Repositories;

use App\Entities\Attachment;

/**
 * Class AttachmentRepository
 * @package namespace App\Repositories;
 */
class AttachmentRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Attachment::class;
    }

    public function deleteAttachment($type, $id)
    {
    	return $this->model->where('type', '=', $type)
    				->where('foreign_id', '=', $id)
    				->delete();
    }

    public function getAttachmentsByTypes($types, $userId)
    {
        return $this->model->whereIn('type', $types)
                    ->where('user_id', '=', $userId)
                    ->paginate(20);
    }
}
