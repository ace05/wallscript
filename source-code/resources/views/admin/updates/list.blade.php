@extends('admin.layouts.admin')
@section('title')
    {{{ trans('app.updates') }}}
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{{ trans('app.updates') }}}</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @if($updates->count() > 0)
            <table class="table table-striped projects">
              <thead>
                <tr>
                  <th style="width: 20%">{{{ trans('app.user') }}}</th>
                  <th>{{{ trans('app.message') }}}</th>
                  <th>{{{ trans('app.created_on') }}}</th>
                  <th>{{{ trans('app.update_type') }}}</th>
                  <th style="width: 30%">{{{ trans('app.actions') }}}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($updates as $update)
                    <tr>
                        <td>
                            <div class="media" > 
                                <div class="media-left"> 
                                    <a target="_blank" href="{{{ route('userProfile', ['username' => $update->user->username]) }}}">
                                        <img class="img-circle media-object " src="{{ url(getImageUrl($update->user->profilePicture, 'medium', 'User')) }}" alt="{{{ $update->user->name }}}" />
                                    </a>
                                </div> 
                                <div class="media-body"> 
                                    <div class="media-heading clearfix">
                                        <a target="_blank" href="{{{ route('userProfile', ['username' => $update->user->username]) }}}">
                                            {{{ $update->user->name }}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            {!! formatText($update->post->message) !!}
                        </td>
                        <td>
                            {{{ timeAgo($update->updated_at) }}}
                        </td>
                        <td>
                            {{{ $update->update_type }}}
                        </td>
                        <td>
                            <a href="{{{ route('deleteAdminUpdate', ['id' => $update->id, 'postId' => $update->post_id, 'type' => $update->update_type]) }}}" class="btn btn-xs btn-danger ajax-delete-confirm">{{{ trans('app.delete') }}}</a>
                            <a target="_blank" href="{{{ route('viewUpdate', ['id' => $update->id]) }}}" class="btn btn-xs btn-success">{{{ trans('app.view') }}}</a>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            {{{ $updates->links() }}}
        @else
            <div class="alert alert-warning">
                {{{ trans('message.no_updates_found') }}}
            </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection