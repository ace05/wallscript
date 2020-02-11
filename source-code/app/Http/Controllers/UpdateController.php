<?php

namespace App\Http\Controllers;

use Auth;
use Event;
use App\Libraries\Oembed;
use App\Http\Requests\Post;
use Illuminate\Http\Request;
use App\Http\Requests\Comment;
use App\Libraries\FileUploader;
use App\Events\NotificationEvent;
use App\Repositories\LikeRepository;
use App\Repositories\PostRepository;
use App\Repositories\UpdateRepository;
use App\Repositories\FriendRepository;
use App\Repositories\CommentRepository;
use App\Repositories\AttachmentRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\ConversationRepository;

class UpdateController extends Controller
{
    public function postUpdate(
    	Post $request, PostRepository $postRepo, AttachmentRepository $fileRepo,
        UpdateRepository $updateRepo
    )
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $oEmbed = app()->make(Oembed::class);
        $data = $request->all();
        $data['external_data'] = $oEmbed->processContent($request->get('message'));
        $update = $postRepo->createPost($data);
        $files = $request->file('images');
        $response = [];
        $response['fileError'] = false;
        if(empty($files) === false){
        	$uploadedFiles = $this->uploadImages($files, $update->post_id, $fileRepo, 'Post');
        	if(empty($uploadedFiles) === false){
        		$response['fileError'] = true;
        	}
        }

