<?php

if(function_exists('embedContent') === false){
	function embedContent($data)
	{
		if(empty($data) === false){
			$extData = json_decode(unserialize($data), true);
			switch ($extData['type']) {
				case 'video':
					if(empty($extData['code']) === false){
						return $extData['code'];
					}
					else{
						return '<iframe width="560" height="315" src="'.str_replace('watch', 'embed', $extData['url']).'" frameborder="0" allowfullscreen></iframe>';				
					}
					break;
				case 'rich':
					return $extData['code'];
					break;
				case 'photo':
					return '<img src="'.$extData['image'].'" class="img-responsive" />';
					break;				
				case 'link':
					return '<div class="media external-link"> 
								<div class="media-left"> 
									<a href="'.$extData['url'].'" target="_blank"> <img src="'.$extData['image'].'" class="media-object" width="200" height="200"> </a> 
								</div> 
								<div class="media-body"> 
									<h4 class="media-heading">
										<a href="'.$extData['url'].'" target="_blank">'.$extData['title'].'</a>										
									</h4>
									'.$extData['description'].'
								</div> 
							</div>';
					break;
				default:
					break;
			}
		}

		return;
	}
}