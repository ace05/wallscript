@extends('frontend.layouts.app')

@section('title')
    {{{ trans('app.login') }}}
@endsection

@section('content')<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="registration">
                    <h4>{{{ trans('app.login_to') }}} {{{ config('db.site_name') }}}</h4>
                </div>
                <div class="panel-body">
                    <div class="row">

                        <div class="@if(config('db.facebook_login') == 'Yes' || config('db.linkedin_login') == 'Yes' || config('db.google_login') == 'Yes') col-md-6 @endif">
                            <div class="">
                                @if(config('db.facebook_login') == 'Yes')
                                  <a href="{{{ route('socialRegister', ['provider' => 'facebook']) }}}" class="btn btn-lg btn-block btn-facebook"> 
                                      <i class="fa fa-facebook"></i> | {{{ trans('app.login_with_facebook') }}}
                                  </a>
                                @endif
                                @if(config('db.linkedin_login') == 'Yes')
                                    <a href="{{{ route('socialRegister', ['provider' => 'linkedin']) }}}" class="btn btn-lg btn-block btn-linkedin">
                                        <i class="fa fa-linkedin"></i> | {{{ trans('app.login_with_linkedin') }}}
                                    </a>
                                @endif
                                @if(config('db.google_login') == 'Yes')
                                    <a href="{{{ route('socialRegister', ['provider' => 'google']) }}}" class="btn btn-lg btn-block btn-google">
                                      <i class="fa fa-google"></i> | {{{ trans('app.login_with_google') }}}
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="@if(config('db.facebook_login') == 'Yes' || config('db.linkedin_login') == 'Yes' || config('db.google_login') == 'Yes') col-md-6  col-md-6 block-border-left @else col-md-12 @endif">
                            {!! Form::open(['url' => route('login')]) !!}
                                <div class="form-group {{ $errors->has('login') ? ' has-error' : '' }}">
                                    {!! Form::label('login', $label); !!}
                                    {!! Form::text('login', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('login'))
                                        <span class="help-block">
                                            <strong>{{{ $errors->first('login') }}}</strong>
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
                                <div class="form-group">
                                    <div class="row">
                                        <div class="checkbox col-md-6">
                                            <label>
                                                <input type="checkbox" name="remember"> {{{ trans('app.remember_me') }}}
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{{ url('/password/email') }}}">{{{ trans('app.forgot_password') }}}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::submit(trans('app.login'), ['class' => 'btn btn-primary']); !!}
                                </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
