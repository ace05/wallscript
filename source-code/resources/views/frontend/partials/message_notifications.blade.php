@if(empty($messages) === false)
	@foreach($messages as $message)
		<li>
			<a href="{{{ route('conversation', ['username' => $message['lastMessage']->user->username]) }}}">
              	<div class="media"> 
              		<div class="media-left"> 
              			<a href="{{{ route('conversation', ['username' => $message['lastMessage']->user->username]) }}}"> <img src="{{ url(getImageUrl($message['lastMessage']->user->profilePicture, 'small', 'User')) }}" class="media-object"> </a> 
              		</div> 
              		<div class="media-body"> 
              			<h4 class="media-heading"><a href="{{{ route('conversation', ['username' => $message['lastMessage']->user->username]) }}}">{{{ $message['lastMessage']->user->name }}}</a></h4> 
                        <a href="{{{ route('conversation', ['username' => $message['lastMessage']->user->username]) }}}">{{{ limitText($message['lastMessage']->message, 40) }}}</a>
                        <div class="message-time">
                            <a href="{{{ route('conversation', ['username' => $message['lastMessage']->user->username]) }}}"><i class="fa fa-clock-o"></i> {{{ timeAgo($message['lastMessage']->created_at) }}}</a>                          
                        </div>                        
              		</div>
                    <div class="media-right">
                        <a href="{{{ route('markRead', ['conversationId' => $message['conversation']->id]) }}}" data-toggle="tooltip" data-original-title="{{{ trans('app.mark_as_read') }}}" class="btn btn-primary ajax-markread">
                            <i class="fa fa-check-square "></i>
                        </a>
                    </div>
              	</div>
			</a>
		</li>
	@endforeach
      <li class="noti-all-action">
          <a class="text-center" href="{{{ route('conversation', ['username' => 'all']) }}}">
              {{{ trans('app.view_all') }}}
          </a>
      </li>
@else
    <li class="noti-all-action">
      <span class="text-center">{{{ trans('message.no_messages_found') }}}</span>
    </li>
@endif