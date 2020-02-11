<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@section('title') {{{ trans('app.welcome') }}} @show | {{{ config('db.site_name') }}}</title>
    @if(empty(config('db.favicon_url')) === false)
      <link rel="icon" href="{{{ config('db.favicon_url') }}}">
    @endif
    <link rel="stylesheet" href="{{ url(elixir('css/app.css')) }}" />
    <!--[if lt IE 9]>
       <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
       <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	</head>
  <body class="homepage">
    <div id="wrap">
       @include('frontend.partials.navigation')
      <div class="container" id="home">
		<div class="row">
			<div class="col-md-7">
				<h3 class="slogan">
					{{{ trans('app.home_slogan', ['siteName' => config('db.site_name')]) }}}
				</h3>
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                                <img src="{{ asset('/build/img/sc1.png') }}">
                            </div>
                            <div class="item">
                                <img src="{{ asset('/build/img/sc2.png') }}">
                            </div>
                            <div class="item">
                                <img src="{{ asset('/build/img/sc3.png') }}">
                            </div>
                            <div class="item">
                                <img src="{{ asset('/build/img/sc4.png') }}">
                            </div>
                            <div class="item">
                                <img src="{{ asset('/build/img/sc5.png') }}">
                            </div>
                            <div class="item">
                                <img src="{{ asset('/build/img/sc6.png') }}">
                            </div>
                            <div class="item">
                                <img src="{{ asset('/build/img/sc7.png') }}">
                            </div>
                              <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                              </a>
                              <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                              </a>
                      </div>
                </div>
			</div>
			<div class="col-md-5">		     
                @include('frontend.partials.home_register')
			</div>
		</div>
      </div>
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
    </footer>
    <script type="text/javascript">
      var isLoggedIn = @if(\Auth::user()) true @else false @endif;
          notificationUrl = '{{{ route("getNotificationCount") }}}';
    </script>
    <script src="{{ url(elixir('js/app.js')) }}"></script>
  </body>
</html>