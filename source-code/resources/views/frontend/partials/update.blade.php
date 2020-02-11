@if(empty($update->upid) === false)
	{{--*/ $updateObj = $update /*--}}
@else
	{{--*/ $updateObj = getUpdateById($update->latestId) /*--}}
@endif
@if(empty($updateObj->user) === false && empty($updateObj->post) === false)
	<div class="panel panel-default panel-update updates-list post-{{{ $updateObj->post_id }}} update-{{{ $updateObj->id }}}">
		@if(in_array($updateObj->update_type, ['comment', 'like', 'share']))
			<div class="other-activity-block">
	    		<a href="{{{ route('userProfile', ['username' => $updateObj->user->username]) }}}">
	        		{{{ $updateObj->user->name }}}
	        	</a>
	        	{{{ getTypeContext($updateObj->update_type) }}}
	    	</div>
	    @endif
	    @if($updateObj->user_id == \Auth::user()->id || $updateObj->post->user_id == \Auth::user()->id)
		    <div class="dropdown">
		        <span class="dropdown-toggle" type="button" data-toggle="dropdown">
		            <span class="glyphicon glyphicon-chevron-down"></span>
		        </span>
		        <ul class="dropdown-menu" role="menu">
		        	@if(in_array($updateObj->update_type, ['comment', 'like', 'share']))
						{{--*/ $type = $updateObj->update_type /*--}}
					@else
						{{--*/ $type = 'post' /*--}}
					@endif

					@if($updateObj->post->user_id == \Auth::user()->id)
						{{--*/ $type = 'post' /*--}}
					@endif
		            <li role="presentation">
		            	<a role="menuitem" tabindex="-1" data-id="post-{{{ $updateObj->post_id }}}" data-class="update-{{{ $updateObj->id }}}" href="{{{ route('deletePost', ['updateId' => $updateObj->id, 'postId' => $updateObj->post_id, 'type' => $type]) }}}" class="ajax-delete"><span class="glyphicon glyphicon-trash"></span> {{{ trans('app.delete') }}}</a>
		            </li>
		        </ul>
		    </div>
		@endif
	    <div class="panel-heading">
	    	@if(in_array($updateObj->update_type, ['comment', 'share', 'like']))
	    		<img class="img-circle" src="{{ url(getImageUrl($updateObj->post->user->profilePicture, 'medium', 'User')) }}" alt="{{{ $updateObj->post->user->name }}}" />
	    	@else
	        	<img class="img-circle" src="{{ url(getImageUrl($updateObj->user->profilePicture, 'medium', 'User')) }}" alt="{{{ $updateObj->user->name }}}" />
	        @endif
	        <h3>
	        	@if(in_array($updateObj->update_type, ['comment', 'share', 'like']))
		        	<a href="{{{ route('userProfile', ['username' => $updateObj->post->user->username]) }}}">
		        		{{{ $updateObj->post->user->name }}}
		        	</a>		        	
		        @else
		        	<a href="{{{ route('userProfile', ['username' => $updateObj->user->username]) }}}">
		        		{{{ $updateObj->user->name }}}
		        	</a>
		        @endif
		        @if(empty($updateObj->post->wall_user_id) === false)
		        	<span class="glyphicon glyphicon-play"></span>
	        		<a href="{{{ route('userProfile', ['username' => $updateObj->post->toUser->username]) }}}">
	        			{{{ $updateObj->post->toUser->name }}}
	        		</a>		        	
	        	@endif

	        	@if(empty($updateObj->post->place) === false)
		        	<span class="add-place-section">
		        		{{{ trans('app.at') }}} <i class="fa fa-map-marker"></i> <a href="javascript:;" data-toggle="popover" data-placement="right" data-title="{{{ $updateObj->post->place }}}" data-content="&lt;img src='https://maps.googleapis.com/maps/api/staticmap?center={{{ $updateObj->post->lat }}},{{{ $updateObj->post->lng }}}&zoom=14&size=250x250&key={{{ config('db.google_map_api_key') }}}&markers=color:red%7Clabel:G%7C{{{ $updateObj->post->lat }}},{{{ $updateObj->post->lng }}}' /&gt;"> {{{ $updateObj->post->place }}}</a>
		        	</span>
		        @endif

		        @if($updateObj->post->getTaggedFriends->count() > 0)
		        	<span class="add-friends-tags">
		        		{{{ trans('app.with') }}} {!! formatFriendTagsContext($updateObj->post->getTaggedFriends) !!}
		        	</span>
		        @endif
		        <div class="time-section">
			        <span class="time-ago">
		        		<a href="{{{ route('viewUpdate', ['id' => $updateObj->id]) }}}"> {{{ timeAgo($updateObj->created_at) }}}</a>
		        	</span> -        	 
		        	@if($updateObj->privacy == 1)
		        		<a href="javascript:;" class="privacy-icon" data-toggle="tooltip" data-original-title="{{{ trans('app.shared_publicly') }}}"><span class="fa fa-unlock" ></span></a>
		        	@elseif($updateObj->privacy == 2)
		        		<a href="javascript:;" class="privacy-icon" data-toggle="tooltip" data-original-title="{{{ trans('app.shared_with_friends') }}}"><span class="fa fa-users"></span></a>
		        	@else
		        		<a href="javascript:;" class="privacy-icon" data-toggle="tooltip" data-original-title="{{{ trans('app.shared_privately') }}}"><span class="fa fa-lock" ></span></a>
		        	@endif
		        </div>
	        </h3>
	    </div>
	    <div class="panel-body">
	        <p class="message-blck">{!! formatText($updateObj->post->message) !!}</p>
	    </div>
	    <div class="ext-data-section">
	    	 @if(empty($updateObj->post->external_data) === true && $updateObj->post->images->count()  === 0 && empty($updateObj->post->place) === false)
	    	 	<div class="map-section">
	        		<img src="https://maps.googleapis.com/maps/api/staticmap?center={{{ $updateObj->post->lat }}},{{{ $updateObj->post->lng }}}&zoom=14&size=600x350&key={{{ config('db.google_map_api_key') }}}&markers=color:red%7Clabel:G%7C{{{ $updateObj->post->lat }}},{{{ $updateObj->post->lng }}}" class="img-responsive">
	        	</div>
	        @endif    
	    	<div class="ext-section">
	        	{!! embedContent($updateObj->post->external_data) !!}
	        </div>
	        <div class="images-section">
		        @if($updateObj->post->images->count() > 0)
		        	@if($updateObj->post->images->count() == 1)
		        		@foreach($updateObj->post->images as $image)
		        			<a data-toggle="modal" data-target="#update-modal" href="{{{ route('viewUpdate', ['id' => $updateObj->id]) }}}" class="img-modal">
		        				<img src="{{ url(getImageUrl($image, 'postSlider')) }}" class="img-responsive">
		        			</a>
		        		@endforeach
		        	@else
		        		<div class="gallery clearfix">
		        			@if($updateObj->post->images->count() >= 3)
		        				{{--*/ $ppos = 1 /*--}}
								@foreach($updateObj->post->images as $image)
									{{--*/ $thumbnail = 'postSlider' /*--}}
									{{--*/ $ratio = '' /*--}}
									@if($ppos > 1 && $ppos <= 3)
										{{--*/ $thumbnail = 'postGallery' /*--}}
										{{--*/ $ratio = 'aspect' /*--}}
									@endif
									@if($updateObj->post->images->count() >= 3)
										@if($ppos === 3)
											@if(($updateObj->post->images->count() - 3) > 0)
												<span class="hovereffect ">
													<a href="{{{ route('viewUpdate', ['id' => $updateObj->id]) }}}" data-toggle="modal" data-target="#update-modal" class="img-modal">
													   <img src="{{ url(getImageUrl($image, $thumbnail, $ratio)) }}" class="img-responsive" >
													    <span class="overlay">
													       <h2>+{{{ $updateObj->post->images->count() - 3 }}}</h2>
													    </span>
													</a>
												</span>
											@else
												<a href="{{{ route('viewUpdate', ['id' => $updateObj->id]) }}}" data-toggle="modal" data-target="#update-modal" class="img-modal">
													<img src="{{ url(getImageUrl($image, $thumbnail, $ratio)) }}" class="img-responsive" >
												</a>
											@endif
										@elseif($ppos < 3)
											<a href="{{{ route('viewUpdate', ['id' => $updateObj->id]) }}}" data-toggle="modal" data-target="#update-modal" class="img-modal">
			        							<img src="{{ url(getImageUrl($image, $thumbnail, $ratio)) }}" class="img-responsive" >
			        						</a>
			        					@endif
			        				@endif
			        				{{--*/ $ppos = $ppos + 1 /*--}}
			        			@endforeach
							@else
								@foreach($updateObj->post->images as $image)
									<a href="{{{ route('viewUpdate', ['id' => $updateObj->id]) }}}" data-toggle="modal" data-target="#update-modal" class="img-modal">
										<img src="{{ url(getImageUrl($image, 'postGallery', 'aspect')) }}" class="img-responsive" >
									</a>
								@endforeach
							@endif
		        			
		        		</div>
				    @endif
			    @endif
		    </div>
	    </div>
	    <div class="panel-body">
	    	<div class="comment-section-actions">
    			<a href="{{{ route('addLike', ['postId' => $updateObj->post_id, 'type' => 'like', 'updateId' => $updateObj->id]) }}}" data-id="like-sec-{{{ $updateObj->id }}}" data-toggle="popover" data-content='{{{ getLikeEmotions($updateObj->post_id, $updateObj->id) }}}' class="btn btn-sm btn-default like-popover like-types like-sec-{{{ $updateObj->id }}}" >
    				{!! getLikeText($updateObj) !!}
		        </a>
		        @if($updateObj->post->user_id != \Auth::user()->id)
			        <a href="{{{ route('shareUpdate', ['postId' => $updateObj->post_id]) }}}" class="btn btn-sm btn-default ajax-share">
			            <span class="glyphicon glyphicon-share-alt"></span>{{{ trans('app.share') }}}
			        </a>
			    @endif
		        <a href="javascript:;" data-id="comment-form-{{{ $updateObj->id }}}" class="btn btn-sm btn-default comment-add">
		            <i class="fa fa-comment-o"></i>{{{ trans('app.comment') }}}
		        </a>
		        @if($updateObj->post->comments->count() > 2)
			        <a href="{{{ route('getAllcomments', ['postId' => $updateObj->post_id]) }}}" data-id="cmt-sec-{{{ $updateObj->id }}}" class="btn btn-default btn-sm ajax-all-comments">
			            {{{ trans('app.view_all_comments', ['count' => $updateObj->post->comments->count()]) }}}
			        </a>
			    @endif
		    </div>
	    </div>
	    <div class="panel-footer">
	    	<div class="like-section" id="like-sec-{{{ $updateObj->id }}}">
		    	@if($updateObj->post->likes->count() > 0)
		    		{!! likeUrl($updateObj->post->likes, $updateObj->post_id) !!}
				@else
					<a href="{{{ route('getAllLikes', ['postId' => $updateObj->post_id])}}}" data-toggle="modal" data-target="#likeModal" class="like-popup"></a>
				@endif
			</div>	
        	@if($updateObj->post->comments->count() > 0)
        		<div class="comments-section cmt-sec-{{{ $updateObj->id }}}">
        			@include('frontend.partials.comments', ['comments' => $updateObj->post->getLastTwoComments, 'updateId' => $updateObj->id, 'updateType' => $updateObj->update_type])
        		</div>
        	@else
        		<div class="cmt-sec-{{{ $updateObj->id }}}">
        		</div>
        	@endif       
	    </div>
	    <div class="panel-update-comment" id="comment-form-{{{ $updateObj->id }}}">
	        <img class="img-circle" src="{{ url(getImageUrl(Auth::user()->profilePicture, 'medium', 'User')) }}" alt="{{{ Auth::user()->name }}}" />
	        <div class="panel-update-textarea">
	        	{!! Form::open(['url' => route('addComment'), 'class' => 'cmt-form']) !!}
	        		{!! Form::textarea('comment', null, ['class' => 'comment-area']) !!}
	        		<div class="cmt-image-preview-{{{ $updateObj->id }}}" id="cmt-img-preview"></div>
					<a href='javascript:;' data-class="cmt-image-preview-{{{ $updateObj->id }}}" class="file-upload-block btn btn-default">
						<i class="fa fa-camera"></i>
						{!! Form::file('commentImage', ['class' => 'file-chooser', 'data-class' => 'cmt-image-preview-'.$updateObj->id]) !!}
					</a>
					{!! Form::hidden('update_id', $updateObj->id) !!}
					{!! Form::hidden('post_id', $updateObj->post_id) !!}
					{!! Form::button(trans('app.post_comment'), ['class' => 'btn btn-success cmt-button btn-sm', 'type' => 'submit']) !!}
					{!! Form::button(trans('app.cancel'), ['class' => 'btn btn-default btn-sm comment-area-reset', 'type' => 'reset']) !!}
		        {!! Form::close() !!}
	        </div>
	        <div class="clearfix"></div>
	    </div>
	</div>
@endif