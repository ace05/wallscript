<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use Event;
use Validator;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Libraries\CoverCrop;
use App\Libraries\FileUploader;
use App\Exceptions\UserNotFound;
use App\Events\NotificationEvent;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Repositories\FriendRepository;
use App\Repositories\UpdateRepository;
use App\Repositories\AttachmentRepository;
use App\Repositories\NotificationRepository;

class UserController extends Controller
{
    public function getProfile(
        $username, Request $request, UserRepository $userRepo, UpdateRepository $updateRepo
    )
    {
    	$user = $userRepo->getUserDetails($username);

        if(empty($user->id) === true){
            throw new UserNotFound(trans('message.user_not_found'));            
        }

        $friendShip = $userRepo->getFriendStatus($user->id);
        $updates = $updateRepo->getUserFeeds($user->id, $friendShip);
        if($request->ajax() === true) return view('frontend.partials.updates', ['updates' => $updates]);
        $layout = 'frontend.user.timeline';
        $title = $user->name."'s ".trans('app.profile');

        return view('frontend.user.profile', compact('user', 'updates', 'layout', 'title', 'friendShip'));
    }

    public function addFriend($id, Request $request, FriendRepository $friendRepo)
    {
    	if($request->ajax() === false) return redirect(route('userHome'));

    	$friend = $friendRepo->addFriend($id);
    	$res = [];
    	$res['status'] = false;
    	if(empty($friend->id) === false){
    		$res['text'] = trans('app.cancel_friend_request');
    		$res['link'] = route('cancelRequest', ['id' => $friend->id]);
    		$res['status'] = true;
    	}

    	return response()->json($res);
    }

    public function cancelRequest($id, Request $request, FriendRepository $friendRepo)
    {
    	if($request->ajax() === false) return redirect(route('userHome'));

    	$friend = $friendRepo->findById($id);
    	$res = [];
    	$res['status'] = false;
    	if(empty($friend->id) === false){
    		if($friendRepo->delete($friend->id)){
    			$res['text'] = trans('app.add_as_friend');
    			$res['link'] = route('addFriend', ['id' => $friend->second_user_id]);    			
    			$res['status'] = true;
    		}
    	}

    	return response()->json($res);

    }

    public function confirmFriend($id, Request $request, FriendRepository $friendRepo)
    {
    	if($request->ajax() === false) return redirect(route('userHome'));

    	$res = [];
    	$res['status'] = false;
        $friend = $friendRepo->updateStatus($id);
    	if(empty($friend->id) === false){
            $toUserId = ($friend->first_user_id == Auth::user()->id) ? $friend->second_user_id : $friend->first_user_id;
            Event::fire(new NotificationEvent([
                'update_id' => $toUserId,
                'from_user_id' => Auth::user()->id,
                'to_user_id' => $toUserId,
                'type' => 'confirmFriend',
                'action' => 'add'
            ]));
			$res['text'] = trans('app.friends');
			$res['link'] = '';	
			$res['status'] = true;
    	}

    	return response()->json($res);
    }

    public function viewAllNotifications(Request $request, NotificationRepository $notificationRepo)
    {
        $notifications = $notificationRepo->getAllNotifications();

        return view('frontend.user.all_notifications', ['notifications' => $notifications]);
    }

    public function viewAllFriendRequests(Request $request, FriendRepository $friendRepo)
    {
        $friends = $friendRepo->getAllFriendRequest();
        
        return view('frontend.user.all_friend_requests', ['friends' => $friends]);
    }

