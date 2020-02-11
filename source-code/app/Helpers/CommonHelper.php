<?php

if(function_exists('randomKey') === false){
	function randomKey($len = 8)
	{
	   $user = '';
	   $lchar = 0;
	   $char = 0;
	   for($i = 0; $i < $len; $i++)
	   {
	       while($char == $lchar)
	       {
	           $char = rand(48, 109);
	           if($char > 57) $char += 7;
	           if($char > 90) $char += 6;
	       }
	       $user .= chr($char);
	       $lchar = $char;
	   }
	   return $user;
	}
}

if(function_exists('getImagePath') === false){
	function getImagePath($attachment) {
		if(is_null($attachment)) {
			return null;	
		}
		if(in_array(strtolower(PHP_OS), array("win32", "windows", "winnt"))) {
			$path = sprintf('%s\\%s\\%s\\%s',public_path(),'uploads', $attachment->type, $attachment->filename);
		} else if(in_array(strtolower(PHP_OS), array("linux", "superior operating system"))) {
			$path = sprintf('%s/%s/%s/%s',public_path(), 'uploads',$attachment->type, $attachment->filename);
		} else{
			$path = sprintf('%s/%s/%s/%s',public_path(),'uploads',$attachment->type, $attachment->filename);
		}

		return $path;
	}
}

if(function_exists('getImageUrl') === false){
	function getImageUrl($attachment, $size, $type = '') {
		if(is_null($attachment) && $type == 'User') {
			return sprintf('/image/0/%s/%s', $size, sprintf('%s.%s.%s', 1, md5('default-avatar.jpg'), 'jpg'));
		}
		
		if(empty($type) === false && $type === 'aspect'){
			return sprintf('/image/1/%s/%s', $size, sprintf('%s.%s.%s', $attachment->id, md5($attachment->filename), 'jpg'));
		}

		return sprintf('/image/0/%s/%s', $size, sprintf('%s.%s.%s', $attachment->id, md5($attachment->filename), 'jpg'));
	}
}

if(function_exists('getCoverUrl') === false){
	function getCoverUrl($attachment, $size)
	{
		if(is_null($attachment)) {
			return 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iOTAwIiBoZWlnaHQ9IjUwMCIgdmlld0JveD0iMCAwIDkwMCA1MDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzkwMHg1MDAvYXV0by8jNTU1OiM1NTUKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNTZiMGYxZWEzYiB0ZXh0IHsgZmlsbDojNTU1O2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjQ1cHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE1NmIwZjFlYTNiIj48cmVjdCB3aWR0aD0iOTAwIiBoZWlnaHQ9IjUwMCIgZmlsbD0iIzU1NSIvPjxnPjx0ZXh0IHg9IjMzNC41IiB5PSIyNzAuNCI+OTAweDUwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg==';
		}

		return sprintf('/image/0/%s/%s', $size, sprintf('%s.%s.%s', $attachment->id, md5($attachment->filename), 'jpg'));
	}
}

if(function_exists('randomUsername') === false){
	function randomUsername($string) {
		$pattern = " ";
		$firstPart = strstr(strtolower($string), $pattern, true);
		$secondPart = substr(strstr(strtolower($string), $pattern, false), 0,3);
		$nrRand = rand(0, 100);

		$username = trim($firstPart).trim($secondPart).trim($nrRand);
		return $username;
	}
}

if(function_exists('timeAgo') === false){
	function timeAgo($dateTime) {
		if(empty($dateTime) === false) return \Carbon\Carbon::parse($dateTime)->diffForHumans();
		
		return '';
	}
}

if(function_exists('formatText') === false){
	function formatText($str) {
		if(empty($str) === false){
			$str = strip_tags(htmlentities($str));
			if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches)){
                for ($i = 0; $i < count($matches['0']); $i++){
                    $period = '';
                    if (preg_match("|\.$|", $matches['6'][$i])){
                        $period = '.';
                        $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
                    }

                    $str = str_replace($matches['0'][$i],
                            $matches['1'][$i].'<a  target="_blank" href="http'.
                            $matches['4'][$i].'://'.
                            $matches['5'][$i].
                            $matches['6'][$i].'">http'.
                            $matches['4'][$i].'://'.
                            $matches['5'][$i].
                            $matches['6'][$i].'</a>'.
                            $period, $str
                    );
                }
            }

		}

		return nl2br($str);
	}
}

if(function_exists('getTypeContext') === false){
	function getTypeContext($context) {
		$str = '';
		switch (strtolower($context)) {
			case 'comment':
				$str = trans('app.commented_on_this');
				break;
			case 'like':
				$str = trans('app.liked_this');
				break;
			case 'share':
				$str = trans('app.shared_this');
				break;
			default:
				break;
		}

		return $str;
	}
}

