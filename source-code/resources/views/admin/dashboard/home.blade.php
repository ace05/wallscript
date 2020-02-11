@extends('admin.layouts.admin')

@section('content')
<div class="row top_tiles">
  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="tile-stats">
      <div class="icon"><i class="fa fa-user"></i></div>
      <div class="count">{{{ $userCount }}}</div>
      <h3>{{{ trans('app.users') }}}</h3>
    </div>
  </div>
  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="tile-stats">
      <div class="icon"><i class="fa fa-comments-o"></i></div>
      <div class="count">{{{ $commentCount }}}</div>
      <h3>{{{ trans('app.comments') }}}</h3>
    </div>
  </div>
  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="tile-stats">
      <div class="icon"><i class="fa fa-envelope"></i></div>
      <div class="count">{{{ $messageCount }}}</div>
      <h3>{{{ trans('app.messages') }}}</h3>
    </div>
  </div>
  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="tile-stats">
      <div class="icon"><i class="fa fa-user-plus"></i></div>
      <div class="count">{{{ $friendCount }}}</div>
      <h3>{{{ trans('app.friendships') }}}</h3>
    </div>
  </div>
</div>

<div class="row top_tiles">
  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="tile-stats">
      <div class="icon"><i class="fa fa-pencil-square-o"></i></div>
      <div class="count">{{{ $postCount }}}</div>
      <h3>{{{ trans('app.posts') }}}</h3>
    </div>
  </div>
  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="tile-stats">
      <div class="icon"><i class="fa fa-check-circle-o"></i></div>
      <div class="count">{{{ $updateCount }}}</div>
      <h3>{{{ trans('app.updates') }}}</h3>
    </div>
  </div>
  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="tile-stats">
      <div class="icon"><i class="fa fa-picture-o"></i></div>
      <div class="count">{{{ $attachmentCount }}}</div>
      <h3>{{{ trans('app.photos') }}}</h3>
    </div>
  </div>
  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="tile-stats">
      <div class="icon"><i class="fa fa-comments"></i></div>
      <div class="count">{{{ $conversationCount }}}</div>
      <h3>{{{ trans('app.conversations') }}}</h3>
    </div>
  </div>
</div>
@endsection
