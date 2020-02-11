<?php

namespace App\Repositories;

use DB;
use Auth;
use Carbon\Carbon;
use App\Entities\Friend;
use App\Entities\UserProfile;

/**
 * Class FriendRepository
 * @package namespace App\Repositories;
 */
class FriendRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Friend::class;
    }

    public function addFriend($id)
    {
    	return $this->model->create([
    		'first_user_id' => Auth::user()->id,
    		'second_user_id' => $id,
    		'friend_status' => 0
    	]);
    }

    public function findById($id)
    {
    	return $this->model->where('id', '=', $id)
    				->first();
    }

    public function updateStatus($id)
    {
    	$updateStatus = $this->model->where('id', '=', $id)
    				    ->update(['friend_status' => 1]);
        if(empty($updateStatus) === false){
            $friend = $this->model->where('id', '=', $id)->first();
            if(empty($friend->first_user_id) === false){
                if(UserProfile::where('user_id','=', $friend->first_user_id)
                        ->orWhere('user_id','=', $friend->second_user_id)
                        ->update([
                           'friends_count' => \DB::raw('friends_count + 1'),
                           'updated_at' => Carbon::now()
                        ])){
                    return $friend;
                }               
            }
        }

        return false;
    }

    public function getCount()
    {
        return $this->model->where('second_user_id', '=', Auth::user()->id)
                        ->where('friend_status', '=', 0)
                        ->count();
    }

    public function getFriendRequestList()
    {
        return $this->model->where('second_user_id', '=', Auth::user()->id)
                    ->where('friend_status', '=', 0)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
    }

    public function getAllFriendRequest()
    {
        return $this->model->where('second_user_id', '=', Auth::user()->id)
                    ->where('friend_status', '=', 0)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
    }

    public function getUserFriends($userId)
    {
        return $this->model->select('id', 'first_user_id', 'second_user_id', 'friend_status')
            ->where(function($query) use ($userId){
                    $query->where('first_user_id', '=', $userId)
                        ->orWhere('second_user_id', '=', $userId);
                })
            ->where('friend_status', '=', 1)
            ->paginate(20);
    }

    public function searchUserFriends($query, $userId)
    {
        return $this->model->select('friends.id', 'users.id', 'users.name', 'friends.first_user_id', 'friends.second_user_id', 'friends.friend_status')
            ->join('users', function($join) use ($userId){
                $join->on(DB::raw('CASE
                        WHEN friends.first_user_id = '.$userId.'
                        THEN friends.second_user_id = users.id
                        WHEN friends.second_user_id= '.$userId.'
                        THEN friends.first_user_id= users.id
                    END'
                ), DB::raw(''), DB::raw(''));
            })
            ->where(function($query) use ($userId){
                $query->where('friends.first_user_id', '=', $userId)
                    ->orWhere('friends.second_user_id', '=', $userId);
            })
            ->where('users.name', 'LIKE', '%'.$query.'%')
            ->where('friends.friend_status', '=', 1)
            ->limit(10)
            ->lists('users.name as name', 'users.id as id');
    }

    public function unFriend($id){
        $friend = $this->model->where('id', '=', $id)->first();
        if(empty($friend->id) === false){
            if($this->model->where('id', '=', $id)->delete()){
                return UserProfile::where('user_id','=', $friend->first_user_id)
                        ->orWhere('user_id','=', $friend->second_user_id)
                        ->update([
                           'friends_count' => \DB::raw('friends_count - 1'),
                           'updated_at' => Carbon::now()
                        ]);               
            }
        }

        return false;
    }
}
