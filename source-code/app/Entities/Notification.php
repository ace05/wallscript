<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $fillable = ['update_id', 'from_user_id', 'to_user_id', 'type', 'is_read', 'deleted_at'];

    public function fromUser()
    {
        return $this->belongsTo('App\Entities\User', 'from_user_id')->where('is_blocked', '=', 0);
    }

    public function toUser()
    {
        return $this->belongsTo('App\Entities\User', 'to_user_id')->where('is_blocked', '=', 0);
    }
}
