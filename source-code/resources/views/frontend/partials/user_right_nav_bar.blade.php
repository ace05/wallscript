@if(empty(config('db.sidebar_right_banner')) === false)
	<div class="panel panel-default panel-update">
		<div class="panel-heading">
			<h3>
				{{{ trans('app.advertisement') }}}
			</h3>
		</div>
		<div class="panel-body">
			{!! config('db.sidebar_right_banner') !!}
		</div>		
	</div>
@endif
{{--*/ $friendList = Auth::user()->getFriendShipDetails(Auth::user()->id, 'limit') /*--}}
	@if($friendList->count() > 0)
		<div class="panel panel-default panel-update mg-top20">
			<div class="panel-heading">
				<h3>
					<a href="{{{ route('userFriendsList', ['username' => Auth::user()->username]) }}}">{{{ trans('app.friends') }}}({{{ Auth::user()->profile->friends_count}}})</a>
				</h3>
			</div>
			<div class="panel-body">
				<ul class="list-inline user-friend-lists">
					@foreach($friendList as $friend)
				  		<li>
				  			@if($friend->first_user_id != Auth::user()->id)
				  				<a href="{{{ route('userProfile', ['username' => $friend->fromUser->username]) }}}" data-toggle="tooltip" data-placement="top" data-original-title="{{{ $friend->fromUser->name }}}">
					  				<img class="img-responsive img-thumbnail" src="{{ url(getImageUrl($friend->fromUser->profilePicture, 'small', 'User')) }}">
					  			</a>
				  			@else
				  				<a href="{{{ route('userProfile', ['username' => $friend->toUser->username]) }}}" data-toggle="tooltip" data-placement="top" data-original-title="{{{ $friend->toUser->name }}}">
					  				<img class="img-responsive img-thumbnail" src="{{ url(getImageUrl($friend->toUser->profilePicture, 'small', 'User')) }}">
					  			</a>
				  			@endif									  			
				  		</li>
				  	@endforeach
				</ul>
			</div>
		</div>
	@endif
	
	{{--*/ $userLists = Auth::user()->getRecentUsers() /*--}}
	@if($userLists->count() > 0)
		<div class="panel panel-default panel-update usernav-friends-list">
			<div class="panel-heading">
				<h3>{{{ trans('app.recent_users') }}}</h3>
			</div>
			<div class="panel-body">
				@foreach($userLists as $userList)
					<div class="media friend-list-box user-{{{ $userList->id }}}">
						<div class="media-left">
			  				<a href="{{{ route('userProfile', ['username' => $userList->username]) }}}" data-toggle="tooltip" data-placement="top" data-original-title="{{{ $userList->name }}}">
				  				<img  class="media-object" src="{{ url(getImageUrl($userList->profilePicture, 'small', 'User')) }}">
				  			</a>
						</div> 
						<div class="media-body"> 
							<h4 class="media-heading">
								<a href="{{{ route('userProfile', ['username' => $userList->username]) }}}">
									{{{ $userList->name }}}
								</a>
							</h4>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	@endif