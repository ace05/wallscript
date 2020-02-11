@if(empty($comment->user) === false && empty($comment->post) === false)
	<div class="media comment-{{{ $comment->id }}}" > 
		<div class="media-left"> 
			<a href="{{{ route('userProfile', ['username' => $comment->user->username]) }}}">
				<img class="img-circle media-object " src="{{ url(getImageUrl($comment->user->profilePicture, 'medium', 'User')) }}" alt="{{{ $comment->user->name }}}" />
			</a>
		</div> 
		<div class="media-body"> 
			<div class="media-heading clearfix">
				<a href="{{{ route('userProfile', ['username' => $comment->user->username]) }}}">
	        		{{{ $comment->user->name }}}
	        	</a>
	        	@if($comment->user_id == \Auth::user()->id)
	        		@if(empty($retrive) === true)
	        			<a href="{{{ route('deleteComment', ['commentId' => $comment->id, 'updateId' => $updateId, 'postId' => $comment->post->id, 'type' => $updateType])}}}" data-class="comment-{{{ $comment->id }}}" class="pull-right ajax-delete"><span class="glyphicon glyphicon-remove"></span></a>
	        		@else
	        			<a href="{{{ route('deleteComment', ['commentId' => $comment->id, 'updateId' => $comment->update_id, 'postId' => $comment->post->id, 'type' => $updateType])}}}" data-class="comment-{{{ $comment->id }}}" class="pull-right ajax-delete"><span class="glyphicon glyphicon-remove"></span></a>
	        		@endif	        		
	        	@endif
	        	<span>{{{ timeAgo($comment->created_at) }}}</span>
			</div>
			<div class="cmt-body"> 
				{!! formatText($comment->comment) !!}
				@if(empty($comment->image) === false)
					<img src="{{ url(getImageUrl($comment->image, 'commentImage', 'User')) }}" class="img-responsive">
				@endif
			</div>
		</div>
	</div>
@endif