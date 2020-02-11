<?php

namespace App\Libraries;

use Storage;

class FileUploader
{

	public function __construct()
	{
		$this->setConfig();
	}

    public function upload($file, $destinationPath)
    {
        $destinationPath = 'uploads/'.$destinationPath;
        $basePath = dirname($destinationPath);
        $fileName = basename($destinationPath);
        $response = $this->uploadToLocal($file, $basePath, $fileName);

        return $response;
    }

    public function save($fileContent, $destinationPath)
    {
        $destinationPath = 'uploads/'.$destinationPath;
        $response = $this->saveToLocal($fileContent, $destinationPath);

        return $response;
    }

    protected function saveToLocal($fileContent, $destination)
    {
        return Storage::disk('local')->put($destination, $fileContent);
    }

    protected function uploadToLocal($file, $destination, $fileName)
    {
        return $file->move(
            $destination, $fileName
        );
    }

	protected function setConfig()
	{
		config([
            'filesystems.disks.local.root' => public_path()
        ]);

        return;
	}


}