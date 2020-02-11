<div class="row">
	<div class="col-md-6">
		@if(Auth::user()->id != $user->id)
			{{--*/ $friend = $user->getFriendShipDetails($user->id) /*--}}
			@if(empty($friend) === false)
				@if($friend->friend_status == 1)
					<div> 
						@include('frontend.partials.post_share_form', ['slogan' => trans('app.message_user_slogan', ['username' => $user->name]), 'type' => 'profile'])
					</div>
				@endif
			@endif
		@endif
		<div class="panel panel-default panel-update friends-list">
	    		<div class="panel-heading">
	    			<h3>
	    				{{{ trans('app.about') }}}
	    			</h3>
	    		</div>
	    		<div class="panel-body">
	    			@if(empty($user->profile->about) === false)
	    				{!! formatText($user->profile->about) !!}
	    				<hr>
	    			@endif
	    			<dl>
					  <dt>{{{ trans('app.gender') }}}</dt>
					  <dd>
					  	@if(empty($user->profile->gender) === false)
					  		@if($user->profile->gender == 'M')
					  			{{{ trans('app.male') }}}
					  		@else
					  			{{{ trans('app.female') }}}
					  		@endif
					  	@endif
					  </dd>
					</dl>
	    		</div>
	    		
	    </div>
		{{--*/ $friendList = $user->getFriendShipDetails($user->id, 'limit') /*--}}
		@if($friendList->count() > 0)
	    	<div class="panel panel-default panel-update friends-list">
	    		<div class="panel-heading">
	    			<h3>
	    				<a href="{{{ route('userFriendsList', ['username' => $user->username]) }}}">{{{ trans('app.friends') }}}({{{ $user->profile->friends_count}}})</a>
	    			</h3>
	    		</div>
	    		<div class="panel-body">
	    			<ul class="list-inline">
	    				@foreach($friendList as $friend)
					  		<li>
					  			@if($friend->first_user_id != $user->id)
					  				<a href="{{{ route('userProfile', ['username' => $friend->fromUser->username]) }}}" data-toggle="tooltip" data-placement="top" data-original-title="{{{ $friend->fromUser->name }}}">
						  				<img class="img-responsive img-thumbnail" src="{{ url(getImageUrl($friend->fromUser->profilePicture, 'medium', 'User')) }}">
						  			</a>
					  			@else
					  				<a href="{{{ route('userProfile', ['username' => $friend->toUser->username]) }}}" data-toggle="tooltip" data-placement="top" data-original-title="{{{ $friend->toUser->name }}}">
						  				<img class="img-responsive img-thumbnail" src="{{ url(getImageUrl($friend->toUser->profilePicture, 'medium', 'User')) }}">
						  			</a>
					  			@endif									  			
					  		</li>
					  	@endforeach
					</ul>
	    		</div>
	    	</div>
	    @endif
	</div>
	<div class="col-md-6">
		<div class="updates">
			@include('frontend.partials.updates')
		</div>
	</div>
</div>