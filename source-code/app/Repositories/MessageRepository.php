<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Entities\Message;
use App\Entities\Conversation;

/**
 * Class MessageRepository
 * @package namespace App\Repositories;
 */
class MessageRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Message::class;
    }

    public function getMessages($conversationId)
    {
        return $this->model->where('conversation_id', '=', $conversationId)
                    ->orderBy('id', 'desc')
                    ->paginate(20);
    }

    public function addMessage($data)
    {
        $message = [];
        $message['message'] = $data['message'];
        $message['user_id'] = $data['user_id'];
        $message['conversation_id'] = $data['conversation_id'];
        if(empty($data['external_data']) === false){
            $message['external_data'] = serialize(json_encode($data['external_data']));
        }

        $message = $this->model->create($message);
        if(empty($message->id) === false){
            $updateConversation = Conversation::where('id', '=', $data['conversation_id'])
                                        ->update(['updated_at' => Carbon::now()]);
            return $message;
        }

        return false;
    }

    public function markAsRead($cid, $userId)
    {
        return $this->model->where('conversation_id', '=', $cid)
                    ->where('user_id', '!=', $userId)
                    ->where('is_read', '=', 0)
                    ->update(['is_read' => 1]);
    }
}
