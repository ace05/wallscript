@if(empty($retrive) === false)	
	@foreach($comments as $comment)
		@include('frontend.partials.comment', ['comment' => $comment, 'updateType' => $updateType, 'retrive' => 'all'])
	@endforeach
@else
	@foreach($comments as $comment)
		@include('frontend.partials.comment', ['comment' => $comment])
	@endforeach
@endif

