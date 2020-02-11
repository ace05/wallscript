 @if(empty($updateObj->user) === false && empty($updateObj->post) === false)
     <div class="row">
          <div class="col-md-8 modal-image">
                @if($updateObj->post->images->count() > 0)
                    @foreach($updateObj->post->images as $image)
                        <img src="{{ url(getImageUrl($image, 'modelBox')) }}" class="img-responsive">
                    @endforeach
                @endif
                @if($updateObj->post->images->count() > 1)
                  <a href="javascript:;" id="mdl-btn-left" class="img-modal-btn left"><i class="glyphicon glyphicon-chevron-left"></i></a>
                  <a href="javascript:;" id="mdl-btn-right"  class="img-modal-btn right"><i class="glyphicon glyphicon-chevron-right"></i></a>
                @endif
          </div>
          <div class="col-md-4 modal-meta">
            <div class="modal-meta-top">
            <button type="button" data-dismiss="modal" class="close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <div class="img-poster clearfix">
                    <div class="media"> 
                            <div class="media-left"> 
                                @if(in_array($updateObj->update_type, ['comment', 'share', 'like']))
                                    <a href="{{{ route('userProfile', ['username' => $updateObj->post->user->username]) }}}"><img class="img-circle media-object" src="{{ url(getImageUrl($updateObj->post->user->profilePicture, 'medium', 'User')) }}" alt="{{{ $updateObj->post->user->name }}}" /></a>
                                @else
                                   <a href="{{{ route('userProfile', ['username' => $updateObj->post->user->username]) }}}"> <img class="img-circle media-object" src="{{ url(getImageUrl($updateObj->user->profilePicture, 'medium', 'User')) }}" alt="{{{ $updateObj->user->name }}}" /></a>
                                @endif
                            </div> 
                            <div class="media-body"> 
                                <h4 class="media-heading">
                                     @if(in_array($updateObj->update_type, ['comment', 'share', 'like']))
                                        <a class="post-name" href="{{{ route('userProfile', ['username' => $updateObj->post->user->username]) }}}">
                                            {{{ $updateObj->post->user->name }}}
                                        </a>                  
                                    @else
                                        <a class="post-name" href="{{{ route('userProfile', ['username' => $updateObj->user->username]) }}}">
                                            {{{ $updateObj->user->name }}}
                                        </a>
                                    @endif
                                    @if(empty($updateObj->post->place) === false)
                                        <span class="add-place-section">
                                            {{{ trans('app.at') }}} <i class="fa fa-map-marker"></i> <a href="javascript:;" data-toggle="popover" data-placement="bottom" data-title="{{{ $updateObj->post->place }}}" data-content="&lt;img src='https://maps.googleapis.com/maps/api/staticmap?center={{{ $updateObj->post->lat }}},{{{ $updateObj->post->lng }}}&zoom=14&size=250x250&key={{{ config('db.google_map_api_key') }}}&markers=color:red%7Clabel:G%7C{{{ $updateObj->post->lat }}},{{{ $updateObj->post->lng }}}' /&gt;"> {{{ $updateObj->post->place }}}</a>
                                        </span>
                                    @endif
                                    @if($updateObj->post->getTaggedFriends->count() > 0)
                                        <span class="add-friends-tags">
                                            {{{ trans('app.with') }}} {!! formatFriendTagsContext($updateObj->post->getTaggedFriends) !!}
                                        </span>
                                    @endif
                                    <div class="time-ago">
                                        <a href="{{{ route('viewUpdate', ['id' => $updateObj->id]) }}}"> {{{ timeAgo($updateObj->created_at) }}}</a>
                                         -            
                                        @if($updateObj->privacy == 1)
                                            <a href="javascript:;" class="privacy-icon" data-toggle="tooltip" data-original-title="{{{ trans('app.shared_publicly') }}}"><span class="fa fa-unlock" ></span></a>
                                        @elseif($updateObj->privacy == 2)
                                            <a href="javascript:;" class="privacy-icon" data-toggle="tooltip" data-original-title="{{{ trans('app.shared_with_friends') }}}"><span class="fa fa-users"></span></a>
                                        @else
                                            <a href="javascript:;" class="privacy-icon" data-toggle="tooltip" data-original-title="{{{ trans('app.shared_privately') }}}"><span class="fa fa-lock" ></span></a>
                                        @endif
                                    </div>
                                </h4> 
                            </div>
                            <hr>
                            <div class="message-area">
                                <p>{!! formatText($updateObj->post->message) !!}</p>
                            </div>
                    </div>
                    <div class="modal-bottom-area">
                        <div class="comment-section-actions">
                            <a href="{{{ route('addLike', ['postId' => $updateObj->post_id, 'type' => 'like', 'updateId' => $updateObj->id]) }}}" data-id="like-sec-{{{ $updateObj->id }}}" data-class="like-modal-sec-{{{ $updateObj->id }}}" data-toggle="popover" data-content='{{{ getLikeEmotions($updateObj->post_id, $updateObj->id) }}}' class="btn btn-sm btn-default like-popover like-types like-sec-{{{ $updateObj->id }}}" >
                                {!! getLikeText($updateObj) !!}
                            </a>
                            @if($updateObj->post->user_id != \Auth::user()->id)
                                <a href="{{{ route('shareUpdate', ['postId' => $updateObj->post_id]) }}}" class="btn btn-sm btn-default ajax-share">
                                    <span class="glyphicon glyphicon-share-alt"></span>{{{ trans('app.share') }}}
                                </a>
                            @endif
                            @if($updateObj->post->comments->count() > 2)
                                <a href="{{{ route('getAllcomments', ['postId' => $updateObj->post_id]) }}}" data-id="cmt-sec-{{{ $updateObj->id }}}" class="btn btn-default btn-sm ajax-all-comments">
                                    {{{ trans('app.view_all_comments', ['count' => $updateObj->post->comments->count()]) }}}
                                </a>
                            @endif
                        </div>
                        <div class="like-section like-modal-sec-{{{ $updateObj->id }}}">
                            @if($updateObj->post->likes->count() > 0)
                                {!! likeUrl($updateObj->post->likes, $updateObj->post_id) !!}
                            @else
                                <a href="{{{ route('getAllLikes', ['postId' => $updateObj->post_id])}}}" data-toggle="modal" data-target="#likeModal" class="like-popup"></a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="img-comment-list">
                    @if($updateObj->post->comments->count() > 0)
                        <div class="cmt-sec-{{{ $updateObj->id }}}">
                            @include('frontend.partials.comments', ['comments' => $updateObj->post->getLastTwoComments, 'updateId' => $updateObj->id, 'updateType' => $updateObj->update_type])
                        </div>
                    @else
                        <div class="cmt-sec-{{{ $updateObj->id }}}">
                        </div>
                    @endif
                 </div>
            </div>
            <div class="modal-meta-bottom">
                    <div class="panel-update-comment" id="comment-form-{{{ $updateObj->id }}}">
                        <div class="panel-update-textarea">
                            {!! Form::open(['url' => route('addComment'), 'class' => 'cmt-form']) !!}
                                {!! Form::textarea('comment', null, ['class' => 'comment-area']) !!}
                                <div class="cmt-image-preview-{{{ $updateObj->id }}}" id="cmt-img-preview"></div>
                                <a href='javascript:;' data-class="cmt-image-preview-{{{ $updateObj->id }}}" class="file-upload-block btn btn-default">
                                    <i class="fa fa-camera"></i>
                                    {!! Form::file('commentImage', ['class' => 'file-chooser', 'data-class' => 'cmt-image-preview-'.$updateObj->id]) !!}
                                </a>
                                {!! Form::hidden('update_id', $updateObj->id) !!}
                                {!! Form::hidden('post_id', $updateObj->post_id) !!}
                                {!! Form::button(trans('app.post_comment'), ['class' => 'btn btn-success cmt-button btn-sm', 'type' => 'submit']) !!}
                            {!! Form::close() !!}
                        </div>
                        <div class="clearfix"></div>
                    </div>
            </div>
          </div>
      </div>
@endif