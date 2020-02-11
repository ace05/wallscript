@extends('frontend.layouts.app')

@section('title')
    {{{ trans('app.registration') }}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="registration">
                    <h4>{{{ trans('app.register_to') }}} {{{ config('db.site_name') }}}</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="@if(config('db.facebook_login') == 'Yes' || config('db.linkedin_login') == 'Yes' || config('db.google_login') == 'Yes') col-md-6 @endif">
                            <div class="@if(config('db.facebook_login') == 'Yes' || config('db.linkedin_login') == 'Yes' || config('db.google_login') == 'Yes') social-register @endif">
                                @if(config('db.facebook_login') == 'Yes')
                                  <a href="{{{ route('socialRegister', ['provider' => 'facebook']) }}}" class="btn btn-lg btn-block btn-facebook"> 
                                      <i class="fa fa-facebook"></i> | {{{ trans('app.register_with_facebook') }}}
                                  </a>
                                @endif
                                @if(config('db.linkedin_login') == 'Yes')
                                    <a href="{{{ route('socialRegister', ['provider' => 'linkedin']) }}}" class="btn btn-lg btn-block btn-linkedin">
                                        <i class="fa fa-linkedin"></i> | {{{ trans('app.register_with_linkedin') }}}
                                    </a>
                                @endif
                                @if(config('db.google_login') == 'Yes')
                                    <a href="{{{ route('socialRegister', ['provider' => 'google']) }}}" class="btn btn-lg btn-block btn-google">
                                      <i class="fa fa-google"></i> | {{{ trans('app.register_with_google') }}}
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="@if(config('db.facebook_login') == 'Yes' || config('db.linkedin_login') == 'Yes' || config('db.google_login') == 'Yes') col-md-6  col-md-6 block-border-left @else col-md-12 @endif">
                            {!! Form::open(['url' => route('register')]) !!}
                                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                    {!! Form::label('name', trans('app.full_name')); !!}
                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{{ $errors->first('name') }}}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                                    {!! Form::label('username', trans('app.username')); !!}
                                    {!! Form::text('username', null, ['class' => 'form-control username']) !!}
                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{{ $errors->first('username') }}}</strong>
                                        </span>
                                    @endif
                                    <span class="help-block">
                                        {{{ url('/') }}}/<span class="ap-username"></span>
                                    </span>
                                </div>
                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    {!! Form::label('email', trans('app.email')); !!}
                                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    {!! Form::label('password', trans('app.password')); !!}
                                    {!! Form::password('password', ['class' => 'form-control']) !!}
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    {!! Form::label('password_confirmation', trans('app.password_confirmation')); !!}
                                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
                                    {!! Form::label('gender', trans('app.gender')); !!}
                                    {!! Form::select('gender', ['F' => trans('app.female'), 'M' => trans('app.male')], null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('gender'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('gender') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('terms') ? ' has-error' : '' }}">
                                    {!! Form::checkbox('terms', true, ['class' => 'form-control']) !!}
                                    {{{ trans('app.agree_terms') }}} 
                                    @if ($errors->has('terms'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('terms') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                

                                <div class="form-group">
                                    {!! Form::submit(trans('app.register'), ['class' => 'btn btn-success btn-block']); !!}
                                </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
