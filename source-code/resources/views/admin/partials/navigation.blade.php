<div class="col-md-3 left_col menu_fixed">
   <div class="left_col scroll-view">
      <div class="navbar nav_title">
         <a href="{{{ route('dashboard') }}}" class="site_title"><i class="fa fa-cog"></i>
            <span>{{{ trans('app.admin_dashboard') }}}</span>
         </a>
      </div>
      <div class="clearfix"></div>
      <br />
      <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
         <div class="menu_section">
            <ul class="nav side-menu">
               <li>
                  <a href="{{{ route('dashboard') }}}"><i class="glyphicon glyphicon-dashboard"></i> {{{ trans('app.dashboard') }}}</a>
               </li>
               <li>
                  <a href="{{{ route('getUsersList') }}}"><i class="fa fa-group"></i> {{{ trans('app.users') }}}</a>
               </li>
               <li>
                  <a href="{{{ route('getPostList') }}}"><i class="fa fa-pencil-square-o"></i> {{{ trans('app.posts') }}}</a>
               </li>
               <li>
                  <a href="{{{ route('getUpdatesList') }}}"><i class="fa fa-check-circle-o"></i> {{{ trans('app.updates') }}}</a>
               </li>
               <li>
                  <a href="{{{ route('getCommentsList') }}}"><i class="fa fa-comments-o"></i> {{{ trans('app.comments') }}}</a>
               </li>
            </ul>
         </div>
         <div class="menu_section">
                <h3>{{{ trans('app.settings') }}}</h3>
                <ul class="nav side-menu">
                    @if(empty($settingCategories) === false)
                        @foreach($settingCategories as $settingCategory)
                            <li>
                                <a href="{{{ route('adminSettings', ['slug' => $settingCategory->slug]) }}}">
                                    <i class="{{{ $settingCategory->icon }}}"></i>
                                    {{{ trans('app.'.$settingCategory->slug)}}}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
        </div>
      </div>
   </div>
</div>

<div class="top_nav">
   <div class="nav_menu">
      <nav class="" role="navigation">
         <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
         </div>
         <ul class="nav navbar-nav navbar-right">
            <li class="">
               <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
               <img src="{{ url(getImageUrl(Auth::user()->profilePicture, 'small', 'User')) }}" alt="">{{{ Auth::user()->name }}}
               <span class=" fa fa-angle-down"></span>
               </a>
               <ul class="dropdown-menu dropdown-usermenu pull-right">
                  <li><a href="{{{ route('userHome') }}}">{{{ trans('app.website') }}}</a></li>
                  <li><a href="{{{ route('logout') }}}"><i class="fa fa-sign-out pull-right"></i> {{{ trans('app.logout') }}}</a></li>
               </ul>
            </li>
         </ul>
      </nav>
   </div>
</div>