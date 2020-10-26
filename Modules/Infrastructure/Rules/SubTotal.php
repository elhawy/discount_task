<?php

namespace Modules\Infrastructure\Rules;


use Illuminate\Contracts\Validation\Rule;

class SubTotal implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $data ;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->data){
            return $value == array_sum($this->data);
        }
        return ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sub Total Calculation Error.';
    }
}
