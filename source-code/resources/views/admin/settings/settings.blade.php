@extends('admin.layouts.admin')
@section('title')
    {{{ trans('app.settings') }}}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
            <h2>{{{ trans('app.'.$settingCategory->slug)}}}</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
            {!! Form::open(['url' => route('adminSettings', ['slug' => $settingCategory->slug]), 'class' => 'form-horizontal form-label-left']) !!}
                @if(empty($settingCategory->settings) === false)
                    @foreach($settingCategory->settings as $setting)
                        <div class="form-group">
                            {!! Form::label($setting->trans_key, trans('app.'.$setting->trans_key),['class' => 'col-sm-2 control-label col-md-3 col-sm-3 col-xs-12']); !!}
                            @if($setting->type === 'text')
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::text($setting->trans_key, $setting->value,['class' => 'form-control col-md-7 col-xs-12']) !!}
                                </div>
                            @elseif($setting->type === 'select')
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select($setting->trans_key, formAdminSelectOption($setting->inputs), $setting->value, ['class' => 'form-control col-md-7 col-xs-12']) !!}
                                </div>
                            @elseif($setting->type === 'textarea')
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::textarea($setting->trans_key, $setting->value, ['class' => 'form-control col-md-7 col-xs-12']) !!}
                                </div>
                            @endif
                            
                        </div>
                    @endforeach
                @endif
                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        {!! Form::submit(trans('app.update'), ['class' => 'btn btn-success']); !!}
                  </div>
                </div>
            {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection