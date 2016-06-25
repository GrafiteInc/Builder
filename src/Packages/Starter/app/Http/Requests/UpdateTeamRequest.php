<?php

namespace {{App\}}Http\Requests;

use Auth;
use Route;
use {{App\}}Http\Requests\Request;
use {{App\}}Repositories\Team\Team;

class UpdateTeamRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $teamOwnerId = (int) Auth::user()->teams->find($this->segment(2))->user_id;
        $userId = (int) Auth::id();

        if ($teamOwnerId === $userId) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }
}
