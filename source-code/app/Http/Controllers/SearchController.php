<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class SearchController extends Controller
{

    public function getSearchResults(Request $request, UserRepository $userRepo)
    {
        $query = $request->get('q');
        
        $users = $userRepo->getUsersByName($query);

        $res = [];
        if(empty($users) === false){
            foreach ($users as $key => $user) {
                $res[$key]['avatar'] = url(getImageUrl($user->profilePicture, 'likeThumb', 'User'));
                $res[$key]['url'] = url(route('userProfile', ['username' => $user->username]));
                $res[$key]['name'] = $user->name;
            }
        }

        return response()->json($res);
    }
}
