<?php
namespace App\Libraries\Image\Facades;

use Illuminate\Support\Facades\Facade;

class Image extends Facade 
{

    protected static function getFacadeAccessor()
    {
        return new \App\Libraries\Image\Image;
    }

}