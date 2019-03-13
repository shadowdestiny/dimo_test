<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'first_name'               => ['string', 'required'],
            'last_name'                => ['string', 'required'],
            'birth_date'               => ['date', 'required'],
            'gender'                   => ['string', 'required'],
            'dui'                      => ['string', 'required', 'unique:clients_info,dui'],
            'address'                  => ['string', 'required'],
            'city_id'                  => ['integer', 'required'],
            'email'                    => ['email', 'required', 'unique:clients_info,email'],
            'alternative_number_phone' => ['string', 'required'],
        ];
    }
}
