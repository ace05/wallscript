<?php

namespace App\Http\Controllers\Admin;

use Cache;
use Illuminate\Http\Request;
use App\Repositories\CommentRepository;
use App\Http\Controllers\Admin\BaseAdminController;

class AdminCommentController extends BaseAdminController
{    
    public function getCommentList(Request $request, CommentRepository $commentRepo)
    {
        return view('admin.comments.list', [
            'comments' => $commentRepo->getCommentsList()
        ]);
    }

    public function deleteAdminComment(
        $id, $postId, $updateId, Request $request, CommentRepository $commentRepo
    )
    {
        if($commentRepo->deleteAdminComment($id, $postId, $updateId)){
            return redirect(route('getCommentsList'))->with('success', trans('message.comment_deleted_successfully'));
        }

        return redirect(route('getCommentsList'))->with('error', trans('message.comment_deleted_failed'));
        
    }
}
