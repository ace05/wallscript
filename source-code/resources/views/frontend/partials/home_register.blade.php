{!! Form::open(['url' => route('register'), 'class' => 'form']) !!}
    <div class="row">
        <div class="col-xs-7 col-md-7">
            <h3 class="create-account">{{{ trans('app.signup') }}}</h3>
        </div>
        <div class="col-xs-5 col-md-5">
            @if(config('db.facebook_login') == 'Yes')
              <a href="{{{ route('socialRegister', ['provider' => 'facebook']) }}}" class="btn btn-facebook"> 
                  <i class="fa fa-facebook"></i>
              </a>
            @endif
            @if(config('db.linkedin_login') == 'Yes')
                <a href="{{{ route('socialRegister', ['provider' => 'linkedin']) }}}" class="btn btn-linkedin">
                    <i class="fa fa-linkedin"></i>
                </a>
            @endif
            @if(config('db.google_login') == 'Yes')
                <a href="{{{ route('socialRegister', ['provider' => 'google']) }}}" class="btn btn-google">
                  <i class="fa fa-google"></i>
                </a>
            @endif
        </div>
    </div>

     <hr>
    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::text('name', null, ['class' => 'form-control input-lg', 'placeholder' => trans('app.full_name')]) !!}
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{{ $errors->first('name') }}}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
        {!! Form::text('username', null, ['class' => 'form-control input-lg', 'placeholder' => trans('app.username')]) !!}
        @if ($errors->has('username'))
            <span class="help-block">
                <strong>{{{ $errors->first('username') }}}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
        {!! Form::text('email', null, ['class' => 'form-control input-lg', 'placeholder' => trans('app.email')]) !!}
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
        {!! Form::password('password', ['class' => 'form-control input-lg', 'placeholder' => trans('app.password')]) !!}
        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
        {!! Form::password('password_confirmation', ['class' => 'form-control input-lg', 'placeholder' => trans('app.password_confirmation')]) !!}
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
        {!! Form::submit(trans('app.register'), ['class' => 'btn btn-lg btn-primary btn-block signup-btn']); !!}
    </div>
{!! Form::close() !!}