if(function_exists('getLikeEmotions') === false){
	function getLikeEmotions($postId, $uid){
		return e('<a href="'.route('addLike', ['postId' => $postId, 'type' => 'like', 'updateId' => $uid]).'" data-toggle="tooltip" data-placement="bottom" data-original-title="{{{ trans("app.like") }}}" title="'.trans("app.like").'" class="like-types" data-class="like-modal-sec-'.$uid.'" data-id="like-sec-'.$uid.'"><span class="twa-thumbsup btn-primary glyphicon glyphicon-thumbs-up"></span></a><a href="'.route('addLike', ['postId' => $postId, 'type' => 'haha', 'updateId' => $uid]).'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.trans("app.haha").'" class="like-types" data-class="like-modal-sec-'.$uid.'" data-id="like-sec-'.$uid.'"><span class="twa twa-3x twa-haha"></span></a><a href="'.route('addLike', ['postId' => $postId, 'type' => 'wow', 'updateId' => $uid]).'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.trans("app.wow").'" class="like-types" data-id="like-sec-'.$uid.'" data-class="like-modal-sec-'.$uid.'"><span class="twa twa-3x twa-wow"></span></a><a href="'.route('addLike', ['postId' => $postId, 'type' => 'sad', 'updateId' => $uid]).'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.trans("app.sad").'" class="like-types" data-class="like-modal-sec-'.$uid.'" data-id="like-sec-'.$uid.'"><span class="twa twa-3x twa-sad"></span></a><a href="'.route('addLike', ['postId' => $postId, 'type' => 'angry', 'updateId' => $uid]).'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.trans("app.angry").'" class="like-types" data-id="like-sec-'.$uid.'" data-class="like-modal-sec-'.$uid.'"><span class="twa twa-3x twa-angry"></span></a>');
	}
}

if(function_exists('likeUrl') === false){
	function likeUrl($likes, $postId){
		return '<a href="'.route('getAllLikes', ['postId' => $postId]).'" data-toggle="modal" data-target="#likeModal" class="like-popup">'.formatLikesContext($likes).'</a>';
	}
}

if(function_exists('formatLikesContext') === false){
	function formatLikesContext($likes){
		$str = '';
		$emotions = [];
		$count = $likes->count();
		$cCount = 0;
		foreach ($likes as $key => $like) {
			if($count > 2){
				if($cCount < 2){
					if($cCount == 1) {
						$str .= $like->user->name.' ';
					}
					else{
						$str .= $like->user->name.' ,';							
					}			
				}
				else{
					$str .= trans('app.and_others', ['count' => $count - 2]);
					break;
				}
			}
			else{
				if($cCount < $count-1) {
					$str .= $like->user->name.' and ';
				}
				else{
					$str .= $like->user->name.' ';							
				}
			}

			$cCount++;
		}
		foreach ($likes as $key => $like) {
			if(in_array($like->type, $emotions) === false) array_push($emotions, $like->type);
		}
		$emotionIcon = '';
		$emotionCount = 1;
		foreach ($emotions as $key => $emotion) {
			$class = '';
			if($emotionCount > 1) $class= 'next-emotions';
			if($emotion === 'like'){
				$emotionIcon .= '<span class="twa-thumbsup-small btn-primary glyphicon glyphicon-thumbs-up '.$class.'"></span>';
			}
			else{
				$emotionIcon .= '<span class="twa twa-1x twa-'.$emotion.' '.$class.'"></span>';
			}
			$emotionCount++;
		}

		$str = $emotionIcon.$str;

		return $str;
	}
}

if(function_exists('formatTagsContext') === false){
	function formatTagsContext($tags){
		$str = '';
		if(empty($tags) === false){
			$cCount = 0;
			$friendNames = explode(',', $tags);
			$count = count($friendNames);
			foreach ($friendNames as $key => $friendName) {
				if($count > 2){
					if($cCount < 2){
						if($cCount == 1) {
							$str .= $friendName.' ';
						}
						else{
							$str .= $friendName.' ,';							
						}			
					}
					else{
						$str .= trans('app.and_others', ['count' => $count - 2]);
						break;
					}
				}
				else{
					if($cCount+1 < $count) {
						$str .= $friendName.' '.trans('app.and').' ';
					}
					else{
						$str .= $friendName.' ';							
					}
				}
				$cCount++;		
			}
		}

		return $str;
	}
}

