@extends('frontend.layouts.app')

@section('title')
    {{{ $title }}}
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">	
			@if(empty($user) === false)	
				<div class="profile-header-wrapper">
					@if(Auth::user()->id == $user->id)
				   		{!! Form::open(['url' => route('coverPhotoUpload'), 'class' => 'cover-photo-form', 'files' => true]) !!}
							<a href='javascript:;' data-toggle="tooltip" data-placement="right" data-original-title="{{{ trans('app.upload_cover_photo') }}}" class="cover-uploader">
								<i class="glyphicon glyphicon-camera"></i>
								{!! Form::file('coverPhoto', ['class' => 'custom-file-chooser', 'id' => 'cover-photo','accept'=> "image/*"]) !!}
							</a>
						{!! Form::close() !!}
					@endif
					@if(empty($user->coverPhoto) === false)
						<div class="cover-wrapper">
							<img src="{{ url(getCoverUrl($user->coverPhoto, 'coverThumb')) }}" class="img-cover" />
						</div>
					@else
						<div class="cover-wrapper">
							<img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iOTAwIiBoZWlnaHQ9IjUwMCIgdmlld0JveD0iMCAwIDkwMCA1MDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzkwMHg1MDAvYXV0by8jNTU1OiM1NTUKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNTZiMGYxZWEzYiB0ZXh0IHsgZmlsbDojNTU1O2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjQ1cHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE1NmIwZjFlYTNiIj48cmVjdCB3aWR0aD0iOTAwIiBoZWlnaHQ9IjUwMCIgZmlsbD0iIzU1NSIvPjxnPjx0ZXh0IHg9IjMzNC41IiB5PSIyNzAuNCI+OTAweDUwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg==" height="276" class="img-cover">
						</div>
					@endif
				    <div class="cover-resize-wrapper">
				    </div>
				    <div class="clearfix">
					    <div class="avatar-wrapper">
					    	@if(Auth::user()->id == $user->id)
						    	{!! Form::open(['url' => route('profilePhotoUpload'), 'class' => 'profile-photo-form', 'files' => true]) !!}
									<a href='javascript:;' class="profile-photo-uploader">
										<i class="glyphicon glyphicon-camera"></i>
										{!! Form::file('profilePhoto', ['class' => 'custom-file-chooser', 'id' => 'profile-photo', 'accept'=> "image/*"]) !!}
										{!! Form::hidden('type', 'profilePage') !!}
									</a>
								{!! Form::close() !!}
							@endif
					         <img class="img-responsive" src="{{ url(getImageUrl($user->profilePicture, 'profileThumb', 'User')) }}" id="profilePic">
					    </div>
					    <div class="profile-name-wrapper">
					    	<a href="{{{ route('userProfile', ['username' => $user->username]) }}}">{{{ ucwords($user->name) }}}</a>
					    </div>
				    </div>
				    @if(Auth::user()->id == $user->id)
					    <div class="cover-photo-action-block">
					    	{!! Form::open(['url' => route('updateCoverPhoto'), 'class' => 'cover-update-form']) !!}
								{!! Form::hidden('positions', null, ['class' => 'selectedPosition']) !!}
								{!! Form::submit(trans('app.save'), ['type' => 'submit', 'class' => 'btn btn-primary green save-button']) !!}
								<a href="javascript:;" class="cover-cancel btn btn-default">{{{ trans('app.cancel') }}}</a>
							{!! Form::close() !!}
					    </div>
					@endif
					<div class="profile-statistics-wrapper clearfix">
						<ul class="list-inline pull-left">
			                <li class="@if(Route::currentRouteName() ==='userProfile') active @endif">
			                	<a href="{{{ route('userProfile', ['username' => $user->username]) }}}">{{{ trans('app.timeline') }}}</a>
			                </li>
			                <li class="@if(Route::currentRouteName() ==='userFriendsList') active @endif">
			                	<a href="{{{ route('userFriendsList', ['username' => $user->username]) }}}">			                		<span class="badge">{{{ $user->profile->friends_count}}}</span>{{{ trans('app.friends') }}}
			                	</a>
			                </li>
			                <li class="@if(Route::currentRouteName() ==='userPhotos') active @endif">
			                	<a href="{{{ route('userPhotos', ['username' => $user->username]) }}}">{{{ trans('app.photos') }}}</a>
			                </li>
			            </ul>

						<div class="profile-btn-actions pull-right">
			                
			                @if($user->id != \Auth::user()->id)
						   		{{--*/ $friend = $user->getFriendShipDetails($user->id) /*--}}
							   	<div class="media-right">
								   	@if(empty($friend->first_user_id) == false)
								   		@if($friend->friend_status != 1)
									   		@if($friend->first_user_id == Auth::user()->id && $friend->friend_status == 0)
									   			<a href="" class="btn btn-default ajax-friend-request">
									   				<span class="glyphicon glyphicon-ok"></span>
									   				{{{ trans('app.friend_request_sent') }}}
									   			</a>
									   		@else
									   			<a href="{{{ route('confirmRequest', ['id' => $friend->id]) }}}" class="btn btn-primary ajax-friend-request">
									   				<span class="glyphicon glyphicon-ok"></span> {{{ trans('app.confirm_request') }}}
									   			</a>
									   		@endif
									   	@else
									   		<a href="{{{ route('conversation', ['username' => $user->username]) }}}" class="btn btn-success"><span class="glyphicon glyphicon-envelope"></span> {{{ trans('app.message') }}}</a>
									   	@endif
								   	@else
								   		<a href="{{{ route('addFriend', ['id' => $user->id]) }}}" class="btn btn-default ajax-friend-request">
							   				<span class="glyphicon glyphicon-plus"></span> {{{ trans('app.add_as_friend') }}}
							   			</a>
								   	@endif
							   	</div>
							@endif
			            </div>
						
			        </div>
				    </div>
				    <div class="profile-layout">
				    	@include($layout)
				    </div>
				</div>
			@else

			@endif
		</div>
	</div>
@endsection