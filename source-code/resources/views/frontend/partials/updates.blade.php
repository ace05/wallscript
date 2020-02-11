@if($updates->count() > 0)
	@foreach($updates as $update)
		@include('frontend.partials.update', ['update' => $update])
	@endforeach

	<div class="pagination hidden">
		{{{ $updates->render() }}}
	</div>
@else
	<div class="alert alert-warning no-updates">{{{ trans('message.no_updates_found') }}}</div>
@endif
<div class="loader hidden text-center">
	<div class="btn btn-default">
		<i class="fa fa-refresh fa-spin fa-3x fa-fw" aria-hidden="true"></i>
	</div>
</div>