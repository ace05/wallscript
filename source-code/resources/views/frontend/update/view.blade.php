@extends('frontend.layouts.app')

@section('title')
    {{{ trans('app.update') }}}
@endsection

@section('content')
	<div class="row">
		<div class="col-md-3 visible-lg">
			@include('frontend.partials.user_nav_bar')
		</div>
		<div class="col-md-6">
			<div class="updates">
				@include('frontend.partials.update', ['update' => $update])
			</div>
		</div>
		<div class="col-md-3 visible-lg">
			@include('frontend.partials.user_right_nav_bar')
		</div>
	</div>
@endsection