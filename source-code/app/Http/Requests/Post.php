<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\Request as FRequest;

class Post extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(FRequest $request)
    {
        $images = $request->file('images');
        if(empty($images) === false){
            return [];
        }

        return [
            'message' => 'required'
        ];
    }
}
