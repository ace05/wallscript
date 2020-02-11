<?php

namespace App\Http\Controllers\Admin;

use Cache;
use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Http\Controllers\Admin\BaseAdminController;

class AdminPostController extends BaseAdminController
{    
    public function getPostList(Request $request, PostRepository $postRepo)
    {
        return view('admin.posts.list', [
            'posts' => $postRepo->getPostList()
        ]);
    }

    public function deletePost($id, Request $request, PostRepository $postRepo)
    {
        if($postRepo->deletePost($id)){
            return redirect(route('getPostList'))->with('success', trans('message.post_deleted_successfully'));
        }

        return redirect(route('getPostList'))->with('error', trans('message.post_deleted_failed'));
        
    }
}
