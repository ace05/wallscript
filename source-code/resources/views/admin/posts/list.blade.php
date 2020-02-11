@extends('admin.layouts.admin')
@section('title')
    {{{ trans('app.posts') }}}
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{{ trans('app.posts') }}}</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @if($posts->count() > 0)
            <table class="table table-striped projects">
              <thead>
                <tr>
                  <th style="width: 20%">{{{ trans('app.user') }}}</th>
                  <th>{{{ trans('app.message') }}}</th>
                  <th>{{{ trans('app.created_on') }}}</th>
                  <th>{{{ trans('app.comments_count') }}}</th>
                  <th>{{{ trans('app.shares_count') }}}</th>
                  <th>{{{ trans('app.likes_count') }}}</th>
                  <th style="width: 20%">{{{ trans('app.actions') }}}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>
                            <div class="media" > 
                                <div class="media-left"> 
                                    <a target="_blank" href="{{{ route('userProfile', ['username' => $post->user->username]) }}}">
                                        <img class="img-circle media-object " src="{{ url(getImageUrl($post->user->profilePicture, 'medium', 'User')) }}" alt="{{{ $post->user->name }}}" />
                                    </a>
                                </div> 
                                <div class="media-body"> 
                                    <div class="media-heading clearfix">
                                        <a target="_blank" href="{{{ route('userProfile', ['username' => $post->user->username]) }}}">
                                            {{{ $post->user->name }}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            {!! formatText($post->message) !!}
                        </td>
                        <td>
                            {{{ timeAgo($post->updated_at) }}}
                        </td>
                        <td>
                            {{{ $post->comments_count }}}
                        </td>
                        <td>
                            {{{ $post->share_count }}}
                        </td>
                        <td>
                            {{{ $post->likes_count }}}
                        </td>
                        <td>
                            <a href="{{{ route('deleteAdminPost', ['id' => $post->id]) }}}" class="btn btn-xs btn-danger ajax-delete-confirm">{{{ trans('app.delete') }}}</a>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            {{{ $posts->links() }}}
        @else
            <div class="alert alert-warning">
                {{{ trans('message.no_posts_found') }}}
            </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection