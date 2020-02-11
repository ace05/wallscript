@extends('frontend.layouts.app')

@section('title')
    {{{ trans('app.notifications') }}}
@endsection

@section('content')
	<div class="row">
		<div class="col-md-3 hidden-xs">
			@include('frontend.partials.user_nav_bar')
		</div>

		<div class="col-md-6">
			<div class="panel panel-default panel-update">
				<div class="panel-body">
					<ul class="list-unstyled">
						@include('frontend.partials.user_notifications', ['notifications' => $notifications, 'type' => 'all'])
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-3 visible-lg">
			@include('frontend.partials.user_right_nav_bar')
		</div>
	</div>
@endsection