<?php
namespace App\Libraries;

class CoverCrop
{
	
	private $handleimg;
	private $original = "";
	private $handlethumb;
	private $oldoriginal;

	public function openImg($file)
	{
		$this->original = $file;

		if($this->extension($file) == 'jpg' || $this->extension($file) == 'jpeg')
		{
			$this->handleimg = imagecreatefromjpeg($file);
		}
		elseif($this->extension($file) == 'png')
		{
			$this->handleimg = imagecreatefrompng($file);
		}
		elseif($this->extension($file) == 'gif')
		{
			$this->handleimg = imagecreatefromgif($file);
		}
		elseif($this->extension($file) == 'bmp')
		{
			$this->handleimg = imagecreatefromwbmp($file);
		}
	}

	public function getWidth()
	{
		return imageSX($this->handleimg);
	}
	
	public function getRightWidth($newheight)
	{
		$oldw = $this->getWidth();
		$oldh = $this->getHeight();
		
		$neww = ($oldw * $newheight) / $oldh;
		
		return $neww;
	}
		
	public function getHeight()
	{
		return imageSY($this->handleimg);
	}	

	public function getRightHeight($newwidth)
	{
		$oldw = $this->getWidth();
		$oldh = $this->getHeight();
		
		$newh = ($oldh * $newwidth) / $oldw;
		
		return $newh;
	}
	
	
	public function createThumb($newWidth, $newHeight)
	{
		$oldw = $this->getWidth();
		$oldh = $this->getHeight();
		
		$this->handlethumb = imagecreatetruecolor($newWidth, $newHeight);
		
		return imagecopyresampled($this->handlethumb, $this->handleimg, 0, 0, 0, 0, $newWidth, $newHeight, $oldw, $oldh);
	}
	
	public function cropThumb($width, $height, $x, $y)
	{
		$oldw = $this->getWidth();
		$oldh = $this->getHeight();
		
		$this->handlethumb = imagecreatetruecolor($width, $height);
		
		return imagecopy($this->handlethumb, $this->handleimg, 0, 0, $x, $y, $width, $height);
	}
	
	
	public function saveThumb($path, $qualityJpg = 100)
	{
		if($this->extension($this->original) == 'jpg' || $this->extension($this->original) == 'jpeg')
		{
			return imagejpeg($this->handlethumb, $path, $qualityJpg);
		}
		elseif($this->extension($this->original) == 'png')
		{
			return imagepng($this->handlethumb, $path);
		}
		elseif($this->extension($this->original) == 'gif')
		{
			return imagegif($this->handlethumb, $path);
		}
		elseif($this->extension($this->original) == 'bmp')
		{
			return imagewbmp($this->handlethumb, $path);
		}
	}
	
	public function closeImg()
	{
		imagedestroy($this->handlethumb);
		imagedestroy($this->handleimg);

		return true;
	}
	
	public function setThumbAsOriginal()
	{
		$this->oldoriginal = $this->handleimg;
		$this->handleimg = $this->handlethumb;
	}
	
	public function resetOriginal()
	{
		$this->handleimg = $this->oldoriginal;
	}
	
	/*
		Estrae l'estensione da un file o un percorso
	*/
	private function extension($file)
	{
		if($ext = pathinfo($file, PATHINFO_EXTENSION)){
			return strtolower(trim($ext));
		}
		else{
			$exts = explode(".", $file);			
			return strtolower(trim(array_pop($exts)));
		}
	}
	
}