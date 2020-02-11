
@if($likes->count() > 0)
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         <h4 class="modal-title">{{{ trans('app.likes') }}}</h4>
    </div>
    <div class="modal-body">
		@foreach($likes as $like)
			<div class="media">
			   	<div class="media-left">
		        	<a href="{{{ route('userProfile', ['username' => $like->user->username]) }}}"><img src="{{ url(getImageUrl($like->user->profilePicture, 'medium', 'User')) }}" alt="{{{ $like->user->name }}}" class="media-object img-circle"  /></a>
				</div>
			   <div class="media-body">
			      <h4 class="media-heading"><a href="{{{ route('userProfile', ['username' => $like->user->username]) }}}">{{{ $like->user->name }}}</a></h4>
			      <span>{!! getLikeTextByType($like->type) !!} - {{{ timeAgo($like->created_at) }}}</span>
			   </div>
			   	@if($like->user->id != \Auth::user()->id)
			   		{{--*/ $friend = $like->user->getFriendShipDetails($like->user->id) /*--}}
				   	<div class="media-right">
					   	@if(empty($friend->first_user_id) == false)
					   		@if($friend->friend_status != 1)
						   		@if($friend->first_user_id == Auth::user()->id && $friend->friend_status == 0)
						   			<a href="" class="btn btn-default ajax-friend-request">
						   				{{{ trans('app.friend_request_sent') }}}
						   			</a>
						   		@else
						   			<a href="{{{ route('confirmRequest', ['id' => $friend->id]) }}}" class="btn btn-primary ajax-friend-request">
						   				{{{ trans('app.confirm_request') }}}
						   			</a>
						   		@endif
						   	@else
						   		<a href="javascript:;" class="btn btn-default ajax-friend-request">
					   				{{{ trans('app.friends') }}}
					   			</a>
						   	@endif
					   	@else
					   		<a href="{{{ route('addFriend', ['id' => $like->user->id]) }}}" class="btn btn-default ajax-friend-request">
				   				{{{ trans('app.add_as_friend') }}}
				   			</a>
					   	@endif
				   	</div>
				@endif
			</div>
		@endforeach
	</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{{ trans('app.close') }}}</button>
    </div>
@endif

