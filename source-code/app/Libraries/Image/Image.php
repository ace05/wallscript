<?php 
namespace App\Libraries\Image;

use App\Entities\Attachment;
use App\Libraries\CoverCrop;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class Image 
{

    protected $imagine;

    // We instantiate the Imagine library with Imagick or GD
    public function __construct($library = null)
    {
        if ( !$this->imagine) {
            if ( !$library and class_exists('Imagick')) {
                $this->imagine = new \Imagine\Imagick\Imagine();
            } else {
                $this->imagine = new \Imagine\Gd\Imagine();
            }
        }
    }

    public function resize($aspectRatio, $filename, $sizeString) {

        if(empty($filename)) {
            return;
        }
        $imageDetails = explode('.', $filename);

        if(empty($imageDetails[0]) || !is_numeric($imageDetails[0])) {
            return;
        }

        $attachment = Attachment::find($imageDetails[0]);

        if(is_null($attachment)) {
            return;
        }

        $imgPath = getImagePath($attachment);

        if(!File::isFile($imgPath)) {
            return;
        }
        
        $outputDir = dirname($imgPath);
        $outputDir = sprintf('%s/%s', $outputDir, 'thumb');
        $outputFile = $outputDir . '/' . $sizeString . '_' . $filename;

        if (File::isFile($outputFile)) {
            return File::get($outputFile);
        }

        $sizeArr = Config::get('site.images.sizes.' . $sizeString);
        $width = $sizeArr['width'];
        $height = $sizeArr['height'];

        $size = new \Imagine\Image\Box($width, $height);
        $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;

        if (!File::isDirectory($outputDir)) {
            File::makeDirectory($outputDir);
        }

        if(empty($aspectRatio) === false){
            $resize = $this->doCrop($imgPath, $outputFile, ['newWidth' => $width, 'newHeight' => $height]);
            return File::get($outputFile);
        }

        $resizeimg = $this->imagine->open($imgPath)
                ->thumbnail($size, $mode);
        $sizeR     = $resizeimg->getSize();
        $widthR    = $sizeR->getWidth();
        $heightR   = $sizeR->getHeight();

        if($sizeString === 'modelBox'){
            $palette = new \Imagine\Image\Palette\RGB();
            $color = $palette->color('#000', 100);
            $preserve  = $this->imagine->create($size, $color);
        }
        else{
            $preserve  = $this->imagine->create($size);
        }
        
        $startX = $startY = 0;
        if ( $widthR < $width ) {
            $startX = ( $width - $widthR ) / 2;
        }
        if ( $heightR < $height ) {
            $startY = ( $height - $heightR ) / 2;
        }
        
        $preserve->paste($resizeimg, new \Imagine\Image\Point($startX, $startY))
            ->save($outputFile, ['quality' => 90]);
       
        return File::get($outputFile);

    }

    public function doResize($imageLocation, $imageDestination, array $options = null)
    {
        $newWidth = $newHeight = 0;
        list($width, $height) = getimagesize($imageLocation);
        if(isset($options['newWidth']) || isset($options['newHeight']))
        {
            if(isset($options['newWidth']) && isset($options['newHeight']))
            {
                $newWidth = $options['newWidth'];
                $newHeight = $options['newHeight'];
            }

            else if(isset($options['newWidth']))
            {
                $deviationPercentage = (($width - $options['newWidth']) / (0.01 * $width)) / 100;

                $newWidth = $options['newWidth'];
                $newHeight = $height - ($height * $deviationPercentage);
            }

            else
            {
                $deviationPercentage = (($height - $options['newHeight']) / (0.01 * $height)) / 100;

                $newWidth = $width - ($width * $deviationPercentage);
                $newHeight = $options['newHeight'];
            }
        }

        else
        {
            // reduce image size up to 20% by default
            $reduceRatio = isset($options['reduceRatio']) ? $options['reduceRatio'] : 20;

            $newWidth = $width * ((100 - $reduceRatio) / 100);
            $newHeight = $height * ((100 - $reduceRatio) / 100);
        }
   
        $size = new \Imagine\Image\Box($newWidth, $newHeight);
        return $this->imagine->open($imageLocation)
                    ->resize($size)
                    ->save($imageDestination, array('quality' => 90));
    }


    /**
     * @param string $filename
     * @return string mimetype
     */
    public function getMimeType($filename) {
        if(empty($filename)) {
            return;
        }
        $imageDetails = explode('.', $filename);
        if(empty($imageDetails[0]) || !is_numeric($imageDetails[0])) {
            return;
        }
        $attachment = Attachment::find($imageDetails[0]);
        if(is_null($attachment)) {
            return;
        }
        $imgPath = getImagePath($attachment);
        if(!File::isFile($imgPath)) {
            return;
        }
        $fileInfo = pathinfo($filename);
        $file = new \Symfony\Component\HttpFoundation\File\File($imgPath);

        return $file->getMimeType();
    }

    public function doCrop($imageLocation, $imageDestination, array $options = null)
    {
        list($originalWidth, $originalHeight) = getimagesize($imageLocation);
        $cropWidth = $options['newWidth'];
        $cropHeight = $options['newHeight'];

        $centreX = round($originalWidth / 2);
        $centreY = round($originalHeight / 2);
        $cropWidthHalf  = round($cropWidth / 2);
        $cropHeightHalf = round($cropHeight / 2);

        $x1 = max(0, $centreX - $cropWidthHalf);
        $y1 = max(0, $centreY - $cropHeightHalf);


        $size = new \Imagine\Image\Box($cropWidth, $cropHeight);
        return $this->imagine->open($imageLocation)
                    ->crop(new \Imagine\Image\Point($x1, $y1), $size)
                    ->save($imageDestination, array('quality' => 90));
    }

    public function coverPhotoCrop($attachment, $sizeString, $pos)
    {
        if(is_null($attachment)) {
            return;
        }

        $imgPath = getImagePath($attachment);
        if(!File::isFile($imgPath)) {
            return;
        }
        
        $outputDir = dirname($imgPath);
        $outputDir = sprintf('%s/%s', $outputDir, 'thumb');
        $outputFile = $outputDir . '/' . $sizeString . '_' . $attachment->id.'.'.md5($attachment->filename).'.jpg';

        if (File::isFile($outputFile)) {
            return File::get($outputFile);
        }

        $sizeArr = Config::get('site.images.sizes.' . $sizeString);
        $width = $sizeArr['width'];
        $height = $sizeArr['height'];

        if (!File::isDirectory($outputDir)) {
            File::makeDirectory($outputDir);
        }

        $imagePath = getImagePath($attachment);
        $coverCrapper = app()->make(CoverCrop::class);
        $coverCrapper->openImg($imgPath);
        $newHeight = $coverCrapper->getRightHeight($width);            
        $coverCrapper->createThumb($width, $newHeight);
        $coverCrapper->setThumbAsOriginal();            
        $coverCrapper->cropThumb($width, $height, 0, $pos);
        $coverCrapper->saveThumb($outputFile);            
        $coverCrapper->resetOriginal();            

        return $coverCrapper->closeImg();
    }

}