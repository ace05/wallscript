@if($photos->count() > 0)
	<div class="row">
	{{--*/ $pcount = 1 /*--}}
	@foreach($photos as $photo)
		<div class="col-md-3">
			<div class="image-set">
	            <a class="example-image-link" href="{{ url(getImageUrl($photo, 'postSlider')) }}" data-lightbox="example-set">
	            	<img class="example-image img-responsive" src="{{ url(getImageUrl($photo, 'timelineThumb')) }}">
	            </a>
            </div>
		</div>
		@if($pcount%4 == 0)
			</div>
			<div class="row">
		@endif
		{{--*/ $pcount = $pcount+1 /*--}}
	@endforeach
	</div>
	<div class="row">
		<div class="text-center">
			{!! $photos->links() !!}			
		</div>
	</div>
@else
	<div class="row">
		<div class="alert alert-warning">
			{{{ trans('app.no_photos_found') }}}
		</div>
	</div>
@endif
