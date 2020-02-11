<nav class="navbar navbar-custom navbar-fixed-top">
   <div class="container">
      <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
         <span class="sr-only">Toggle navigation</span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         </button>
          @if(Auth::user())
            <a class="dropdown-toggle navbar-profile visible-xs"  data-toggle="dropdown" role="button" href="#">
              <img src="{{ url(getImageUrl(Auth::user()->profilePicture, 'small', 'User')) }}" class="profile-image img-circle pull-left">
            </a>
            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="{{{ route('userProfile', ['username' => Auth::user()->username]) }}}">
                  <i class="glyphicon glyphicon-user"></i>
                  {{{ trans('app.profile')}}}
                </a>
              </li>
               <li><a href="{{{ route('settings') }}}"><i class="fa fa-cog"></i> {{{ trans('app.settings') }}}</a></li>
               <li><a href="{{{ route('settings') }}}"><i class="fa fa-key"></i> {{{ trans('app.change_password') }}}</a></li>
                <li>
                  <a href="{{{ route('conversation', ['username' => 'all']) }}}"><i class="glyphicon glyphicon-envelope"></i> {{{ trans('app.messages') }}}</a>
                </li>
                @if(empty(Auth::user()->is_admin) === false)
                    <li class="divider"></li>
                    <li>
                      <a href="{{{ route('dashboard') }}}"><i class="glyphicon glyphicon-dashboard"></i> {{{ trans('app.admin_dashboard') }}}</a>
                    </li>
                @endif
               <li class="divider"></li>
               <li><a href="{{{ route('logout') }}}"><i class="fa fa-sign-out"></i>{{{ trans('app.logout') }}}</a></li>
            </ul>
          @endif
          <a class="navbar-brand" href="{{{ url(route('home')) }}}">
              @if(empty(config('db.logo_url')) === false)
                <img src="{{{ config('db.logo_url') }}}" alt="">
              @else
                {{{ config('db.site_name') }}}
              @endif
          </a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
          @if(Auth::user())
              {!! Form::open(['url' => route('searchPeople'), 'class' => 'navbar-form navbar-left people-search-form'])!!}
                  <div class="input-group navbar-input-group">
                     {!! Form::text('q', null, ['class' => 'form-control navbar-form-input people-search-autocomplete', 'placeholder' => trans('app.search'), 'data-url' => route('searchPeople'), 'autocomplete' => 'off']) !!}
                     <span class="input-group-addon">
                         <button type="submit">
                             <span class="glyphicon glyphicon-search"></span>
                         </button>  
                     </span>
                  </div>
              {!! Form::close() !!}
          @endif
         <ul class="nav navbar-nav navbar-right">

            @if(Auth::user())
                <li>
                  <a href="{{{ route('userHome') }}}">
                     <span class="glyphicon glyphicon-home"></span>
                     <span class="visible-xs pos-media-txt">{{{ trans('app.home') }}}</span>
                  </a>
                </li>
                <li class="notifications dropdown" id="notification">
                  <a class="dropdown-toggle" data-url="{{{ route('getUserNotificationList') }}}" data-toggle="dropdown" role="button" href="{{{ route('userHome') }}}">
                     <span class="glyphicon glyphicon-globe"></span>
                     <span class="visible-xs pos-media-txt">{{{ trans('app.notifications') }}}</span>
                     <span class="badge notification-badge"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-notification" role="menu">
                  </ul>
                </li>
                <li class="dropdown notifications" id="friend-requests">
                  <a class="dropdown-toggle" data-url="{{{ route('getFriendRequestList') }}}" data-toggle="dropdown" role="button" href="{{{ route('userHome') }}}">
                     <span class="glyphicon glyphicon-user"></span>
                     <span class="visible-xs pos-media-txt">{{{ trans('app.friend_request') }}}</span>
                     <span class="badge friend-request-badge"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-notification" role="menu">
                  </ul>
                </li>
                <li class="dropdown notifications" id="messages">
                  <a class="dropdown-toggle" data-url="{{{ route('getConversationList') }}}" data-toggle="dropdown" role="button" href="{{{ route('userHome') }}}">
                     <span class="fa fa-envelope"></span>
                     <span class="visible-xs pos-media-txt">{{{ trans('app.messages') }}}</span>
                     <span class="badge messages-badge"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-notification" role="menu">
                  </ul>
                </li>                
                <li class="dropdown hidden-xs">
                   <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ url(getImageUrl(Auth::user()->profilePicture, 'small', 'User')) }}" class="profile-image img-circle">
                    {{{ Auth::user()->username }}}
                    <b class="caret"></b>
                   </a>
                   <ul class="dropdown-menu">
                      <li>
                        <a href="{{{ route('userProfile', ['username' => Auth::user()->username]) }}}">
                          <i class="glyphicon glyphicon-user"></i>
                          {{{ trans('app.profile')}}}
                        </a>
                      </li>
                       <li><a href="{{{ route('settings') }}}"><i class="fa fa-cog"></i> {{{ trans('app.settings') }}}</a></li>
                       <li><a href="{{{ route('settings') }}}"><i class="fa fa-key"></i> {{{ trans('app.change_password') }}}</a></li>
                        <li>
                          <a href="{{{ route('conversation', ['username' => 'all']) }}}"><i class="glyphicon glyphicon-envelope"></i> {{{ trans('app.messages') }}}</a>
                        </li>
                        @if(empty(Auth::user()->is_admin) === false)
                            <li class="divider"></li>
                            <li>
                              <a href="{{{ route('dashboard') }}}"><i class="glyphicon glyphicon-dashboard"></i> {{{ trans('app.admin_dashboard') }}}</a>
                            </li>
                        @endif
                       <li class="divider"></li>
                       <li><a href="{{{ route('logout') }}}"><i class="fa fa-sign-out"></i>{{{ trans('app.logout') }}}</a></li>
                   </ul>
               </li>
            @else
               <li>
                  {!! link_to_route('register', trans('app.register')) !!}
               </li>
               <li>
                  {!! link_to_route('login', trans('app.login')) !!}
               </li>
            @endif
         </ul>
      </div>
   </div>
</nav>