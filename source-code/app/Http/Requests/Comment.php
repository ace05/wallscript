<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\Request as FRequest;

class Comment extends Request
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
        $file = $request->file('commentImage');
        if(empty($file) === false){
            return [];
        }

        return [
            'comment' => 'required'
        ];
    }
}
