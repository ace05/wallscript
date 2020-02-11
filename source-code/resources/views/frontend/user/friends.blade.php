@if($friends->count() > 0)
	<div class="row">
	{{--*/ $fcount = 1 /*--}}
	@foreach($friends as $friend)
		<div class="col-md-6">
			<div class="media friend-list-box friend-list-{{{ $friend->id }}}">
				<div class="media-left">
					@if($friend->first_user_id != $user->id)
		  				<a href="{{{ route('userProfile', ['username' => $friend->fromUser->username]) }}}" data-toggle="tooltip" data-placement="top" data-original-title="{{{ $friend->fromUser->name }}}">
			  				<img class="media-object" src="{{ url(getImageUrl($friend->fromUser->profilePicture, 'medium', 'User')) }}">
			  			</a>
		  			@else
		  				<a href="{{{ route('userProfile', ['username' => $friend->toUser->username]) }}}" data-toggle="tooltip" data-placement="top" data-original-title="{{{ $friend->toUser->name }}}">
			  				<img  class="media-object" src="{{ url(getImageUrl($friend->toUser->profilePicture, 'medium', 'User')) }}">
			  			</a>
		  			@endif
				</div> 
				<div class="media-body"> 
					<h4 class="media-heading">
						@if($friend->first_user_id != $user->id)
							<a href="{{{ route('userProfile', ['username' => $friend->fromUser->username]) }}}">
								{{{ $friend->fromUser->name }}}
							</a>
						@else
							<a href="{{{ route('userProfile', ['username' => $friend->toUser->username]) }}}">
								{{{ $friend->toUser->name }}}
							</a>
						@endif
					</h4>
				</div>
				@if($user->id == Auth::user()->id)
					<div class="media-right">
						<a href="{{{ route('unFriend', ['id' => $friend->id]) }}}" data-class="friend-list-{{{ $friend->id }}}" class="btn btn-danger ajax-unfriend">{{{ trans('app.unfriend') }}}</a>
					</div>
				@endif
			</div>
		</div>
		@if($fcount%2 == 0)
			</div>
			<div class="row">
		@endif
		{{--*/ $fcount = $fcount+1 /*--}}
	@endforeach
	</div>
	<div class="row">
		<div class="text-center">
			{!! $friends->links() !!}			
		</div>
	</div>
@else
	<div class="row">
		<div class="alert alert-warning">
			{{{ trans('message.no_friends_found') }}}
		</div>
	</div>
@endif