        return view('frontend.partials.update', ['update' => $updateRepo->getUpdateById($update->id)]);
    }


    protected function uploadImages($files, $postId, $fileRepo, $type)
    {    	
        $response = [];
        if(empty($files) === false){
            $uploader = app()->make(FileUploader::class);
    		foreach ($files as $key => $file) {
    			$fileExt = $file->getClientOriginalExtension();
    			$fileName = $file->getClientOriginalName();
	            $destinationPath = sprintf('%s/%s-%s.%s', $type, $postId, md5(microtime(true)), $fileExt);
	            if($uploader->upload($file, $destinationPath)){
	            	$postImage = $fileRepo->create([
                            'filename' => basename($destinationPath),
                            'type' => $type,
                            'foreign_id' => $postId,
                            'user_id' => Auth::user()->id,
                            'path' => $destinationPath
                        ]);
	            	if(empty($postImage->id) === true){
	            		array_push($response, $fileName);
	            	}
	            }else{
	                array_push($response, $fileName);
	            }
    		}
    	}

        return $response;
    }

    public function addComment(
        Comment $request, CommentRepository $commentRepo, AttachmentRepository $fileRepo
    )
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $commentImages = [];
        $response = [];
        $response['fileError'] = false;
        $comment = $commentRepo->createComment($request->all());
        $commentId = $comment['comment']->id;
        $file = $request->file('commentImage');
        if(empty($file) === false){
            array_push($commentImages, $file);
            $uploadedFiles = $this->uploadImages($commentImages, $commentId, $fileRepo, 'Comment');
            if(empty($uploadedFiles) === false){
                $response['fileError'] = true;
            }

        }
        $comment['updateId'] = empty($comment['update']->id) === false ? $comment['update']->id : '';
        $comment['updateType'] = 'comment';
        return view('frontend.partials.comment', $comment);
    }

    public function getAllcomments($postId, Request $request, CommentRepository $commentRepo)
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $comments = $commentRepo->getAllComments($postId);

        return view('frontend.partials.comments', ['comments' => $comments, 'retrive' => 'all', 'updateType' => 'comment']);
    }

    public function addLike(
        $postId, $updateId, $type, Request $request, LikeRepository $likeRepo
    )
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $response = $likeRepo->addLike($postId, $type, $updateId);

        $likes = $likeRepo->getAllLikes($postId);

        $res['html'] = view('frontend.partials.like', ['likes' => $likes, 'postId' => $postId])->render();
        $res['url'] = route('removeLike', ['id' => $response['like']->id , 'postId' => $postId, 'updateId' => $response['update']->id]);
        $res['context'] = getLikeTextByType($type);

        return response()->json($res);
    }


    public function removeLike($id, $postId, $updateId, Request $request, LikeRepository $likeRepo)
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $response = $likeRepo->removeLike($id, $postId, $updateId);

        $likes = $likeRepo->getAllLikes($postId);

        $res['html'] = view('frontend.partials.like', ['likes' => $likes, 'postId' => $postId])->render();
        $res['url'] = route('addLike', ['postId' => $postId, 'type' => 'like', 'updateId' => $updateId]);
        $res['context'] = getLikeTextByType('new');

        return response()->json($res);
    }

    public function getAllLikes($postId, Request $request, LikeRepository $likeRepo)
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $likes = $likeRepo->getAllLikes($postId);

        return view('frontend.partials.likes', ['likes' => $likes]);
    }

    public function deletePost($updateId, $postId, $type, Request $request, UpdateRepository $updateRepo)
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $res['status'] = false;
        if(in_array($type, ['comment', 'like', 'share'])) {
            $res['type'] = 'update';
        }
        else{
            $res['type'] = 'post';
        }

        if($updateRepo->deleteUpdate($updateId, $postId, $type)){
            $res['status'] = true;
        }

        return response()->json($res);
    }

    public function deleteComment(
        $id, $updateId, $postId, $type, Request $request, CommentRepository $commentRepo
    )
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $res['status'] = false;
        $res['type'] = $type;
        $res['updateId'] = $updateId;
        if($commentRepo->deleteComment($id, $updateId, $postId)){
            $res['status'] = true;
        }

        return response()->json($res);
    }

    public function shareUpdate(
        $postId, UpdateRepository $updateRepo, Request $request,
        PostRepository $postRepo
    )
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $res['status'] = false;
        $share = $updateRepo->create([
            'user_id' => Auth::user()->id,
            'post_id' => $postId,
            'update_type' => 'share'
        ]);
        if(empty($share->id) === false){
            if($postRepo->updateShareCount($postId)){
                Event::fire(new NotificationEvent([
                    'update_id' => $share->id,
                    'from_user_id' => Auth::user()->id,
                    'to_user_id' => $share->post->user_id,
                    'type' => 'share',
                    'action' => 'add'
                ]));
                $res['status'] = true;
                $res['text'] = trans('app.unshare');                
            }
        }

        return response()->json($res);
    }

    public function getNotificationCount(
        Request $request, NotificationRepository $notificationRepo, FriendRepository $friendRepo,
        ConversationRepository $conversationRepo
    )
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        return response()->json([
            'count' => $notificationRepo->getCount(),
            'friendCount' => $friendRepo->getCount(),
            'messageCount' => $conversationRepo->getCountorNotificationMessages(Auth::user()->id)
        ]);
    }

    public function getUserNotificationList(Request $request, NotificationRepository $notificationRepo)
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $notifications = $notificationRepo->getUserNotificationList();
        $update = $notificationRepo->updateNotificationReadStatus();
        
        return view('frontend.partials.user_notifications', ['notifications' => $notifications]);
        
    }

    public function getFriendRequestList(Request $request, FriendRepository $friendRepo)
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $friendRequests = $friendRepo->getFriendRequestList();

        return view('frontend.partials.friend_requests', ['friendRequests' => $friendRequests]);
    }

    public function viewUpdate($id, Request $request, UpdateRepository $updateRepo)
    {
        $update = $updateRepo->getUpdateById($id);
        if(empty($update->upid) === true){
            return redirect(route('userHome'))->with('error', trans('message.update_not_found'));
        }

        if($request->ajax() === true) return view('frontend.partials.update_modal_box', ['updateObj' => $updateRepo->getUpdateById($id)]);

        return view('frontend.update.view', ['update' => $updateRepo->getUpdateById($id)]); 
    }

    
}
