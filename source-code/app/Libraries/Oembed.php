<?php

namespace App\Libraries;

use Embed\Embed;

class Oembed
{
	public static $pattern = '/((((http|https|ftp|ftps)\:\/\/)|www\.)[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(\/\S*)?)/';

    public function processContent($content)
    {
        try{
            preg_match(self::$pattern, $content, $url);
            if(empty($url[0]) === false){
                $info = Embed::create($url[0]);
                return [
                    "title" => $info->title,
                    "description" => $info->description,
                    "url" => $info->url,
                    "type" => $info->type,
                    "tags" => $info->tags,
                    "images" => $info->images,
                    "image" => $info->image,
                    "imageWidth" => $info->imageWidth,
                    "imageHeight" => $info->imageHeight,
                    "code" => $info->code,
                    "width" => $info->width,
                    "height" => $info->height,
                    "aspectRatio" => $info->aspectRatio,
                    "authorName" => $info->authorName,
                    "authorUrl" => $info->authorUrl,
                    "providerName" => $info->providerName,
                    "providerUrl" => $info->providerUrl,
                    "providerIcons" => $info->providerIcons,
                    "providerIcon" => $info->providerIcon,
                    "publishedDate" => $info->publishedDate,
                    "license" => $info->license,
                    "linkedData" => $info->linkedData
                ];
            }
        }
        catch(\Exceptio $e){
            return [];
        }
    }
}