if(function_exists('formatFriendTagsContext') === false){
	function formatFriendTagsContext($friendNames){
		$str = '';
		if(empty($friendNames) === false){
			$cCount = 0;
			$count = $friendNames->count();
			foreach ($friendNames as $key => $friendName) {
				if($count > 2){
					if($cCount < 2){
						if($cCount == 1) {
							$str .= '<a href="'.route('userProfile', ['username' => $friendName->friend->username]).'" data-toggle="popover" data-title="'. $friendName->friend->name.'" data-placement="right" data-content="'.getProfileHover($friendName).'">'.$friendName->friend->name.'</a> ';
						}
						else{
							$str .= '<a href="'.route('userProfile', ['username' => $friendName->friend->username]).'" data-toggle="popover" data-title="'. $friendName->friend->name.'" data-placement="right" data-content="'.getProfileHover($friendName).'">'.$friendName->friend->name.'</a>, ';							
						}			
					}
					else{
						$str .= trans('app.and_others', ['count' => $count - 2]);
						break;
					}
				}
				else{
					if($cCount+1 < $count) {
						$str .= '<a href="'.route('userProfile', ['username' => $friendName->friend->username]).'" data-toggle="popover" data-title="'.$friendName->friend->name.'" data-placement="right" data-content="'.getProfileHover($friendName).'">'.$friendName->friend->name.'</a> '.trans('app.and').' ';
					}
					else{
						$str .= '<a href="'.route('userProfile', ['username' => $friendName->friend->username]).'" data-toggle="popover" data-title="'. $friendName->friend->name.'" data-placement="right" data-content="'.getProfileHover($friendName).'">'.$friendName->friend->name.'</a> ';							
					}
				}
				$cCount++;		
			}
		}

		return $str;
	}
}

if(function_exists('getProfileHover') === false){
	function getProfileHover($friendName)
	{
		return "&lt;div class=&quot;media profile-hover-card&quot;&gt;
			&lt;div class=&quot;media-left&quot;&gt;
				&lt;a href=&quot;".route('userProfile', ['username'=> $friendName->friend->username])."&quot;&gt;
					&lt;img src=&quot;".url(getImageUrl($friendName->friend->profilePicture, "medium","User"))."&quot; class=&quot;media-object&quot;&gt; 
				&lt;/a&gt;
			&lt;/div&gt;
			&lt;div class=&quot;media-body&quot;&gt; 
				&lt;h4 class=&quot;media-heading&quot;&gt;&lt;a href=&quot;".route('userProfile', ['username'=> $friendName->friend->username])."&quot;&gt;".$friendName->friend->name."&lt;/a&gt;&lt;/h4&gt;".limitText($friendName->friend->profile->about, 30)."
			&lt;/div&gt;
		&lt;/div&gt;";
	}
}

if(function_exists('getLikeText') === false){
	function getLikeText($update){
		$like = $update->getCurrentPostLike($update->post_id);
		$type = empty($like->type) === false ? $like->type : 'new';
		return getLikeTextByType($type);
	}
}

if(function_exists('getLikeTextByType') === false){
	function getLikeTextByType($type){
		$str = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>';
		switch (strtolower($type)) {
			case 'like':
				$str = '<i class="twa-thumbsup-small btn-primary glyphicon glyphicon-thumbs-up"></i>';
				$str .= trans('app.like');
				break;
			case 'haha':
				$str = '<i class="twa twa-1x twa-haha"></i>';
				$str .= trans('app.haha');
				break;
			case 'wow':
				$str = '<i class="twa twa-1x twa-wow"></i>';
				$str .= trans('app.wow');
				break;
			case 'sad':
				$str = '<i class="twa twa-1x twa-sad"></i>';
				$str .= trans('app.sad');
				break;
			case 'angry':
				$str = '<i class="twa twa-1x twa-angry"></i>';
				$str .= trans('app.angry');
				break;				
			default:
				$str .= trans('app.like');
				break;
		}

		return $str;
	}
}

if(function_exists('getNotificationTextByType') === false){
	function getNotificationTextByType($type){
		$str = '';
		switch (strtolower($type)) {
			case 'like':
				$str .= trans('app.noti_like');
				break;
			case 'comment':
				$str .= trans('app.noti_comment');
				break;
			case 'friendtag':
				$str .= trans('app.noti_friend_tag');
				break;
			case 'share':
				$str .= trans('app.noti_shared');
				break;
			case 'confirmfriend':
				$str .= trans('app.noti_confirm_friend');
				break;
			default:
				break;
		}

		return $str;
	}
}

if(function_exists('limitText') === false){
	function limitText($text, $limit) {
		$text = strip_tags($text);
      	if (str_word_count($text, 0) > $limit) {
          	$words = str_word_count($text, 2);
          	$pos = array_keys($words);
          	$text = substr($text, 0, $pos[$limit]) . '...';
      	}

      return $text;
    }
}

if(function_exists('formAdminSelectOption') === false){
	function formAdminSelectOption($inputs) {
		$res = [];
		if(empty($inputs) === false){
			$datas = explode(',', $inputs);
			foreach ($datas as $key => $data) {
				$res[$data] = trans('app.'.$data);
			}
		}

      return $res;
    }
}

if(function_exists('getUpdateById') === false){
	function getUpdateById($id) {
		return \App\Entities\Update::find($id);
    }
}



