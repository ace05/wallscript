<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use App\Repositories\UpdateRepository;
use App\Repositories\FriendRepository;
use App\Repositories\CommentRepository;
use App\Repositories\MessageRepository;
use App\Repositories\AttachmentRepository;
use App\Repositories\ConversationRepository;
use App\Http\Controllers\Admin\BaseAdminController;

class AdminHomeController extends BaseAdminController
{
    public function getHome(
    	Request $request, UserRepository $userRepo, PostRepository $postRepo, UpdateRepository $updateRepo,
    	FriendRepository $friendRepo, MessageRepository $messageRepo, AttachmentRepository $attachmentRepo,
    	ConversationRepository $conversationRepo, CommentRepository $commentRepo
    )
    {	
        return view('admin.dashboard.home', [
        	'userCount' => $userRepo->getRepoCounts(),
        	'postCount' => $postRepo->getRepoCounts(),
        	'commentCount' => $commentRepo->getRepoCounts(),
        	'updateCount' => $updateRepo->getRepoCounts(),
        	'friendCount' => $friendRepo->getRepoCounts(['friend_status' => 1]),
        	'messageCount' => $messageRepo->getRepoCounts(),
        	'attachmentCount' => $attachmentRepo->getRepoCounts(),
        	'conversationCount' => $conversationRepo->getRepoCounts()
        ]);
    }

}
