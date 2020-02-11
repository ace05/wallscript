@if($friendRequests->count() > 0)
	@foreach($friendRequests as $friendRequest)
		<li>
			<a href="{{{ route('userProfile', ['username' => $friendRequest->fromUser->username]) }}}">
              	<div class="media"> 
              		<div class="media-left"> 
              			<a href="{{{ route('userProfile', ['username' => $friendRequest->fromUser->username]) }}}"> <img src="{{ url(getImageUrl($friendRequest->fromUser->profilePicture, 'small', 'User')) }}" class="media-object"> </a> 
              		</div> 
              		<div class="media-body"> 
              			<h4 class="media-heading"><a href="{{{ route('userProfile', ['username' => $friendRequest->fromUser->username]) }}}">{{{ $friendRequest->fromUser->name }}}</a></h4> 
                        <i class="fa fa-clock-o"></i> {{{ timeAgo($friendRequest->created_at) }}}
              		</div> 
                    <div class="media-right">
                        <a href="{{{ route('confirmRequest', ['id' => $friendRequest->id]) }}}" class="btn btn-primary ajax-friend-request">
                            {{{ trans('app.confirm') }}}
                        </a>
                    </div>
              	</div>
			</a>
		</li>
	@endforeach
    @if(empty($type) === false)
        {!! $friendRequests->links() !!}
    @else
        @if($friendRequests->count() > 1)
            <li class="noti-all-action">
                <a class="text-center" href="{{{ route('allFriendRequests') }}}">
                    {{{ trans('app.view_all') }}}
                </a>
            </li>
        @endif
    @endif    
@else
    <li class="noti-all-action">
      <span class="text-center">{{{ trans('app.no_friend_request_found') }}}</span>
    </li>
@endif