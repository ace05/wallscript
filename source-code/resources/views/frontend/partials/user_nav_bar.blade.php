<div class="side-bar">
	<div class="profile-sidebar">
		<!-- SIDEBAR USERPIC -->
		<div class="profile-userpic">
	   		{!! Form::open(['url' => route('profilePhotoUpload'), 'class' => 'profile-photo-form', 'files' => true]) !!}
				<a href='javascript:;' data-toggle="tooltip" data-placement="right" data-original-title="{{{ trans('app.upload_profile_picture') }}}" class="profile-photo-uploader">
					<i class="glyphicon glyphicon-camera"></i>
					{!! Form::file('profilePhoto', ['class' => 'custom-file-chooser', 'id' => 'profile-photo']) !!}
				</a>
			{!! Form::close() !!}
			<a href="{{{ route('userProfile', ['username' => Auth::user()->username]) }}}">
				<img src="{{ url(getImageUrl(Auth::user()->profilePicture, 'userNavThumb', 'User')) }}" class="img-responsive" id="profilePic" />			
			</a>
		</div>
		<div class="profile-usertitle">
			<div class="profile-usertitle-name">
				<a href="{{{ route('userProfile', ['username' => Auth::user()->username]) }}}">
					{{{ Auth::user()->name }}}
				</a>
			</div>
		</div>
		<div class="profile-usermenu">
			<ul class="nav">
				<li>
					<a href="{{{ route('userProfile', ['username' => Auth::user()->username]) }}}">
						<i class="glyphicon glyphicon-user"></i>
						{{{ trans('app.profile')}}}
					</a>
				</li>
				<li>
					<a href="{{{ route('settings') }}}"><i class="fa fa-cog"></i> {{{ trans('app.settings') }}}
					</a>
				</li>
	            <li>
	            	<a href="{{{ route('settings') }}}"><i class="fa fa-key"></i> {{{ trans('app.change_password') }}}</a>
	            </li>
	            <li>
	            	<a href="{{{ route('conversation', ['username' => 'all']) }}}"><i class="glyphicon glyphicon-envelope"></i> {{{ trans('app.messages') }}}</a>
	            </li>
	            
			</ul>
		</div>
	</div>
	@if(empty(config('db.sidebar_left_banner')) === false)
		<div class="panel panel-default panel-update mg-top20">
			<div class="panel-heading">
				<h3>
					{{{ trans('app.advertisement') }}}
				</h3>
			</div>
			<div class="panel-body">
				{!! config('db.sidebar_left_banner') !!}
			</div>
		</div>
	@endif
</div>