<?php

namespace Sanjok\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdStoreRequest extends FormRequest
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
    public function rules()
    {
        return [
            'container_id' => 'required',
            'image' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'container_id.required' => 'Please select a container',
            'image' => 'image is required'
        ];
    }
}
