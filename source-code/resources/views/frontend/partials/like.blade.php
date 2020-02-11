@if($likes->count() > 0)
	<a href="{{{ route('getAllLikes', ['postId' => $postId]) }}}" data-toggle="modal" data-target="#likeModal" class="like-popup">
		{!! formatLikesContext($likes) !!}
	</a>
@endif