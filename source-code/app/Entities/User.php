<?php

namespace App\Entities;

use Auth;
use App\Entities\Friend;
use App\Entities\Observers\UserObserver;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
    	'name', 'email', 'password', 'username', 'email_verification_token', 'email_token_created', 'is_email_verified', 'is_blocked', 'is_active', 'source', 'social_data', 'cover_photo_position', 'last_login'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profilePicture()
    {
        return $this->hasOne('App\Entities\Attachment', 'foreign_id')->where('type','=','User');
    }

    public function profile()
    {
        return $this->hasOne('App\Entities\UserProfile');
    }

    public function coverPhoto()
    {
        return $this->hasOne('App\Entities\Attachment', 'foreign_id')->where('type','=','CoverPhoto');
    }

    public function getFriendShipDetails($userId, $type = '')
    {
        $friend = Friend::select('id', 'first_user_id', 'second_user_id', 'friend_status')
            ->where(function($query) use ($userId){
                    $query->where('first_user_id', '=', $userId)
                        ->orWhere('second_user_id', '=', $userId);
                });
            
        if(empty($type) === false && $type = 'limit'){
            return $friend->where('friend_status', '=', 1)
                        ->limit(12)->get();        
        }

        return $friend->where(function($query){
                    $query->where('first_user_id', '=', Auth::user()->id)
                        ->orWhere('second_user_id', '=', Auth::user()->id);
                })->first();
    }

    public function getRecentUsers()
    {
        return User::where('is_active', '=', 1)
                ->where('is_blocked', '=', 0)
                ->limit(10)
                ->orderBy('created_at', 'desc')
                ->get();
    }
}
