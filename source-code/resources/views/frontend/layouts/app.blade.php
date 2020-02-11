<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      @if(empty(config('db.favicon_url')) === false)
        <link rel="icon" href="{{{ config('db.favicon_url') }}}">
      @endif
      <title>@section('title') {{{ trans('app.welcome') }}} @show | {{{ config('db.site_name') }}}</title>
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
      <link rel="stylesheet" href="{{ url(elixir('css/app.css')) }}" />
      <!--[if lt IE 9]>
         <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
         <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
   </head>
   <body>
      @include('frontend.partials.navigation')
      <div class="container">
         @include('frontend.partials.notifications')
         @yield('content')
      </div>
      <footer class="footer">
         <div class="container">
            <div class="panel-body">
              <ul class="list-inline">
                  <li>
                  © Copyright - <a href="http://www.wsnippets.com" target="_blank">Wsnippets.com</a> 
                  </li>
              </ul>
            </div>
         </div>
         @if(empty(config('db.google_map_api_key')) === false)
          <div class="google-map-api-enabled"></div>
         @endif
      </footer>
       <script type="text/javascript">
        var isLoggedIn = @if(\Auth::user()) true @else false @endif;
            notificationUrl = '{{{ route("getNotificationCount") }}}';
      </script>
      <script src="{{ url(elixir('js/app.js')) }}"></script>
      @if(empty(config('db.google_map_api_key')) === false)
         <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{{ config('db.google_map_api_key') }}}&libraries=places"></script>
      @endif
      @if(Auth::user())
         <div class="modal fade" id="likeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
             <div class="modal-dialog">
                 <div class="modal-content">
                 </div>
             </div>
         </div>

         <div class="modal img-modal" id="update-modal">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-body">
                   
                  </div>
                </div>
              </div>
            </div>
      @endif
   </body>
</html>

