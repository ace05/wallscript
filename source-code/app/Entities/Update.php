<?php

namespace App\Entities;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Update extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $softDelete = true;

    protected $fillable = [
    	'user_id', 'to_user_id', 'post_id', 'update_type', 'privacy', 'deleted_at'
    ];

    public function post()
    {
        return $this->belongsTo('App\Entities\Post', 'post_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Entities\User')->where('is_blocked', '=', 0);
    }    

    public function getCurrentPostLike($postId)
    {
        return Like::where('user_id', '=', Auth::user()->id)
                    ->where('post_id', '=', $postId)
                    ->orderBy('created_at', 'desc')
                    ->first();
    }

    public function getPostById($postId)
    {
        return Post::where('post_id', '=', $postId)
                    ->first();
    }


}
