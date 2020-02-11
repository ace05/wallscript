@if($notifications->count() > 0)
	@foreach($notifications as $notification)
		<li>
      @if($notification->type === 'confirmFriend')
        {{--*/ $url = route('userProfile', ['username' => $notification->fromUser->username]) /*--}}
      @else
        {{--*/ $url =  route('viewUpdate', ['id' => $notification->update_id])  /*--}}
      @endif
			<a href="{{{ $url }}}" class="@if(!$notification->is_read) unread @endif">
              	<div class="media"> 
              		<div class="media-left"> 
              			<a href="{{{ $url }}}"> <img src="{{ url(getImageUrl($notification->fromUser->profilePicture, 'small', 'User')) }}" class="media-object"> </a> 
              		</div> 
              		<div class="media-body"> 
              			<h4 class="media-heading"><a href="{{{ $url }}}">{{{ $notification->fromUser->name }}}</a></h4> 
              			<a href="{{{ $url }}}">{{{ getNotificationTextByType($notification->type) }}} - <i class="fa fa-clock-o"></i> {{{ timeAgo($notification->created_at) }}}</a>
              		</div> 
              	</div>
			</a>
		</li>
	@endforeach
    @if(empty($type) === false)
        {!! $notifications->links() !!}
    @else
        @if($notifications->count() > 4)
            <li class="noti-all-action"><a class="text-center" href="{{{ route('notifications') }}}">{{{ trans('app.view_all') }}}</a></li>
        @endif
    @endif
@else
    <li class="noti-all-action">
      <span class="text-center">{{{ trans('app.no_more_notifications') }}}</span>
    </li>
@endif