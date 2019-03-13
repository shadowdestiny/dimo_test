<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'birth_date'               => ['date', 'required'],
            'DUI'                      => ['string', 'required'],
            'address'                  => ['string', 'required'],
            'city_id'                  => ['integer', 'required'],
            'email'                    => ['email', 'required'],
            'alternative_number_phone' => ['string', 'required'],
        ];
    }
}
