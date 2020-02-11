@extends('frontend.layouts.app')

@section('title')
    {{{ trans('app.home') }}}
@endsection

@section('content')
	<div class="row">
		<div class="col-md-3 visible-lg">
			@include('frontend.partials.user_nav_bar')
		</div>

		<div class="col-md-6">
			@include('frontend.partials.post_share_form', ['slogan' => trans('app.message_slogan'), 'type' => 'home'])
			<div class="updates">
				@include('frontend.partials.updates')
			</div>
		</div>
		<div class="col-md-3 visible-lg">
			@include('frontend.partials.user_right_nav_bar')
		</div>
	</div>
@endsection