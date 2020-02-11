<?php

namespace App\Repositories;

use Auth;
use App\Entities\Notification;

/**
 * Class NotificationRepositoryEloquent
 * @package namespace App\Repositories;
 */
class NotificationRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Notification::class;
    }

    public function getCount()
    {
    	return $this->model->where('to_user_id', '=', Auth::user()->id)
                        ->where('is_read', '=', 0)
    					->count();
    }

    public function getUserNotificationList()
    {
        return $this->model->where('to_user_id', '=', Auth::user()->id)
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
    }

    public function updateNotificationReadStatus()
    {
        return $this->model->where('to_user_id', '=', Auth::user()->id)
                        ->where('is_read', '=', 0)
                        ->update(['is_read' => 1]);
    }

    public function getAllNotifications()
    {
        return $this->model->where('to_user_id', '=', Auth::user()->id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
    }
}	
