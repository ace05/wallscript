<?php

namespace App\Repositories;

use DB;
use Auth;
use Carbon\Carbon;
use App\Entities\Post;
use App\Entities\Like;
use App\Entities\Update;
use App\Entities\Comment;

/**
 * Class UpdateRepository
 * @package namespace App\Repositories;
 */
class UpdateRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Update::class;
    }

    public function getUpdateById($id)
    {
        return $this->model->where('id', '=', $id)
                    ->select('updates.post_id', 'updates.id', 'updates.id as upid','updates.privacy', 'updates.user_id', 'updates.updated_at', 'updates.created_at', 'updates.update_type')
                    ->first();
    }

    public function getFeeds($userId)
    {
        $query = $this->model->select(
                'posts.deleted_at','friends.first_user_id','friends.second_user_id', 
                'friends.friend_status','users.is_blocked', 'updates.user_id', 
                'updates.post_id', 'updates.privacy',
                \DB::raw('MAX(updates.id) as latestId')
            )->join('users', 'updates.user_id', '=', 'users.id')
            ->join('posts', 'posts.id', '=', 'updates.post_id')
            ->join('friends', function($join) use ($userId){
                $join->on(DB::raw('CASE
                            WHEN friends.first_user_id = '.$userId.'
                            THEN friends.second_user_id = updates.user_id
                            WHEN friends.second_user_id= '.$userId.'
                            THEN friends.first_user_id = updates.user_id
                            END'
                        ), DB::raw(''), DB::raw(''));
            })->whereRaw('updates.privacy in ("1","2")')
            ->whereRaw('friends.friend_status in ("1", "2")')
            ->whereRaw('users.is_blocked = 0')
            ->whereRaw('posts.deleted_at is null')
            ->whereRaw('updates.user_id !='.Auth::user()->id)
            ->groupBy('updates.post_id');

        return  \DB::table(DB::raw("(".$query->toSql().") as sub"))
                    ->orderBy('sub.latestId', 'DESC')
                    ->paginate(15);
    }

    public function getUserFeeds($userId, $friendShip)
    {
        $privacy = '1';
        if(empty($friendShip->id) === false) $privacy = '1,2';
        $query = $this->model->select(
                'posts.deleted_at','users.is_blocked', 'updates.user_id', 
                'updates.post_id', 'updates.privacy', 'posts.wall_user_id',
                \DB::raw('MAX(updates.id) as latestId')
            )->join('users', 'updates.user_id', '=', 'users.id')
            ->join('posts', 'posts.id', '=', 'updates.post_id')
            ->whereRaw('updates.privacy in ('.$privacy.')')
            ->whereRaw('users.is_blocked = 0')
            ->whereRaw('posts.deleted_at is null')
            ->Where(function ($query) use($userId) {
                $query->whereRaw('updates.user_id = '. $userId)
                    ->orwhereRaw('posts.wall_user_id = '. $userId);
            })
            ->groupBy('updates.post_id');

        return  \DB::table(DB::raw("(".$query->toSql().") as sub"))
                    ->orderBy('sub.latestId', 'DESC')
                    ->paginate(3);
    }

    public function deleteUpdate($id, $postId, $type)
    {
        if($this->model->where('id', '=', $id)->delete()){            
            if($type === 'comment'){
                if(Comment::where('update_id', '=', $id)->delete()){
                    return Post::where('id','=', $postId)
                            ->update([
                               'comments_count' => \DB::raw('comments_count + 1'),
                               'updated_at' => Carbon::now()
                            ]);
                }
            }
            elseif($type === 'like'){
                if(Like::where('update_id', '=', $id)->delete()){
                    return Post::where('id','=', $postId)
                            ->update([
                               'likes_count' => \DB::raw('likes_count + 1'),
                               'updated_at' => Carbon::now()
                            ]);
                }
            }
            elseif($type === 'post'){
                $post = Post::find($postId);
                return $post->delete();
            }
        }

        return false;
    }

    public function getUpdatesList()
    {
        return $this->model->orderBy('created_at', 'desc')
                    ->paginate(15);
    }

    public function deleteUpdateAsComment($id, $postId)
    {
        if($this->model->where('id', '=', $id)->delete()){
            if(Comment::where('update_id', '=', $id)->delete()){
                return Post::where('id','=', $postId)
                            ->update([
                               'comments_count' => \DB::raw('comments_count - 1'),
                               'updated_at' => Carbon::now()
                            ]);
            }
        }

        return false;
    }

    public function deleteUpdateAsLike($id, $postId)
    {
        if($this->model->where('id', '=', $id)->delete()){
            if(Like::where('update_id', '=', $id)->delete()){
                return Post::where('id','=', $postId)
                            ->update([
                               'likes_count' => \DB::raw('likes_count - 1'),
                               'updated_at' => Carbon::now()
                            ]);
            }
        }

        return false;
    }

    public function deleteUpdateAsShare($id, $postId)
    {
        if($this->model->where('id', '=', $id)->delete()){
            return Post::where('id','=', $postId)
                        ->update([
                           'share_count' => \DB::raw('share_count - 1'),
                           'updated_at' => Carbon::now()
                        ]);
        }

        return false;
    }

    public function deleteUpdateAsPost($id, $postId)
    {
        if($this->model->where('id', '=', $id)->delete()){
            $post = Post::find($postId);
            return $post->delete();
        }

        return false;
    }
}
