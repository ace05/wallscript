@extends('admin.layouts.admin')
@section('title')
    {{{ trans('app.users') }}}
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{{ trans('app.users') }}}</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @if($users->count() > 0)
            <table class="table table-striped projects">
              <thead>
                <tr>
                  <th style="width: 20%">{{{ trans('app.user') }}}</th>
                  <th>{{{ trans('app.last_login') }}}</th>
                  <th>{{{ trans('app.blocked_status') }}}</th>
                  <th>{{{ trans('app.email_verified') }}}</th>
                  <th>{{{ trans('app.admin') }}}</th>
                  <th style="width: 30%">{{{ trans('app.actions') }}}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="media" > 
                                <div class="media-left"> 
                                    <a target="_blank" href="{{{ route('userProfile', ['username' => $user->username]) }}}">
                                        <img class="img-circle media-object " src="{{ url(getImageUrl($user->profilePicture, 'medium', 'User')) }}" alt="{{{ $user->name }}}" />
                                    </a>
                                </div> 
                                <div class="media-body"> 
                                    <div class="media-heading clearfix">
                                        <a target="_blank" href="{{{ route('userProfile', ['username' => $user->username]) }}}">
                                            {{{ $user->name }}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{{ timeAgo($user->last_login) }}}
                        </td>
                        <td>
                            @if(empty($user->is_blocked) === false)
                                <span class="label label-success">{{{ trans('app.Yes') }}}</span>
                            @else
                                <span class="label label-danger">{{{ trans('app.No') }}}</span>
                            @endif
                        </td>
                        <td>
                            @if(empty($user->is_email_verified) === false)
                                <span class="label label-success">{{{ trans('app.Yes') }}}</span>
                            @else
                                <span class="label label-danger">{{{ trans('app.No') }}}</span>
                            @endif
                        </td>
                        <td>
                            @if(empty($user->is_admin) === false)
                                <span class="label label-success">{{{ trans('app.Yes') }}}</span>
                            @else
                                <span class="label label-danger">{{{ trans('app.No') }}}</span>
                            @endif
                        </td>
                        <td>
                            @if(empty($user->is_blocked) === false)
                                <a href="{{{ route('updateUserStatus', ['id' => $user->id, 'type' => 'unblock']) }}}" class="btn btn-xs btn-success">{{{ trans('app.unblock') }}}</a>
                            @else
                                <a href="{{{ route('updateUserStatus', ['id' => $user->id, 'type' => 'block']) }}}" class="btn btn-xs btn-danger">{{{ trans('app.block') }}}</a>
                            @endif
                            @if(empty($user->is_email_verified) === false)
                                <a href="{{{ route('updateUserStatus', ['id' => $user->id, 'type' => 'emailverified']) }}}" class="btn btn-xs btn-danger">{{{ trans('app.mark_email_unverified') }}}</a>
                            @else
                                <a href="{{{ route('updateUserStatus', ['id' => $user->id, 'type' => 'emailunverified']) }}}" class="btn btn-xs btn-success">{{{ trans('app.mark_email_verified') }}}</a>
                            @endif
                            @if(empty($user->is_admin) === false)
                                <a href="{{{ route('updateUserStatus', ['id' => $user->id, 'type' => 'removeadmin']) }}}" class="btn btn-xs btn-danger">{{{ trans('app.remove_as_admin') }}}</a>
                            @else
                                <a href="{{{ route('updateUserStatus', ['id' => $user->id, 'type' => 'markadmin']) }}}" class="btn btn-xs btn-success">{{{ trans('app.mark_as_admin') }}}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            {{{ $users->links() }}}
        @else
            <div class="alert alert-warning">
                {{{ trans('message.no_users_found') }}}
            </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection