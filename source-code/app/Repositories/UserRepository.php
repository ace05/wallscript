<?php

namespace App\Repositories;

use Auth;
use Carbon\Carbon;
use App\Entities\User;
use App\Entities\Friend;
use App\Entities\Attachment;
use App\Entities\UserAccount;
use App\Entities\UserProfile;
use App\Libraries\FileUploader;

/**
 * Class UserRepository
 * @package namespace App\Repositories;
 */
class UserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function createUser(array $data, $source = 'site')
    {
        $user = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'name' => $data['name'],
            'source' => $source,
            'last_login' => Carbon::now()
        ];

        if(empty($data['is_email_verified']) === false) $user['is_email_verified'] = true;
        if(empty($data['social_data']) === false) $user['social_data'] = $data['social_data'];

        if($source === 'site'){
            if(config('db.email_verification') == 'Yes'){
                $user['email_verification_token'] = md5(rand().microtime());
                $user['email_token_created'] = Carbon::now();
            }            
        }

        $userObj = $this->model->create($user);
        if($source === 'site'){
            $userProfile = UserProfile::create([
                'user_id' => $userObj->id,
                'gender' => empty($data['gender']) === false ? ((strtolower($data['gender']) === 'male') ? 'M' : 'F') : 'M' 
            ]);
        }

        return $userObj;
    }

    public function updateLastLogin()
    {
        return $this->model->where('id', '=', Auth::user()->id)
                    ->update([
                        'last_login' => Carbon::now()
                    ]);
    }

    public function updateCoverPosition(array $data)
    {
        return $this->model->where('id', '=', Auth::user()->id)
                    ->update([
                        'cover_photo_position' => $data['positions']
                    ]);
    }

    public function findOrCreateUser(array $data, $source = 'site')
    {
        $user = $this->model->where('email', '=', $data['email'])
                            ->first();
        if(empty($user->id) === true){
            $user = $this->createUser($data, $source);
            if(empty($user->id) === false){
                $userProfile = UserProfile::create([
                    'user_id' => $user->id,
                    'gender' => empty($data['gender']) === false ? ((strtolower($data['gender']) === 'male') ? 'M' : 'F') : 'M' 
                ]);
                $userFriend = Friend::create([
                    'first_user_id' => $user->id,
                    'second_user_id' => $user->id,
                    'friend_status' => 2
                ]);
                if($source != 'site'){
                    if($filePath = $this->saveProfileImage($user->id, $source, $data['socialUser'])){
                        $updateProfileImage = Attachment::create([
                            'filename' => basename($filePath),
                            'type' => 'User',
                            'foreign_id' => $user->id,
                            'user_id' => $user->id,
                            'path' => $filePath
                        ]);                                          
                    } 
                }
            }            
        }
        
        $update = $this->model->where('id', '=', $user->id)
                        ->update([
                            'last_login' => Carbon::now()
                        ]);

        if($source != 'site'){
            $account = UserAccount::where('user_id', '=', $user->id)
                                ->where('account_type', '=', $source)
                                ->first();
            if(empty($account->id) === true){
                $userAccount = UserAccount::create([
                    'user_id' => $user->id,
                    'account_id' => $data['id'],
                    'account_type' => $source
                ]);             
            }                  
        }

        return $user;
    }

    public function updateActivation($activationCode)
    {
        $user = $this->model->where('email_verification_token', '=', $activationCode)
                    ->first();
        if(empty($user->id) === false){
            return $this->model->where('id', '=', $user->id)
                    ->update([
                        'email_verification_token' => null,
                        'email_token_created' => null,
                        'is_email_verified' => true
                    ]);
        }

        return false;
    }

    protected function saveProfileImage($userId, $type, $socialUser)
    {
        $imageUrl = '';
        switch ($type) {
            case 'facebook':
                $imageUrl = empty($socialUser->avatar_original) === false ? $socialUser->avatar_original : '';
                break;
            case 'linkedin':
                $imageUrl = empty($socialUser->avatar_original) === false ? $socialUser->avatar_original : ''; 
                break;
            case 'google':
                $imageUrl = empty($socialUser->avatar) === false ? strtok($socialUser->avatar, '?').'?sz=500' : '';
                break;
            default:
                break;
        }

        if(empty($imageUrl) === false){
            $uploader = app()->make(FileUploader::class);
            $response = file_get_contents($imageUrl);
            $destinationPath = sprintf('User/%s-%s.jpg', $userId, md5(microtime(true)));
            if($uploader->save($response, $destinationPath) === true){
                return $destinationPath;
            }
        }

        return false;
    }

    public function getUserDetails($username)
    {
        return $this->model->where('username', '=', $username)
                    ->where('is_active','=', 1)
                    ->where('is_blocked', '=', 0)
                    ->firstOrFail();
    }

    public function getUsersByName($query)
    {
        $users = $this->model->where('is_active','=', 1)
                    ->where('is_blocked', '=', 0);

        if(empty($query) === false){
            $users = $users->where('name', 'like', '%'.$query.'%');
        }

        return $users->limit(10)->get();
    }

    
    public function updateUserDetails(array $data)
    {
        $user = $this->model->where('id', '=', Auth::user()->id)
                    ->update([
                        'name' => $data['name']
                    ]);
        if(empty($user) === false){
            return UserProfile::where('user_id', '=', Auth::user()->id)
                    ->update([
                        'gender' => $data['gender'],
                        'about' => $data['about']
                    ]);
        }

        return false;
    }

    public function updatePassword(array $data)
    {
        return $this->model->where('id', '=', Auth::user()->id)
                    ->update([
                        'password' => bcrypt($data['new_password'])
                    ]);
    }

    public function getUserList()
    {
        return $this->model->orderBy('created_at', 'desc')->paginate(20);
    }

    public function updateStatus($id, $data)
    {
        return $this->model->where('id', '=', $id)
                    ->update($data);
    }

    public function getFriendStatus($userId)
    {
        return Friend::select('id', 'first_user_id', 'second_user_id', 'friend_status')
            ->where(function($query) use ($userId){
                    $query->where('first_user_id', '=', $userId)
                        ->orWhere('second_user_id', '=', $userId);
                })->where(function($query){
                    $query->where('first_user_id', '=', Auth::user()->id)
                        ->orWhere('second_user_id', '=', Auth::user()->id);
                })->first();
    }
}
