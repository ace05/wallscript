<div class="message-item" id="m{{{ $message->id }}}">
	<div class="message-inner">
		<div class="message-head clearfix">
			<div class="avatar pull-left">
				<a href="{{{ route('userProfile', ['username' => $message->user->username]) }}}"><img src="{{ url(getImageUrl($message->user->profilePicture, 'medium', 'User')) }}"></a>
			</div>
			<div class="user-detail">
				<h5 class="handle"><a href="{{{ route('userProfile', ['username' => $message->user->username]) }}}">{{{ $message->user->name }}}</a></h5>
				<div class="post-meta">
					<div class="asker-meta">
						<span class="qa-message-what"></span>
						<span class="qa-message-when">
							<span class="qa-message-when-data">{{{ 
							timeAgo($message->created_at) 
							}}}</span>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="qa-message-content">
			<p>{!! formatText($message->message) !!}</p>
			@if(empty($message->photo) === false)
				<div class="msg-img-section">
				<img src="{{ url(getImageUrl($message->photo, 'postSlider')) }}" class="img-responsive img-thumbnail">
				</div>
			@endif
			<div class="ext-section">
	        	{!! embedContent($message->external_data) !!}
	        </div>
		</div>
	</div>
</div>