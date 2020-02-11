<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>@section('title') {{{ trans('app.admin') }}} @show | {{{ config('db.site_name') }}}</title>
      <link rel="stylesheet" href="{{ url(elixir('css/admin.css')) }}" />
   </head>
   <body class="nav-md">
      <div class="container body">
         <div class="main_container">
            @include('admin.partials.navigation')
            <div class="right_col" role="main">
               @include('admin.partials.notifications')
               @yield('content')
            </div>
            <footer>
               <div class="pull-right">
               </div>
               <div class="clearfix"></div>
            </footer>
            <script src="{{ url(elixir('js/admin.js')) }}"></script>
         </div>
      </div>
   </body>
</html>