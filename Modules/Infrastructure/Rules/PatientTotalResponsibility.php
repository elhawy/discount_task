<?php

namespace Modules\Infrastructure\Rules;

use Illuminate\Contracts\Validation\Rule;

class PatientTotalResponsibility implements Rule
{

    private $deductibleAmount ;
    private $coInsurance ;

    /**
     * Create a new rule instance.
     *
     * @param $deductibleAmount
     * @param $coInsurance
     */
    public function __construct($deductibleAmount,$coInsurance)
    {
        $this->deductibleAmount = $deductibleAmount;
        $this->coInsurance = $coInsurance;
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
        $deductibleAmount = $this->deductibleAmount? $this->deductibleAmount : 0;
        $coInsurance = $this->coInsurance? $this->coInsurance : 0;

        if( is_numeric($deductibleAmount) && is_numeric($coInsurance)){
            return $value == $deductibleAmount + $coInsurance ;
    }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Patient Responsibility Calculation Error.';
    }
}
