<?php

namespace Bsmma\Http\Requests;

use Bsmma\Http\Requests\Request;

class DepositProfileRequest extends Request
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
            'firstname' => 'min:2|required',
            'lastname' => 'min:2|required',
            'address' => 'required|min:2',
            'address2' => 'min:2',
            'city' => 'required',
            'state' => 'max:2|required',
            'zipcode' => 'min:5|max:11|required',
            'merchant_id' => 'integer|not_in:0'
        ];
    }
}
