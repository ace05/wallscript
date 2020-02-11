<?php

namespace App\Http\Controllers;

use File;
use Auth;
use Event;
use Config;
use Validator;
use Socialite;
use App\Models\User;
use App\Events\EmailEvent;
use Illuminate\Http\Request;
use App\Http\Requests\Login;
use App\Http\Requests\Registration;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\FriendRepository;
use App\Repositories\UserAccountRepository;
use App\Repositories\UserProfileRepository;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function getLogin()
    {
        $label = trans('app.username');
        $loginType = config('db.login_type');
        if($loginType === 'Email'){
            $label = trans('app.email');
        }
        else if($loginType === 'Email or Username'){
            $label = trans('app.email_or_username');
        }

        return view('frontend.auth.login', ['label' => $label]);
    }

    public function getRegister()
    {
        return view('frontend.auth.register');
    }

    public function postLogin(Login $request, UserRepository $userRepo)
    {
        $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $loginType = config('db.login_type');
        if(strtolower($loginType) === 'email'){
            $field = 'email';
        }
        else if(strtolower($loginType) === 'username'){
            $field = 'username';
        }

        $request->merge([$field => $request->input('login')]);
        $remember = empty($request->remember) === false ? true : false;
        if (Auth::attempt($request->only($field, 'password'), $remember)){
            if(empty(Auth::user()->is_blocked) === false){
                return redirect(route('logout'))->with('error', trans('message.blocked_login'));
            }
            $updateLoginTime = $userRepo->updateLastLogin();
            return redirect(route('userHome'));
        }

        return redirect(route('login'))->with('error', trans('message.login_failed'));
    }

    public function postRegister(Registration $request, UserRepository $userRepo, FriendRepository $friendRepo)
    {
        $createUser = $userRepo->createUser($request->all());
        if(empty($createUser->id) === true){           
            return redirect()->route('register')->with('error', trans('message.error_user_creation'));;
        }else{
            if(config('db.email_verification') == 'Yes'){
                $data = [
                    'siteName' => config('db.site_name'),
                    'title' => trans('app.mail_subjects.activation_email', ['siteName' => config('db.site_name'),]),
                    'activationLink' => route('activation', [
                        'activationCode' => $createUser->email_verification_token
                    ]),
                    'emailLogo' => config('db.email_logo_url')
                ];
                $message = view('emails.activation', $data)->render();
                if(empty($message) === false){
                    Event::fire(new EmailEvent($createUser->email, $data['title'], $message));             
                }                
            }

            $updateFriend = $friendRepo->create([
                'first_user_id' => $createUser->id,
                'second_user_id' => $createUser->id,
                'friend_status' => 2
            ]);
        }

        if(config('db.email_verification') == 'Yes'){
            return redirect()->route('register')->with('success', trans('message.success_creation_with_email_verification'));
        }

        return redirect()->route('login')->with('success', trans('message.success_account_creation'));
    }

    public function activateAccount($activationCode, UserRepository $userRepo)
    {
        $user = $userRepo->updateActivation($activationCode);
        if(empty($user) === false){
            return redirect()->route('login')->with('success', trans('message.success_activation'));
        }

        return redirect()->route('login')->with('error', trans('message.failed_activation'));
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function socialRegistration($type)
    {
        $this->setSocialConfig($type);
        return Socialite::driver($type)->redirect();
    }

    public function processSocialRegistration(
        $type, Request $request, UserRepository $userRepo, 
        UserProfileRepository $profileRepo, UserAccountRepository $accountRepo
    )
    {
        $error = $request->get('error');
        if(empty($error) === false){
            return redirect()->route('login')->with('error', trans('message.failed_login'));
        }

        $this->setSocialConfig($type);
        $socialUser = Socialite::driver($type)->stateless()->user();
        if(empty($socialUser->id) === false && empty($socialUser->email) === false){
            $user = $userRepo->findOrCreateUser([
                'username' => randomUsername($socialUser->name),
                'email' => $socialUser->email,
                'password' => 'test123',
                'name' => $socialUser->name,
                'is_email_verified' => true,
                'id' => $socialUser->id,
                'gender' => empty($socialUser->user['gender']) === false ? $socialUser->user['gender'] : '',
                'social_data' => serialize($socialUser->user),
                'socialUser' => $socialUser
            ], $type);
            if(empty($user->id) === false){
                if(empty($user->is_blocked) === false){
                    return redirect()->route('login')->with('error', trans('message.blocked_login'));
                }
                if(Auth::loginUsingId($user->id)){
                    return redirect()->route('userHome');                    
                } 
            }
        }

        return redirect()->route('home')->with('error', trans('message.failed_login'));
    }

    protected function setSocialConfig($type)
    {
        switch ($type) {
            case 'facebook':
                config([
                    'services.facebook.client_id' => config('db.facebook_app_id'),
                    'services.facebook.client_secret' => config('db.facebook_app_secret'),
                    'services.facebook.redirect' => route('socialRegisterProcess', ['provider' => 'facebook']),
                ]);
                break;
            case 'linkedin':
                config([
                    'services.linkedin.client_id' => config('db.linkedin_client_id'),
                    'services.linkedin.client_secret' => config('db.linkedin_client_secret'),
                    'services.linkedin.redirect' => route('socialRegisterProcess', ['provider' => 'linkedin']),
                ]);
                break;
            case 'google':
                config([
                    'services.google.client_id' => config('db.google_client_id'),
                    'services.google.client_secret' => config('db.google_client_secret'),
                    'services.google.redirect' => route('socialRegisterProcess', ['provider' => 'google']),
                ]);
                break;
            default:
                break;
        }

        return;
    }
}
