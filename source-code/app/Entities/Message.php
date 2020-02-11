<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Message extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['message', 'is_read', 'user_id', 'conversation_id', 'external_data'];

    public function user()
    {
        return $this->belongsTo('App\Entities\User', 'user_id')->where('is_blocked', '=', 0);
    }

    public function photo()
    {
        return $this->hasOne('App\Entities\Attachment', 'foreign_id')->where('type','=','MessagePhoto');
    }

}
