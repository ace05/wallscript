<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $softDelete = true;

    protected $fillable = ['post_id', 'comment', 'user_id', 'update_id', 'deleted_at'];

    public function image()
    {
        return $this->hasOne('App\Entities\Attachment', 'foreign_id')->where('type', '=', 'Comment');
    }

    public function post()
    {
        return $this->belongsTo('App\Entities\Post', 'post_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Entities\User')->where('is_blocked', '=', 0);
    }
}
