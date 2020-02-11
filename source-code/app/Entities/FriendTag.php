<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FriendTag extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $softDelete = true;

    protected $fillable = ['post_id', 'friend_id', 'deleted_at'];

    public function friend()
    {
        return $this->belongsTo('App\Entities\User', 'friend_id')->where('is_blocked', '=', 0);
    }
}
