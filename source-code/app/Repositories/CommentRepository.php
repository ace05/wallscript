<?php

namespace App\Repositories;

use Auth;
use Event;
use Carbon\Carbon;
use App\Entities\Post;
use App\Entities\Update;
use App\Entities\Comment;
use App\Events\NotificationEvent;

/**
 * Class CommentRepository
 * @package namespace App\Repositories;
 */
class CommentRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Comment::class;
    }

    public function createComment(array $data)
    {
        $response['comment'] = $this->model->create([
            'post_id' => $data['post_id'],
            'user_id' => Auth::user()->id,
            'comment' => $data['comment']
        ]);

        if(empty($response['comment']->id) === false){
            $response['update'] = Update::create([
                'user_id' => Auth::user()->id,
                'post_id' => $data['post_id'],
                'update_type' => 'comment',
                'privacy' => empty($data['privacy']) === false ? $data['privacy'] : 1
            ]);
            if(empty($response['update']->id) === false){
                $updateComment = $this->model->where('id', '=', $response['comment']->id)
                                    ->update(['update_id' => $response['update']->id]);
                $postUpdate = Post::where('id','=', $data['post_id'])
                                    ->update([
                                       'comments_count' => \DB::raw('comments_count + 1'),
                                       'updated_at' => Carbon::now()
                                    ]);
                if(Auth::user()->id != $response['update']->post->user_id){
                    Event::fire(new NotificationEvent([
                        'update_id' => $response['update']->id,
                        'from_user_id' => Auth::user()->id,
                        'to_user_id' => $response['update']->post->user_id,
                        'type' => 'comment',
                        'action' => 'add'
                    ]));                    
                }
                return $response;
            }
        }

        return false;
    }

    public function getAllComments($postId)
    {
        return $this->model->where('post_id', '=', $postId)
                    ->orderBy('created_at', 'asc')
                    ->get();
    }

     public function deleteComment($id, $updateId, $postId)
    {
        if($this->model->where('id', '=', $id)->delete()){
            if(Update::where('id', '=', $updateId)->delete()){
                $post = Post::where('id', '=', $postId)
                            ->first();
                if(empty($post->user_id) === false && Auth::user()->id != $post->user_id){
                    Event::fire(new NotificationEvent([
                        'update_id' => $updateId,
                        'from_user_id' => Auth::user()->id,
                        'to_user_id' => $post->user_id,
                        'type' => 'comment',
                        'action' => 'remove'
                    ])); 
                }
                return Post::where('id','=', $postId)
                        ->update([
                           'comments_count' => \DB::raw('comments_count - 1'),
                            'updated_at' => Carbon::now()
                        ]); 
            }            
        }

        return false;
    }

    public function getCommentsList()
    {
        return $this->model->orderBy('created_at', 'asc')
                    ->paginate(15);
    }

    public function deleteAdminComment($id, $postId, $updateId)
    {
        if($this->model->where('id', '=', $id)->delete()){
            if(Update::where('id', '=', $updateId)->delete()){
                return Post::where('id','=', $postId)
                            ->update([
                               'comments_count' => \DB::raw('comments_count - 1'),
                               'updated_at' => Carbon::now()
                            ]);
            }
        }

        return false;

    }
}
