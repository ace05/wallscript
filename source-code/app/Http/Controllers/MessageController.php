<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Libraries\Oembed;
use Illuminate\Http\Request;
use App\Http\Requests\Message;
use App\Libraries\FileUploader;
use App\Exceptions\UserNotFound;
use App\Repositories\UserRepository;
use App\Repositories\MessageRepository;
use App\Repositories\AttachmentRepository;
use App\Repositories\ConversationRepository;

class MessageController extends Controller
{
	public function index(
		$username, Request $request, UserRepository $userRepo, 
		ConversationRepository $conversionRepo, MessageRepository $messageRepo
	)
    {    	
    	if(empty($username) === true)  throw new UserNotFound(trans('message.user_not_found'));

    	$userId = Auth::user()->id;
    	$data = [];
    	$data['username'] = $username;
    	$data['messages'] = [];
    	if($username != 'all'){
	    	$user = $userRepo->getUserDetails($username);
	        if(empty($user->id) === true){
	            throw new UserNotFound(trans('message.user_not_found'));            
	        }
    		$conversation = $conversionRepo->checkConversationExists($userId, $user->id);
    		if(empty($conversation) === true){
	    		$conversation = $conversionRepo->addConversation($userId, $user->id);
    		}
    		$updateStatus = $messageRepo->markAsRead($conversation->id, Auth::user()->id);
    		$data['conversation'] = $conversation;
    		$data['conversationUserId'] = $user->id;
    		$data['conversationId'] = $conversation->id;
    		$data['messages'] = $messageRepo->getMessages($conversation->id);
    		if($request->ajax() === true){
    			return view('frontend.partials.messages', $data);
    		}
    	}

        $data['conversations'] = $conversionRepo->getConversationByUser($userId);

        return view('frontend.messages.home', $data);
    }

    public function sendMessage(
    	Request $request, MessageRepository $messageRepo, AttachmentRepository $fileRepo
    )
    {
    	if($request->ajax() === false) return redirect(route('userHome'));
    	
    	$validatorRules = [];
    	$validatorRules['message'] = 'required';
    	$file = $request->file('photos');
    	if(empty($file) === false){
    		$validatorRules['photos'] = 'mimes:jpeg,gif,jpg,png|max:'.config('site.max_upload_file_size');
    	}

    	$validator = Validator::make($request->all(), $validatorRules);
        if ($validator->fails()) {
            $messages = $validator->messages();            
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
        
    	$oEmbed = app()->make(Oembed::class);
        $data = $request->all();
        $data['external_data'] = $oEmbed->processContent($request->get('message'));
    	$message = $messageRepo->addMessage($data);
    	$photo = $request->file('photos');
    	if(empty($message->id) === false){
    		if(empty($photo) === false){
	            $uploader = app()->make(FileUploader::class);
	            $fileExt = $photo->getClientOriginalExtension();
	            $fileName = $photo->getClientOriginalName();
	            $destinationPath = sprintf('%s/%s-%s.%s', 'MessagePhoto', $message->id, md5(microtime(true)), $fileExt);
	            if($uploader->upload($photo, $destinationPath)){
	                $attachment = $fileRepo->create([
	                    'filename' => basename($destinationPath),
	                    'type' => 'MessagePhoto',
	                    'foreign_id' => $message->id,
	                    'user_id' => Auth::user()->id,
	                    'path' => $destinationPath
	                ]);
	            }
	        }

	        return view('frontend.partials.message', ['message' => $message]);
    	}
    }

    public function getConversationList(
    	Request $request, ConversationRepository $conversationRepo
    )
    {
    	if($request->ajax() === false) return redirect(route('userHome'));

        $messages = $conversationRepo->getCountorNotificationMessages(Auth::user()->id, 'list');
        
        return view('frontend.partials.message_notifications', ['messages' => $messages]);
        
    }

    public function markAsRead(
    	$conversationId, Request $request, MessageRepository $messageRepo
    ){
    	if($request->ajax() === false) return redirect(route('userHome'));

    	if($messageRepo->markAsRead($conversationId, Auth::user()->id)){
    		return response()->json(['status' => true]);
    	}

    	return response()->json(['status' => false]);
    }
}