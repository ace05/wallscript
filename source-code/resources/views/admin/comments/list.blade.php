@extends('admin.layouts.admin')
@section('title')
    {{{ trans('app.comments') }}}
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{{ trans('app.comments') }}}</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @if($comments->count() > 0)
            <table class="table table-striped projects">
              <thead>
                <tr>
                  <th style="width: 20%">{{{ trans('app.user') }}}</th>
                  <th>{{{ trans('app.comment') }}}</th>
                  <th>{{{ trans('app.created_on') }}}</th>
                  <th style="width: 20%">{{{ trans('app.actions') }}}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($comments as $comment)
                    <tr>
                        <td>
                            <div class="media" > 
                                <div class="media-left"> 
                                    <a target="_blank" href="{{{ route('userProfile', ['username' => $comment->user->username]) }}}">
                                        <img class="img-circle media-object " src="{{ url(getImageUrl($comment->user->profilePicture, 'medium', 'User')) }}" alt="{{{ $comment->user->name }}}" />
                                    </a>
                                </div> 
                                <div class="media-body"> 
                                    <div class="media-heading clearfix">
                                        <a target="_blank" href="{{{ route('userProfile', ['username' => $comment->user->username]) }}}">
                                            {{{ $comment->user->name }}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            {!! formatText($comment->comment) !!}
                        </td>
                        <td>
                            {{{ timeAgo($comment->created_at) }}}
                        </td>
                        <td>
                            <a href="{{{ route('deleteAdminComment', ['id' => $comment->id, 'postId' => $comment->post_id, 'updateId' => $comment->update_id]) }}}" class="btn btn-xs btn-danger ajax-delete-confirm">{{{ trans('app.delete') }}}</a>
                            <a target="_blank" href="{{{ route('viewUpdate', ['id' => $comment->update_id]) }}}" class="btn btn-xs btn-success">{{{ trans('app.view') }}}</a>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            {{{ $comments->links() }}}
        @else
            <div class="alert alert-warning">
                {{{ trans('message.no_comments_found') }}}
            </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection