<?php

namespace App\Http\Controllers\Admin;

use Cache;
use Illuminate\Http\Request;
use App\Repositories\UpdateRepository;
use App\Http\Controllers\Admin\BaseAdminController;

class AdminUpdatesController extends BaseAdminController
{    
    public function getUpdatesList(Request $request, UpdateRepository $updateRepo)
    {
        return view('admin.updates.list', [
            'updates' => $updateRepo->getUpdatesList()
        ]);
    }

    public function deleteAdminUpdate(
        $id, $postId, $type, Request $request, UpdateRepository $updateRepo
    )
    {
        $delete = false;
        switch ($type) {
            case 'comment':
                $delete = $updateRepo->deleteUpdateAsComment($id, $postId);
                break;

            case 'like':
                $delete = $updateRepo->deleteUpdateAsLike($id, $postId);
                break;

            case 'share':
                $delete = $updateRepo->deleteUpdateAsShare($id, $postId);
                break;

            case 'post':
                $delete = $updateRepo->deleteUpdateAsPost($id, $postId);
                break;
            
            default:
                break;
        }
        if($delete){
            return redirect(route('getUpdatesList'))->with('success', trans('message.post_deleted_successfully'));
        }

        return redirect(route('getUpdatesList'))->with('error', trans('message.post_deleted_failed'));
        
    }
}
