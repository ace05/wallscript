<?php

namespace App\Repositories;

use DB;
use Auth;
use Event;
use Carbon\Carbon;
use App\Entities\Post;
use App\Entities\Update;
use App\Entities\FriendTag;
use App\Events\NotificationEvent;

/**
 * Class PostRepository
 * @package namespace App\Repositories;
 */
class PostRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Post::class;
    }

    public function createPost(array $data)
    {
    	$post = [];
        $post['message'] = $data['message'];
        $post['user_id'] = Auth::user()->id;
        
        if(empty($data['place']) === false){
        	$post['place'] = $data['place'];
        	$post['lat'] = $data['lat'];
        	$post['lng'] = $data['lng'];
        }

        if(empty($data['external_data']) === false){
            $post['external_data'] = serialize(json_encode($data['external_data']));
        }

        if($data['type'] === 'profile'){
            $post['wall_user_id'] = $data['to_user_id'];
        }
        $updatedPost = $this->create($post);
        if(empty($updatedPost->id) === false){
            $updateData = [
                'post_id' => $updatedPost->id,
                'user_id' => Auth::user()->id,
                'update_type' => 'post',
                'privacy' => empty($data['privacy']) === false ? $data['privacy'] : 1
            ];            
            $postUpdate = Update::create($updateData);
            if(empty($postUpdate->id) === false){
                if(empty($data['friends']) == false){
                    foreach ($data['friends'] as $key => $friend) {
                        $tags = FriendTag::create([
                            'friend_id' => $friend,
                            'post_id' => $updatedPost->id
                        ]);
                        Event::fire(new NotificationEvent([
                            'update_id' => $postUpdate->id,
                            'from_user_id' =>  $post['user_id'],
                            'to_user_id' => $friend,
                            'type' => 'friendTag',
                            'action' => 'add'
                        ]));
                    }
                }
        	    
                return $postUpdate;                
            }
        }

        return false;
    }

    public function updateShareCount($postId)
    {
        return $this->model->where('id','=', $postId)
                        ->update([
                           'share_count' => \DB::raw('share_count + 1'),
                           'updated_at' => Carbon::now()
                        ]);           
    }

    public function getPostList()
    {
        return $this->model->orderBy('created_at', 'desc')
                    ->paginate(15);
    }

    public function deletePost($postId)
    {
        return $this->model->where('id','=', $postId)
                        ->first()
                        ->delete();  
    }
}
