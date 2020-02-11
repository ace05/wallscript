<div class="share-area">
	<div class="status-upload clearfix">
		{!! Form::open(['url' => route('updatePost'), 'class' => 'post-form', 'files' => true]) !!}
			{!! Form::textarea('message', null, ['placeholder' => $slogan, 'class' => 'post-message expanding']) !!}
			<div class="context-section">
				<span class="con-friends"></span>
				<span class="con-location"></span>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="imageSection"></div>
					@if(empty(config('db.google_map_api_key')) === false)
						<div class="placeSection extra-form hidden">
							<div class="input-group border-zero">
							  <span class="input-group-addon border-zero" id="basic-addon1">{{{ trans('app.at') }}}</span>
							  	{!! Form::text('place', null,['class' => 'form-control border-zero placeSearch', 'placeholder' => trans('app.with_place_slogan')]) !!}
							  {!! Form::hidden('lat', null, ['class' => 'lat']) !!}
							  {!! Form::hidden('lng', null, ['class' => 'lng']) !!}
							</div>
						</div>
					@endif
					<div class="friendsSection extra-form hidden">
						<div class="input-group border-zero ">
						  <span class="input-group-addon border-zero" id="basic-addon1">{{{ trans('app.with') }}}</span>
						  	{!! Form::select('friends[]', [], null, ['class' => 'form-control border-zero friends-autocomplete friendsection-width', 'multiple' => true, 'data-url' => route('searchFriends'), 'data-text-url' => route('friendTags')]) !!}
						</div>
					</div>
				</div>							
			</div>
			<div class="status-upload-footer clearfix">
				<div class="col-md-5 col-xs-7">
					<ul class="short-icon">
						<li>
							<a title="{{{ trans('app.tag_people_in_your_post') }}}" data-toggle="tooltip" data-class="friendsSection" class="update-type" data-placement="bottom" data-original-title="{{{ trans('app.tag_people_in_your_post') }}}"><i class="fa fa-user-plus"></i></a>
						</li>
						@if(empty(config('db.google_map_api_key')) === false)
							<li>
								<a title="{{{ trans('app.location') }}}" data-class="placeSection" data-toggle="tooltip" class="update-type" data-placement="bottom" data-original-title="{{{ trans('app.location') }}}"><i class="fa fa-map-marker"></i></a>
							</li>
						@endif																	
						<li>
							<a href='javascript:;' data-toggle="tooltip" data-placement="bottom" data-original-title="{{{ trans('app.photos') }}}"  class="file-upload-block">
								<i class="fa fa-picture-o"></i>
								{!! Form::file('images[]', ['class' => 'file-chooser', 'data-class'=>"imageSection", 'multiple' => 'multiple']) !!}
							</a>
						</li>
					</ul>
				</div>
				<div class="col-md-4 col-xs-5">
					@if($type == 'home')
						<select class="privacy-block selectpicker show-tick pull-right" name="privacy" data-width="120px">
						  	<option data-icon="fa fa-unlock" value="1">{{{ trans('app.privacy.public') }}}</option>
						  	<option data-icon="fa fa-users" value="2">{{{ trans('app.privacy.with_friends') }}}</option>
						</select>
					@endif
					{!! Form::hidden('type', $type) !!}
					@if($type === 'profile')
						{!! Form::hidden('to_user_id', $user->id) !!}
					@endif
				</div>
				<div class="col-md-3 @if($type === 'home') col-xs-12 @else col-xs-5 @endif">
					<div class="row">
						{!! Form::submit(trans('app.post'), ['type' => 'submit', 'class' => 'btn btn-primary green post-button']) !!}
					</div>
				</div>
			</div>
			
		{!! Form::close() !!}
	</div>
</div>