@extends('frontend.layouts.app')

@section('title')
    {{{ trans('app.settings') }}}
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">	
			@if(empty($user) === false)
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-default panel-update friends-list">
				    		<div class="panel-heading">
				    			<h3>
				    				{{{ trans('app.profile_settings') }}}
				    			</h3>
				    			<hr>
				    		</div>
				    		<div class="panel-body">
				    			{!! Form::open(['url' => route('profileUpdate'), 'class' => 'form-horizontal']) !!}
				    				<div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
	                                    {!! Form::label('username', trans('app.username'),['class' => 'col-sm-2']); !!}
	                                    <div class="col-sm-10">
		                                    {!! Form::text('username', $user->username,['class' => 'form-control', 'readonly' => 'readonly']) !!}
		                                    @if ($errors->has('username'))
		                                        <span class="help-block">
		                                            <strong>{{ $errors->first('username') }}</strong>
		                                        </span>
		                                    @endif
		                                </div>
	                                </div>
	                                <div class="form-group">
	                                    {!! Form::label('email', trans('app.email'),['class' => 'col-sm-2']); !!}
	                                    <div class="col-sm-10">
	                                    	{{{ $user->email }}}
		                                </div>
	                                </div>
	                                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
	                                    {!! Form::label('name', trans('app.full_name'), ['class' => 'col-sm-2']); !!}
	                                    <div class="col-sm-10">
		                                    {!! Form::text('name', $user->name,['class' => 'form-control']) !!}
		                                    @if ($errors->has('name'))
		                                        <span class="help-block">
		                                            <strong>{{ $errors->first('name') }}</strong>
		                                        </span>
		                                    @endif
		                                </div>
	                                </div>
	                                <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
	                                    {!! Form::label('gender', trans('app.gender'), ['class' => 'col-sm-2']); !!}
	                                    <div class="col-sm-10">
		                                    {!! Form::select('gender', ['F' => trans('app.female'), 'M' => trans('app.male')], $user->profile->gender, ['class' => 'form-control']) !!}
		                                    @if ($errors->has('gender'))
		                                        <span class="help-block">
		                                            <strong>{{ $errors->first('gender') }}</strong>
		                                        </span>
		                                    @endif
		                                </div>
	                                </div>
	                                <div class="form-group {{ $errors->has('about') ? ' has-error' : '' }}">
	                                    {!! Form::label('about', trans('app.about'),['class' => 'col-sm-2']); !!}
	                                    <div class="col-sm-10">
	                                    	{!! Form::textarea('about', $user->profile->about, ['class' => 'form-control']) !!}
		                                    @if ($errors->has('about'))
		                                        <span class="help-block">
		                                            <strong>{{ $errors->first('about') }}</strong>
		                                        </span>
		                                    @endif
		                                </div>
	                                </div>
	                                <div class="form-group">
	                                	<div class="col-sm-offset-2 col-sm-10"> 
	                                    	{!! Form::submit(trans('app.update'), ['class' => 'btn btn-success']); !!}
	                                    </div>
	                                </div>
				    			{!! Form::close()!!}
				    		</div>
				    	</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-default panel-update friends-list">
				    		<div class="panel-heading">
				    			<h3>
				    				{{{ trans('app.change_password') }}}
				    			</h3>
				    			<hr>
				    		</div>
				    		<div class="panel-body">
				    			{!! Form::open(['url' => route('changePassword'), 'class' => 'form-horizontal']) !!}
				    				<div class="form-group {{ $errors->has('old_password') ? ' has-error' : '' }}">
	                                    {!! Form::label('old_password', trans('app.old_password'),['class' => 'col-sm-2']); !!}
	                                    <div class="col-sm-10">
		                                    {!! Form::password('old_password',['class' => 'form-control']) !!}
		                                    @if ($errors->has('old_password'))
		                                        <span class="help-block">
		                                            <strong>{{ $errors->first('old_password') }}</strong>
		                                        </span>
		                                    @endif
		                                </div>
	                                </div>
	                                <div class="form-group {{ $errors->has('new_password') ? ' has-error' : '' }}">
	                                    {!! Form::label('new_password', trans('app.new_password'),['class' => 'col-sm-2']); !!}
	                                    <div class="col-sm-10">
		                                    {!! Form::password('new_password',['class' => 'form-control']) !!}
		                                    @if ($errors->has('new_password'))
		                                        <span class="help-block">
		                                            <strong>{{ $errors->first('new_password') }}</strong>
		                                        </span>
		                                    @endif
		                                </div>
	                                </div>
	                                <div class="form-group {{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
	                                    {!! Form::label('new_password_confirmation', trans('app.new_password_confirmation'),['class' => 'col-sm-2']); !!}
	                                    <div class="col-sm-10">
		                                    {!! Form::password('new_password_confirmation',['class' => 'form-control']) !!}
		                                    @if ($errors->has('new_password_confirmation'))
		                                        <span class="help-block">
		                                            <strong>{{ $errors->first('new_password_confirmation') }}</strong>
		                                        </span>
		                                    @endif
		                                </div>
	                                </div>                                
	                                <div class="form-group">
	                                	<div class="col-sm-offset-2 col-sm-10"> 
	                                    	{!! Form::submit(trans('app.update'), ['class' => 'btn btn-success']); !!}
	                                    </div>
	                                </div>
	                                </div>
				    			{!! Form::close()!!}
				    		</div>
				    	</div>
					</div>
				</div>

			@endif
		</div>
	</div>
@endsection