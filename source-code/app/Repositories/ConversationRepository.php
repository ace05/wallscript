<?php

namespace App\Repositories;

use DB;
use App\Entities\Message;
use App\Entities\Conversation;

/**
 * Class ConversationRepository
 * @package namespace App\Repositories;
 */
class ConversationRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Conversation::class;
    }

    public function getConversationByUser($userId)
    {
        return $this->model->select(DB::raw('users.*, conversations.id as cid, conversations.*'))
            ->join('users', function($join) use ($userId){
                $join->on(DB::raw('
                        CASE
                            WHEN conversations.first_user_id = '.$userId.'
                            THEN conversations.second_user_id = users.id
                            WHEN conversations.second_user_id = '.$userId.'
                            THEN conversations.first_user_id = users.id
                        END'
                        ), DB::raw(''), DB::raw(''));
            })->where(function($query) use ($userId){
                $query->where('first_user_id', '=', $userId)
                    ->orWhere('second_user_id', '=', $userId);
            })
            ->orderBy('conversations.updated_at', 'desc')
            ->get();
    }

    public function checkConversationExists($userOneId, $userTwoId)
    {
        return $this->model->where(function($query) use ($userOneId, $userTwoId){
                $query->where('first_user_id', '=', $userOneId)
                    ->Where('second_user_id', '=', $userTwoId);
            })->orWhere(function($query) use ($userOneId, $userTwoId){
                $query->where('second_user_id', '=', $userOneId)
                    ->Where('first_user_id', '=', $userTwoId);
            })->first();
    }

    public function addConversation($userOneId, $userTwoId)
    {
        return $this->model->create(['first_user_id' => $userOneId, 'second_user_id' => $userTwoId]);
    }

    public function getCountorNotificationMessages($userId, $type = 'count')
    {
        $conversations = $this->model->where(function($query) use ($userId){
                $query->where('first_user_id', '=', $userId)
                    ->orWhere('second_user_id', '=', $userId);
            })->get();
        $count = 0;
        $res = [];
        if(empty($conversations) === false){
            foreach ($conversations as $key => $conversation) {                                
                $message = Message::where('conversation_id', '=', $conversation->id)
                                    ->where('user_id', '!=', $userId)
                                    ->where('is_read', '=', 0);
                if($type === 'count'){
                    $messageCount = $message->count();
                    $count = $count + $messageCount;
                }
                else{
                    $data = [];
                    $lastMessage = $message->orderBy('created_at', 'desc')
                                            ->first();
                    if(empty($lastMessage) === false){
                        $data['conversation'] = $conversation;
                        $data['lastMessage'] = $lastMessage;
                        array_push($res, $data);
                    }
                }
            }
        }

        return ($type == 'count') ? $count : $res;
    }


}
