<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $softDelete = true;

    protected $fillable = ['post_id', 'user_id', 'type', 'deleted_at', 'update_id'];    

    public function user()
    {
        return $this->belongsTo('App\Entities\User')->where('is_blocked', '=', 0);
    }
}
