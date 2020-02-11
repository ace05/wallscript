<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Post extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $fillable = [
    	'user_id', 'group_id', 'message', 'external_data', 'deleted_at', 
    	'place', 'lat', 'lng', 'comments_count', 'share_count', 'likes_count',
        'wall_user_id'
    ];

    protected static function boot() {
        
        parent::boot();

        static::deleted(function($post) {
            $post->comments()->delete();
            $post->likes()->delete();
            $post->updates()->delete();
            $post->getTaggedFriends()->delete();
        });
    }


    public function images()
    {
        return $this->hasMany('App\Entities\Attachment', 'foreign_id')->where('type', '=', 'Post');
    }

    public function user()
    {
        return $this->belongsTo('App\Entities\User')->where('is_blocked', '=', 0);
    }

    public function comments()
    {
        return $this->hasMany('App\Entities\Comment', 'post_id');
    }

    public function updates()
    {
        return $this->hasMany('App\Entities\Update', 'post_id');
    }

    public function likes()
    {
        return $this->hasMany('App\Entities\Like', 'post_id');
    }

    public function getLastTwoComments()
    {
        return $this->hasMany('App\Entities\Comment', 'post_id')->orderBy('created_at', 'asc')
        														->limit(2);
    }

    public function toUser()
    {
        return $this->belongsTo('App\Entities\User', 'wall_user_id')->where('is_blocked', '=', 0);
    }

    public function getTaggedFriends()
    {
        return $this->hasMany('App\Entities\FriendTag', 'post_id');
    }
}
