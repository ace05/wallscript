<?php
namespace App\Http\Controllers;

use Image;
use Response;

class ImageController extends Controller
{

	public function getImage($aspectRatio, $size, $filename) {

		// Make a new response out of the contents of the file
        // We refactor this to use the image resize function.
		// Set the response status code to 200 OK
		$response = Response::make(
			Image::resize($aspectRatio, $filename, $size),
			200
		);
        // Set the mime type for the response.
        // We now use the Image class for this also.
        $response->header(
            'content-type',
            Image::getMimeType($filename)
        );

		// We return our image here.
		return $response;
	}

}
?>