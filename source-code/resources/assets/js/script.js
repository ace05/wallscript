var originalLeave = $.fn.popover.Constructor.prototype.leave;
$.fn.popover.Constructor.prototype.leave = function(obj) {
    var self = obj instanceof this.constructor ?
        obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
    var container, timeout;
    originalLeave.call(this, obj);
    if (obj.currentTarget) {
        container = $('body .popover:last-child');
        timeout = self.timeout;
        container.one('mouseenter', function() {
            clearTimeout(timeout);
            container.one('mouseleave', function() {
                $.fn.popover.Constructor.prototype.leave.call(self, self);
            });
        })
    }
};
var getXsrfToken = function() {
    var cookies = document.cookie.split(';');
    var token = '';

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].split('=');
        if(cookie[0] == 'XSRF-TOKEN') {
            token = decodeURIComponent(cookie[1]);
        }
    }

    return token;
};
$(function() {
    $.ajaxSetup({
        headers: {
            'X-XSRF-TOKEN': getXsrfToken()
        }
    });
	/* Username url appending*/
	$('.ap-username').html('<b>'+$('.username').val()+'</b>');
	$(document).delegate('.username', 'keyup', function(){
		$('.ap-username').html('<b>'+$('.username').val()+'</b>');
	});

    $('.selectpicker').selectpicker({
      style: 'btn-default',
      size: 4
    });

    $("body").tooltip({
        selector: '[data-toggle="tooltip"]'
    });

	$('body').delegate('.comment-add, .comment-area-reset', 'click', function(event) {
        var panel = $(this).closest('.panel-update');
            comment = panel.find('.panel-update-comment');
            commentClass = $(this).attr('data-id');            
        comment.find('.btn:first-child').addClass('disabled');
        comment.find('textarea').val('');        
        panel.toggleClass('panel-update-show-comment');        
        if (panel.hasClass('panel-update-show-comment')) {
            $('html, body').animate({
                scrollTop: $("#"+commentClass).offset().top - 50
            }, 1000);
            comment.find('textarea').focus();
        }
   });

    var uploadFiles = [];

   /* File Uploader */
    $("body").delegate('.file-chooser', 'change', function () {
        var previewBlock = $('.'+$(this).attr('data-class'));
        if (typeof (FileReader) != "undefined") {
            $($(this)[0].files).each(function (i,v) {
                uploadFiles.push(v);
            });
            previewBlock.html("");
            $('.file-chooser').val("");
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
            $(uploadFiles).each(function () {
                var file = $(this);
                if (regex.test(file[0].name.toLowerCase())) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var img = $("<img />");
                        img.attr("style", "height:100px;width: 100px");
                        img.attr("src", e.target.result);
                        img.attr("class", 'img-responsive img-thumbnail');
                        previewBlock.append(img);
                    }
                    reader.readAsDataURL(file[0]);
                } else {
                    previewBlock.html("<div class='alert alert-danger'>"+file[0].name + " is not a valid image file.</div>");
                    return false;
                }
            });
        } else {
            previewBlock.html("<div class='alert alert-danger'>This browser does not support HTML5 FileReader.</div>");
        }
    });

    /* Post Update Tab */
    $(document).delegate('.update-type', 'click', function(){
        var className = $(this).attr('data-class');
        var extraFormFields = $('.extra-form');
        extraFormFields.addClass('hidden');
        $('.'+className).removeClass('hidden');
    });

    /* Iframe videos */
    $("iframe").wrap('<div class="embed-responsive  embed-responsive-4by3"/>');
    $("iframe").addClass('embed-responsive-item');

    if($('.google-map-api-enabled').length > 0){
        $(".placeSearch").geocomplete({ details: "form" })
        .bind("geocode:result", function(event, result){
            $('.con-location').html('at <a href="javascript:;" data-class="placeSection" class="update-type">'+result.formatted_address+'</a>');
            $('.placeSection').addClass('hidden');
        });        
    }

    /**** POST Form ****/
    $('form.post-form').ajaxForm({
        "beforeSubmit": function(formData, jqForm, options){
            if(uploadFiles){
                $(uploadFiles).each(function(i,v){
                    if(i != uploadFiles.length)
                    {
                        formData.push({ "name": "images[]", "value": v });
                    }
                });
            }
            $('.post-button').button('loading');
        },
        "success": function(responseText, statusText, xhr, $form){
            uploadFiles = [];
            $('.no-updates').remove();
            $('.post-button').button('reset');
            $('.post-message').val('');
            $('.con-friends').html('');
            $('.con-location').html('');
            $('.imageSection').html('');
            $('.placeSearch').val('');
            $('.file-chooser').val('');
            $('.lng').val('');
            $('.lat').val('');
            $('.extra-form').addClass('hidden');
            $(responseText).prependTo('.updates').fadeIn('slow');
            $('[data-toggle="tooltip"]').tooltip();
            $("iframe").wrap('<div class="embed-responsive  embed-responsive-4by3"/>');
            $("iframe").addClass('embed-responsive-item');
        },
        "error": function(xhr, status, error){
            $('.post-button').button('reset');
            if(xhr.status == 422){
                swal("Please write something");
            }
            if(xhr.status == 500){
                swal("Oops! unable to post.");
            }
        }
    });

    $('form.cmt-form').ajaxForm({ 
        "beforeSubmit": function(formData, jqForm, options){
            $('.cmt-button').button('loading');
        },
        "success": function(res, statusText, xhr, $form){
            $('.cmt-button').button('reset');
            $('#cmt-img-preview').html('');            
            $('.cmt-sec-'+$form.context[3].value).addClass('comments-section');
            $(res).prependTo('.cmt-sec-'+$form.context[3].value).fadeIn('slow');
        },
        "error": function(xhr, status, error){
            $('.cmt-button').button('reset');
            if(xhr.status == 422){
                swal("Please write something");                
            }
        },
        "resetForm": true,
        "delegation": true
    });
    //PopOver
    $("body").popover({
        selector: '[data-toggle="popover"]',
        trigger: 'click hover',
        container:'body',
        placement: 'right',
        delay: {show: 50, hide: 400},
        html: true
    });
    //LoadMore Pagination
    $(window).scroll(function(){
        if($(window).scrollTop() == $(document).height() - $(window).height()){
            var nextUrl = $("div.pagination li:last-child > a" ).attr('href');
                pagination = $('div.pagination');
                parentElement = pagination.parent();
                loader = $('div.loader');
            if(typeof nextUrl != 'undefined'){
                loader.removeClass('hidden');
                $.ajax({
                    type: "GET",
                    url: nextUrl,
                    async: false,
                    success : function(data) {
                        pagination.remove();
                        loader.remove();
                        parentElement.append(data).fadeIn('slow');
                        $('[data-toggle="tooltip"]').tooltip();
                        $("iframe").wrap('<div class="embed-responsive  embed-responsive-4by3"/>');
                        $("iframe").addClass('embed-responsive-item');
                    }
                });             
            }
        }
    });

    //Get All Comments
    $('body').delegate('.ajax-all-comments', 'click', function(e){
        e.preventDefault();
        var commentSection = $('.'+$(this).attr('data-id'));
        $.get($(this).attr('href'), function(comments){
            commentSection.html(comments);
            $('html, body').animate({
                scrollTop: commentSection.offset().top - 50
            }, 1000);
        });
    });

    //Likes
    $('body').delegate('.like-types', 'click', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
            dataClass = $(this).attr('data-class');
        $.get($(this).attr('href'), function(like){
            $('[data-toggle="popover"]').popover('hide');
            $('#'+id).html(like.html);
            $('.'+dataClass).html(like.html);
            console.log(dataClass);
            if(typeof like.url != 'undefined'){
                $('.'+id).attr('href', like.url);
            }
            if(typeof like.context != 'undefined'){
                $('.'+id).empty().html(like.context);
            }
        });
    });

    $("#likeModal").on("hide.bs.modal", function (e) {
        $(this).removeData('bs.modal');
    });
    $("#update-modal").on("hide.bs.modal", function (e) {
        $(this).removeData('bs.modal');
    });

    $(document).on('show.bs.modal', '.modal', function () {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });
    //Ajax Delete
    $('body').delegate('.ajax-delete', 'click', function(e){
        e.preventDefault();
        var panelClass = $('.'+$(this).attr('data-class'));
            url = $(this).attr('href');
            panelId = $('.'+$(this).attr('data-id'));
        swal({
          "title": "Are you sure?",
          "type": "warning",
          "showCancelButton": true,
          "confirmButtonColor": "#DD6B55",
          "confirmButtonText": "Yes, delete it!",
          "closeOnConfirm": false
        }).then(function(){
            $.get(url, function(update){
                if(update.status === false){
                    swal("Unable to Delete. Please try again later"); 
                }
                else{
                    panelClass.hide('slow', function(){ panelClass.remove(); });
                    $text = 'Post';
                    if(typeof update.type != 'undefined' && update.type == 'comment'){
                        $text = 'Comment';
                    }
                    if(typeof update.type != 'undefined' && update.type == 'post'){
                        $('.update-'+update.updateId).hide('slow', function(){ 
                            $('.update-'+update.updateId).remove(); 
                        });
                        panelId.hide('slow', function(){ 
                            panelId.remove(); 
                        });
                    }
                    if(typeof update.type != 'undefined' && update.type == 'like'){
                        $text = 'Update';
                    }
                    if(typeof update.type != 'undefined' && update.type == 'share'){
                        $text = 'Update';
                    }
                    swal("Deleted!", $text+" has been deleted successfully", "success");
                }
            });
            
        });
        
    });

    //Ajax post share and unshare
    $('body').delegate('.ajax-share', 'click', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        swal({
          "title": "Are you sure want to share?",
          "type": "warning",
          "showCancelButton": true,
          "confirmButtonColor": "#DD6B55",
          "confirmButtonText": "Yes, Share it!",
          "closeOnConfirm": false
        }).then(function(){
            console.log(url);
            $.get(url, function(update){
                console.log(update);
                if(update.status === false){
                    swal("Unable to share. Please try again later"); 
                }
                else{
                    swal("Shared!", "Post has been shared successfully", "success");
                }
            });
            
        });
    });

    //Ajax notification Load
    $(".notifications").on("show.bs.dropdown", function(event){
        var loader = '<li class="text-center"> <i class="fa fa-refresh fa-spin fa-3x fa-fw" aria-hidden="true"></i></li>';
            url = $('.dropdown-toggle', this).attr('data-url');
            curObj = $('.dropdown-menu', this);
        curObj.html(loader);
        $.get(url, function(notifications){
            curObj.html(notifications);
        });     
    });

       
    //Ajax Friend Request System
    $("body").delegate(".ajax-friend-request", 'click', function(e){
        e.preventDefault();
        var _this = $(this);
        $.get(_this.attr('href'), function(data, status, xhr){
            if(xhr.status == 200 && data.status == true){
                _this.attr('href', data.link);
                _this.html(data.text);
            }
        });     
    }); 
    $(document).on('click', '#friend-requests .dropdown-menu, #messages .dropdown-menu', function (e) {
        e.stopPropagation();
    });

    //UnFriend
    $("body").delegate(".ajax-unfriend", 'click', function(e){
        e.preventDefault();
        var _this = $(this);
            url = _this.attr('href');
            _class = _this.attr('data-class');
        swal({
          "title": "Are you sure want to do this?",
          "type": "warning",
          "showCancelButton": true,
          "confirmButtonColor": "#DD6B55",
          "confirmButtonText": "Yes!",
          "closeOnConfirm": false
        }).then(function(){
            $.get(url, function(friend){
                if(friend.status == true){
                    $('.'+_class).addClass('friend-list-deleted');
                }
            });
            
        });     
    });
    
    $('body').delegate('#cover-photo', 'change', function(e){
        e.preventDefault();
        $(".cover-photo-form").ajaxForm({
            beforeSubmit:function(){
                swal({
                    "title":'<div class="text-center"> <i class="fa fa-refresh fa-spin fa-3x fa-fw" aria-hidden="true"></i></div>',
                    "text": 'Uploading is in progress...',
                    "showCancelButton": false,
                    "showConfirmButton": false
                });
            },
            success:function(res, statusText, xhr, $form){
                if(res.status == 422){
                    var errorString = '<ul class="alert alert-danger">';
                    $.each(res.errors, function( key, value) {
                        errorString += '<li>' + value + '</li>';
                    });
                    errorString += '</ul>';
                    swal({ 'title': '', 'text': errorString});
                }
                else{
                    if(xhr.status == 200){
                        $('.cover-resize-wrapper').html('<div class="drag-div" align="center">Drag to reposition</div><img src="'+res.coverUrl+'" class="coverPhotoBackground" />');
                        $('.cover-wrapper').hide();
                        $('.cover-photo-form').hide();
                        $('.cover-resize-wrapper').show();
                        $('.cover-photo-action-block').show();
                        $('#cover-photo').val('');
                        $('.cover-resize-wrapper img')
                        .css('cursor', 's-resize')
                        .draggable({
                            scroll: false,                        
                            axis: "y",                        
                            cursor: "s-resize",                        
                            drag: function (event, ui) {
                                y1 = $('.timeline-header-wrapper').height();
                                y2 = $('.cover-resize-wrapper').find('img').height();
                                
                                if (ui.position.top >= 0) {
                                    ui.position.top = 0;
                                }
                                else if (ui.position.top <= (y1-y2)) {
                                    ui.position.top = y1-y2;                                
                                }
                            },
                            
                            stop: function(event, ui) {
                                var p = $(".coverPhotoBackground").attr("style");
                                    x = 0;
                                if(p){
                                    var position = p.split("top:");
                                    x = position[1];
                                }
                                $('input.selectedPosition').val(x);
                            }
                        });
                    }
                    swal.close();
                }
            },
            error:function(xhr, status, error){
                $('#cover-photo').val('');
                swal({
                    "title":'',
                    "text": '<div class="alert alert-danger">'+xhr.responseJSON.errors.coverPhoto[0]+'</div>',
                    "showCancelButton": true
                });
            } 
        }).submit();
    });

    $('body').delegate('#profile-photo', 'change', function(e){
        e.preventDefault();
        $(".profile-photo-form").ajaxForm({
            beforeSubmit:function(){
                swal({
                    "title":'<div class="text-center"> <i class="fa fa-refresh fa-spin fa-3x fa-fw" aria-hidden="true"></i></div>',
                    "text": 'Uploading is in progress...',
                    "showCancelButton": false,
                    "showConfirmButton": false
                });
            },
            success:function(res, statusText, xhr, $form){                

                if(res.status == 422){
                    var errorString = '<ul class="alert alert-danger">';
                    $.each(res.errors, function( key, value) {
                        errorString += '<li>' + value + '</li>';
                    });
                    errorString += '</ul>';
                    swal({ 'title': '', 'text': errorString});
                }
                else{
                    if(xhr.status == 200){
                        if(res.status == true) {
                            $('#profilePic').attr('src', res.profileUrl);
                            $('#profile-photo').val('');
                        } 
                        swal.close();    
                    }
                }
                
            },
            error:function(xhr, status, error){
                
            } 
        }).submit();
    });

    $('body').delegate('.cover-cancel', 'click', function(e){
        e.preventDefault();
        $('.cover-resize-wrapper').html('');
        $('.cover-resize-wrapper').hide();
        $('.cover-photo-form').show();
        $('.cover-photo-action-block').hide();
        $('.cover-wrapper').show();
        $('input.selectedPosition').val('');
    });

    $('form.cover-update-form').ajaxForm({ 
        "beforeSubmit": function(formData, jqForm, options){
            $('.save-button').button('loading');
        },
        "success": function(res, statusText, xhr, $form){
            $('.save-button').button('reset');
            if(res.status == true){
                $('.cover-resize-wrapper').html('');
                $('.cover-resize-wrapper').hide();
                $('.cover-photo-form').show();
                $('.cover-photo-action-block').hide();
                $('input.selectedPosition').val('');
                $('.img-cover').attr('src', res.url);
                $('.cover-wrapper').show();
            }
        },
        "error": function(xhr, status, error){
            $('.save-button').button('reset');
        },
        "resetForm": true,
        "delegation": true
    });

    //Tag friends
    $('.friends-autocomplete').select2({
        tokenSeparators: [','],
        theme: "bootstrap",
        ajax: {
            url: $('.friends-autocomplete').attr('data-url'),
            dataType: 'json',
            type: 'GET',
            data: function (params) {
              return {
                q: params.term
              };
            },
            processResults: function (data, page) {
              return {
                results: $.map(data, function (key,value) {
                    return { text: key, id: value }
                })
              };
            }
          },
        minimumInputLength: 1
    });
    $('body').delegate('.friends-autocomplete', 'select2:select', function(e){
        var data = $(this).select2('data');
        var selectedText = $.map(data, function(selected, i) {
            return selected.text;
        }).join();
        $.post($('.friends-autocomplete').attr('data-text-url'), { 
            selectedFriends : selectedText,
            _token : $('meta[name="csrf-token"]').attr('content')
        }).done(function(data){
            if($.trim(data.tagText) == ''){
                $('.con-friends').html('');
            }
            else{
                $('.con-friends').html('<a href="javascript:;" data-class="friendsSection" class="update-type">'+data.tagText+'</a>');
            }
        });        
    });
    $('body').delegate('.friends-autocomplete', "select2:unselect", function (e) { 
        var data = $(this).select2('data');
        var selectedText = $.map(data, function(selected, i) {
            return selected.text;
        }).join();
        $.post($('.friends-autocomplete').attr('data-text-url'), { 
            selectedFriends : selectedText,
            _token : $('meta[name="csrf-token"]').attr('content')
        }).done(function(data){
            if($.trim(data.tagText) == ''){
                $('.con-friends').html('');
            }
            else{
                $('.con-friends').html('<a href="javascript:;" data-class="friendsSection" class="update-type">'+data.tagText+'</a>');
            }
        });
    });

    //Message Reply
    $('form.message-reply-form').ajaxForm({ 
        "beforeSubmit": function(formData, jqForm, options){
            $('.reply-btn').button('loading');
        },
        "success": function(res, statusText, xhr, $form){
            $('.reply-btn').button('reset');
            if(res.status == 422){
                var errorString = '<ul class="alert alert-danger">';
                $.each(res.errors, function( key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul>';
                swal({ 'title': '', 'text': errorString});                
            }
            else{
                if($.trim(res) != ''){
                    $('#message-img-preview').html('');
                    $('.message').val('');
                    $('#message-attachment-photo').val('');
                    $('.messages-list').prepend(res).fadeIn('slow');
                }
            }

        },
        "error": function(xhr, status, error){
            $('.reply-btn').button('reset');            
        },
        "delegation": true
    });

    $('body').delegate('.message-load-more', 'click', function(e){
        e.preventDefault();
        var nextUrl = $("ul.pagination li:last-child > a" ).attr('href');
            pagination = $('ul.pagination');
            loader = $('div.loader');
        if(typeof nextUrl != 'undefined'){
            loader.removeClass('hidden');
            $.get(nextUrl, function(data){
                if($.trim(data) === ''){
                    $('.message-load-more').remove();
                }
                pagination.remove();
                loader.remove();
                $('.messages-list').append(data).fadeIn('slow');
            });                
        }
        else{
            $('.message-load-more').remove();
        }
    });

    $("body").delegate(".ajax-markread", 'click', function(e){
        e.preventDefault();
        var _this = $(this);
        $.get(_this.attr('href'), function(data, status, xhr){
            if(xhr.status == 200 && data.status == true){
                _this.remove();
            }
        });     
    });

    //Search
    if($('.people-search-autocomplete').length > 0){
        var peoples = new Bloodhound({
            datumTokenizer: function(datum) {
                return Bloodhound.tokenizers.whitespace(datum.name);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                wildcard: '%QUERY',
                url: $('.people-search-autocomplete').attr('data-url')+'?q=%QUERY'
            }
        });
        $('.people-search-autocomplete').typeahead({
            minLength: 0,
            highlight: true
          },{
            name: 'peoples',
            display: 'name',
            source: peoples,
            templates: {
                empty: [
                  '<div class="empty-message">',
                    'No search results',
                  '</div>'
                ].join('\n'),
                suggestion: function(data){
                    return '<div class="media"><a class="pull-left" href="'+data.url+'"><img class="media-object" src="'+data.avatar+'"></a><div class="media-body"><h4 class="media-heading"><a class="pull-left" href="'+data.url+'">'+data.name+'</a></h4></div></div>'
                }
            }
        });
        $('form.people-search-form').submit(false);
    }

    //Fix Share Area
    if($('.profile-layout').length && $(window).width() > 992){
        $(window).on('scroll',function(){
            if( $(window).scrollTop() > $('.updates').offset().top) {
                $('.share-area').addClass('share-area-fixed').css({width: $('.friends-list').width()});
            }else {
                $('.share-area').removeClass('share-area-fixed').css({width: 'auto'});
            }
        });
    }

    $("body").delegate("#mdl-btn-left", 'click', function(e){
        e.preventDefault();
        cur = $(this).parent().find('img:visible()');
        next = cur.next('img');
        par = cur.parent();
        if (!next.length) { next = $(cur.parent().find("img").get(0)) }
        cur.addClass('hidden');
        next.removeClass('hidden');        
        return false;
    });
    
    $("body").delegate("#mdl-btn-right", 'click', function(e){
        e.preventDefault();
        cur = $(this).parent().find('img:visible()');
        next = cur.prev('img');
        par = cur.parent();
        children = cur.parent().find("img");
        if (!next.length) { next = $(children.get(children.length-1)) }
        cur.addClass('hidden');
        next.removeClass('hidden');        
        return false;
    });
});
(function notificationPoll() {
    if(isLoggedIn == true){
        $.get(notificationUrl, function(data, status, xhr){
            if(xhr.status == 200){
                if(data.count != 0){
                    $('.notification-badge').html(data.count);
                }
                else{
                    $('.notification-badge').html('');
                }

                if(data.friendCount != 0){
                    $('.friend-request-badge').html(data.friendCount);
                }
                else{
                    $('.friend-request-badge').html('');
                }

                if(data.messageCount != 0){
                    $('.messages-badge').html(data.messageCount);
                }
                else{
                    $('.messages-badge').html('');
                }
            }
            setTimeout(notificationPoll, 5000);
        });
    }
})();