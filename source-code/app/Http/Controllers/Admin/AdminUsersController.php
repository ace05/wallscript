<?php

namespace App\Http\Controllers\Admin;

use Cache;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Controllers\Admin\BaseAdminController;

class AdminUsersController extends BaseAdminController
{    
    public function getUsersList(Request $request, UserRepository $userRepo)
    {
        return view('admin.users.list', [
            'users' => $userRepo->getUserList()
        ]);
    }

    public function updateUserStatus($type,$id, Request $request, UserRepository $userRepo)
    {
        $updateStatusData = [];
        switch (strtolower($type)) {
            case 'block':
                $updateStatusData['is_blocked'] = 1;
                break;
            case 'unblock':
                $updateStatusData['is_blocked'] = 0;
                break;
            case 'emailverified':
                $updateStatusData['is_email_verified'] = 1;
                break;
            case 'emailunverified':
                $updateStatusData['is_email_verified'] = 0;
                break;
            case 'markadmin':
                $updateStatusData['is_admin'] = 1;
                break;
            case 'removeadmin':
                $updateStatusData['is_admin'] = 0;
                break;            
            default:
                break;
        }

        if(empty($updateStatusData) === false){
            $update = $userRepo->updateStatus($id, $updateStatusData);
        }

        return redirect(route('getUsersList'))->with('success', trans('message.status_updated_successfully'));
    }
}
