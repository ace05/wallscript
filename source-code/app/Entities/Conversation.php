<?php

namespace App\Entities;

use App\Entities\Message;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Conversation extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['first_user_id', 'second_user_id'];

    public function fromUser()
    {
        return $this->belongsTo('App\Entities\User', 'first_user_id')->where('is_blocked', '=', 0);
    }

    public function toUser()
    {
        return $this->belongsTo('App\Entities\User', 'second_user_id')->where('is_blocked', '=', 0);
    }

    public function getLatestMessage($conversationId)
    {
    	return Message::where('conversation_id', '=', $conversationId)
    			->orderBy('created_at', 'desc')
    			->first();
    }
}
