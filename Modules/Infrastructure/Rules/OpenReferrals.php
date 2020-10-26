<?php

namespace Modules\Infrastructure\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Referral\Entities\Referral;
use Modules\Referral\Entities\Lookups\ReferralActionsLookup;


class OpenReferrals implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $referralRepository;
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
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
        $referral = Referral::with('actions')->find($this->id);
        if($referral){
            $referralActions = $referral->actions->pluck('action')->toArray();
            if(!empty($referralActions)){
                foreach ($referralActions as $action){
                    if($action == ReferralActionsLookup::CLOSE || $action == ReferralActionsLookup::PERMANENTLY_CLOSE || $action == ReferralActionsLookup::REJECT){
                        return false;
                    }else{
                        return true;
                    }
                }
            }else {
                return true ;
            }
        }else{
            return true ;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected referral id is invalid.';
    }
}
