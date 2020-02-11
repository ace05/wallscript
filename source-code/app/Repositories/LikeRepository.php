<?php

namespace App\Repositories;

use Auth;
use Event;
use Carbon\Carbon;
use App\Entities\Like;
use App\Entities\Post;
use App\Entities\Update;
use App\Events\NotificationEvent;

/**
 * Class LikeRepository
 * @package namespace App\Repositories;
 */
class LikeRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Like::class;
    }

    public function addLike($postId, $emotionType, $updateId)
    {
        $response = [];
        $like = $this->model->where('post_id', '=', $postId)
                    ->where('user_id', '=', Auth::user()->id)
                    ->first();
                    
        if(empty($like->id) === false){
            $response['like'] = $like;
            $updateLike = $this->model->where('id', '=', $like->id)
                                ->update([
                                    'type' => $emotionType
                                ]);
            if(empty($updateLike) === false){
                $response['update'] = Update::where('post_id', '=', $postId)
                    ->where('user_id', '=', Auth::user()->id)
                    ->where('update_type', '=', 'like')
                    ->first();                
                return $response;                
            }
        }

        $response['like'] = $this->model->create([
            'post_id' => $postId,
            'type' => $emotionType,
            'update_id' => $updateId,
            'user_id' => Auth::user()->id
        ]);

        if(empty($response['like']->id) === false){
            $response['update'] = Update::create([
                'user_id' => Auth::user()->id,
                'post_id' => $postId,
                'update_type' => 'like',
                'privacy' => empty($data['privacy']) === false ? $data['privacy'] : 1
            ]);
            $postCount = Post::where('id','=', $postId)
                            ->update([
                               'likes_count' => \DB::raw('likes_count + 1'),
                               'updated_at' => Carbon::now()
                            ]);
            $likeUpdate = $this->model->where('id', '=', $response['like']->id)
                                ->update(['update_id' => $response['update']->id]);
            if(empty($response['update']->post->user_id) === false && Auth::user()->id != $response['update']->post->user_id){
                Event::fire(new NotificationEvent([
                    'update_id' => $response['update']->id,
                    'from_user_id' => Auth::user()->id,
                    'to_user_id' => $response['update']->post->user_id,
                    'type' => 'like',
                    'action' => 'add'
                ]));                    
            }
        }

        return $response;
    }

    public function removeLike($id, $postId, $updateId)
    {
        if(Like::where('id', '=', $id)->delete()){
            if(Update::where('id', '=', $updateId)->delete()){
                $postCount = Post::where('id','=', $postId)
                            ->update([
                               'likes_count' => \DB::raw('likes_count - 1'),
                               'updated_at' => Carbon::now()
                            ]);
               $post = Post::where('id', '=', $postId)
                            ->first();
                if(empty($post->user_id) === false && Auth::user()->id != $post->user_id){
                    Event::fire(new NotificationEvent([
                        'update_id' => $updateId,
                        'from_user_id' => Auth::user()->id,
                        'to_user_id' => $post->user_id,
                        'type' => 'like',
                        'action' => 'remove'
                    ])); 
                }
            }
        }

        return true;
    }

    public function getAllLikes($postId)
    {
        return $this->model->with(['user'])
                    ->where('post_id', '=', $postId)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

}
