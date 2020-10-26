<?php

namespace Modules\Infrastructure\Rules;

use Illuminate\Contracts\Validation\Rule;

class PlanPayment implements Rule
{
    private $subTotal ;
    private $deductibleAmount ;
    private $coInsurance ;

    /**
     * Create a new rule instance.
     *
     * @param $subTotal
     * @param $deductibleAmount
     * @param $coInsurance
     */
    public function __construct($subTotal,$deductibleAmount,$coInsurance)
    {
        $this->subTotal = $subTotal;
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
            if( is_numeric($this->subTotal) && is_numeric($deductibleAmount) && is_numeric($coInsurance)){
                $calculatedValue = $this->subTotal - $deductibleAmount - $coInsurance < 0 ? 0 : $this->subTotal - $deductibleAmount - $coInsurance;
            return $value == $calculatedValue ;
        }else{
                return false ;
            }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Plan Payment Calculation Error.';
    }
}
