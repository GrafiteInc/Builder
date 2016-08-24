<?php

namespace {{App\}}Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use {{App\}}Repositories\User\User;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required',
            'name' => 'required',
        ];
    }
}