    public function uploadCoverPhoto(Request $request, AttachmentRepository $fileRepo)
    {
        $validator = Validator::make($request->all(), [
            'coverPhoto' => 'mimes:jpeg,gif,jpg,png|max:'.config('site.max_upload_file_size').'|dimensions:min_width=450,min_height=150',
        ],[
            'dimensions' => trans('message.dimension_validation')
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages(); 
            return response()->json([
                'status' => 422,
                'message' => 'Validation Failed',
                'errors' => $validator->messages()
            ]);
        }
        
        $res = [];
        $res['status'] = false;
        $coverImage = $request->file('coverPhoto');
        if(empty($coverImage) === false){
            $uploader = app()->make(FileUploader::class);
            $fileExt = $coverImage->getClientOriginalExtension();
            $fileName = $coverImage->getClientOriginalName();
            $destinationPath = sprintf('%s/%s-%s.%s', 'CoverPhoto', Auth::user()->id, md5(microtime(true)), $fileExt);
            if($uploader->upload($coverImage, $destinationPath)){
                $deleteExistingCover = $fileRepo->deleteAttachment('CoverPhoto', Auth::user()->id);
                if($attachment = $fileRepo->create([
                    'filename' => basename($destinationPath),
                    'type' => 'CoverPhoto',
                    'foreign_id' => Auth::user()->id,
                    'user_id' => Auth::user()->id,
                    'path' => $destinationPath
                ])){
                    $imgPath = getImagePath($attachment);
                    if(Image::doResize($imgPath, $imgPath, ['newWidth' => 1200, 'newHeight' => 630])){
                        $res['coverUrl'] = url('uploads/CoverPhoto/'.$attachment->filename);
                        $res['status'] = true;
                    }
                }
            }
        }

        return response()->json($res);
    }

    public function updateCoverPhoto(Request $request, UserRepository $userRepo)
    {
        $res = [];
        $res['status'] = false;
        $data = $request->all();
        if(empty($data['positions']) === true) $data['positions'] = 0;
        if($userRepo->updateCoverPosition($data)){
            $attachment = Auth::user()->coverPhoto;
            $positions = $data['positions'];
            $position = str_replace('-', '', str_replace('px;', '', trim($positions)));
            if(Image::coverPhotoCrop($attachment, 'coverThumb', $position)){
                $res['status'] = true;
                $res['url'] = url(getCoverUrl($attachment, 'coverThumb'));
            }
        }

        return response()->json($res);
    }

    public function getUserFriendsList(
        $username, Request $request, UserRepository $userRepo, FriendRepository $friendRepo
    )
    {
        $user = $userRepo->getUserDetails($username);

        if(empty($user->id) === true){
            throw new UserNotFound(trans('message.user_not_found'));            
        }

        $friends = $friendRepo->getUserFriends($user->id);
        $layout = 'frontend.user.friends';
        $title = $user->name."'s ".trans('app.friends');

        return view('frontend.user.profile', compact('user', 'friends', 'layout', 'title'));
    }

    public function uploadProfilePhoto(Request $request, AttachmentRepository $fileRepo)
    {
        $validator = Validator::make($request->all(), [
            'profilePhoto' => 'mimes:jpeg,gif,jpg,png|max:'.config('site.max_upload_file_size').'|dimensions:min_width=250,min_height=100',
        ],[
            'dimensions' => trans('message.profile_dimension_validation')
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages();            
            return response()->json([
                'status' => 422,
                'message' => 'Validation Failed',
                'errors' => $validator->messages()
            ]);
        }
        
        $res = [];
        $res['status'] = false;
        $profilePhoto = $request->file('profilePhoto');
        $type = $request->get('type');
        if(empty($profilePhoto) === false){
            $uploader = app()->make(FileUploader::class);
            $fileExt = $profilePhoto->getClientOriginalExtension();
            $fileName = $profilePhoto->getClientOriginalName();
            $destinationPath = sprintf('%s/%s-%s.%s', 'User', Auth::user()->id, md5(microtime(true)), $fileExt);
            if($uploader->upload($profilePhoto, $destinationPath)){
                $deleteExistingCover = $fileRepo->deleteAttachment('User', Auth::user()->id);
                if($attachment = $fileRepo->create([
                    'filename' => basename($destinationPath),
                    'type' => 'User',
                    'foreign_id' => Auth::user()->id,
                    'user_id' => Auth::user()->id,
                    'path' => $destinationPath
                ])){
                    $imgPath = getImagePath($attachment);
                    if(empty($imgPath) === false){
                        $thumb = 'userNavThumb';
                        if(empty($type) === false && $type == 'profilePage'){
                            $thumb = 'profileThumb';
                        }

                        $res['profileUrl'] = getImageUrl($attachment, $thumb, 'User');
                        $res['status'] = true;
                    }
                }
            }
        }

        return response()->json($res);
    }

    public function unFriend($id, Request $request, FriendRepository $friendRepo)
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $res = [];
        $res['status'] = false;
        if($friendRepo->unFriend($id)){
            $res['status'] = true;
        }

        return response()->json($res);
    }

    public function getUserUploadedPhotos(
        $username, Request $request, UserRepository $userRepo, AttachmentRepository $attachRepo
    )
    {
        $user = $userRepo->getUserDetails($username);

        if(empty($user->id) === true){
            throw new UserNotFound(trans('message.user_not_found'));            
        }

        $photos = $attachRepo->getAttachmentsByTypes(['Post', 'Comment', 'User', 'CoverPhoto'], $user->id);
        $layout = 'frontend.user.photos';
        $title = $user->name."'s ".trans('app.photos');

        return view('frontend.user.profile', compact('user', 'photos', 'layout', 'title'));
    }

    public function getProfileSettings(Request $request)
    {
        $user = Auth::user();

        return view('frontend.user.settings', compact('user'));
    }

    public function updateProfileSettings(
        Request $request, UserRepository $userRepo
    )
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'about' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(route('settings'))->withErrors($validator);
        }

        if($userRepo->updateUserDetails($request->all())){
            return redirect(route('settings'))->with('success', trans('message.success_update_profile'));
        }

        return redirect(route('settings'))->with('error', trans('message.error_update_profile'));
    }

    public function changePassword(Request $request, UserRepository $userRepo)
    {
        Validator::extend('oldPasswordCheck', function($attribute, $value, $parameters, $validator) {
            return Hash::check($value , $parameters[0]) ;
        });

        $hashedPassword = Auth::user()->password;
        $validator = Validator::make($request->all(), [
            'old_password'=> 'required|oldPasswordCheck:'.$hashedPassword,
            'new_password' => 'required|confirmed'
        ]);

        if($validator->fails()) {
            return redirect(route('settings'))->withErrors($validator);
        }

        if($userRepo->updatePassword($request->all())){
            return redirect(route('settings'))->with('success', trans('message.success_password_change'));
        }

        return redirect(route('settings'))->with('error', trans('message.error_password_change'));
    }

    public function searchFriends(Request $request, FriendRepository $friendRepo)
    {
        if($request->ajax() === false) return redirect(route('userHome'));

        $keyword = $request->get('q');
        $friends = $friendRepo->searchUserFriends($keyword, Auth::user()->id);
        return response()->json($friends);
    }

    public function getTaggedFriends(Request $request)
    {
        if($request->ajax() === false) return redirect(route('userHome'));
        
        $friends = $request->get('selectedFriends');

        return response()->json(['tagText' => formatTagsContext($friends)]);

    }
}
