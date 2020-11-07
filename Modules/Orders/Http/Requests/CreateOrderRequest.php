<?php

namespace Modules\Orders\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cart' => 'array|required',
            'cart.*' => [
                'exists:Modules\Products\Entities\Product,name',
                'string',
                'required',
            ],
            'currency' => 'string'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
          'cart.*.exists' => 'Product Not Fount.'
        ];
    }
}
