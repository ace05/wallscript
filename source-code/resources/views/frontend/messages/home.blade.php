@extends('frontend.layouts.app')

@section('title')
    {{{ trans('app.messages') }}}
@endsection

@section('content')
<div class="row">
	<div class="message-wrapper">
		@if(empty($conversations) === false)
			<div class="col-md-4">
				<div class="message-user-list well">
					<ul class="media-list conversation-list">
						@foreach($conversations as $conversation)
							@if(Auth::user()->id != $conversation->first_user_id)
								{{--*/ $user = $conversation->fromUser /*--}}
							@else
								{{--*/ $user = $conversation->toUser /*--}}
							@endif
						   	<li class="media @if(empty($conversationUserId) === false &&$conversationUserId == $user->id) active @endif">
						   		<a href="{{{ route('conversation', ['username' => $user->username]) }}}">
							      	<div class="media-left">
							      		<img src="{{ url(getImageUrl($user->profilePicture, 'likeThumb', 'User')) }}" class="media-object" > 
							      	</div>
							      	<div class="media-body">
							         	<h4 class="media-heading">{{{ $user->name }}}</h4>
							         	{{--*/ $lastMessage = $conversation->getLatestMessage($conversation->cid) /*--}}
							         	@if(empty($lastMessage->message) === false)
							         		<p> <span class="glyphicon glyphicon-share-alt"></span>{{{ timeAgo($lastMessage->created_at) }}}</p>
							         	@endif
							      	</div>
							    </a>
						   </li>
						@endforeach
					</ul>
				</div>
			</div>
			<div class="col-md-8">
				@if($username === 'all')
					<div class="message-conversation-list">
						<div class="alert alert-warning">
							{{{ trans('message.no_conversation_selected') }}}
						</div>
					</div>
				@else
					<div class="message-conversation-list">
						@if(empty($messages) === false)						
							<div class="loader hidden text-center">
								<div class="btn btn-default">
									<i class="fa fa-refresh fa-spin fa-3x fa-fw" aria-hidden="true"></i>
								</div>
							</div>
							<div class="messages-list"> 
								@include('frontend.partials.messages', ['messages' => $messages])
								<div class="hidden">
									{{{ $messages->links() }}}
								</div>
							</div>
							@if($messages->total() > 20)
								<div class="text-center">
									<a href="javascript:;" class="btn btn-default message-load-more">
										<span class="glyphicon glyphicon-refresh"></span> {{{ trans('app.load_old_messages') }}}
									</a>
								</div>
							@endif			
						@endif
					</div>
					<div class="conversation-form ">
						{!! Form::open(['url' => route('sendMessage'), 'class' => 'message-reply-form', 'files' => true]) !!}
			        		{!! Form::textarea('message', null, ['class' => 'comment-area message expanding']) !!}
			        		<div class="message-image-preview" id="message-img-preview"></div>
							<a href='javascript:;' data-toggle="tooltip" data-placement="bottom" data-original-title="{{{ trans('app.photos') }}}" data-class="message-image-preview" class="file-upload-block btn btn-default">
								<i class="fa fa-camera"></i>
								{!! Form::file('photos', ['class' => 'file-chooser', 'data-class' => 'message-image-preview', 'id' => 'message-attachment-photo']) !!}
							</a>
							{!! Form::hidden('user_id', Auth::user()->id) !!}
							{!! Form::hidden('conversation_id', $conversationId) !!}
							{!! Form::button(trans('app.send'), ['class' => 'btn btn-success btn-sm reply-btn', 'type' => 'submit']) !!}
				        {!! Form::close() !!}
					</div>
				@endif				
			</div>
		@endif
	</div>
</div>
@endsection