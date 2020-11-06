<?php

namespace Modules\Orders\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Discounts\Entities\Lookups\DiscountTypeLookups;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $productsOrder = $this->productsOrder;
        $currency = $this->currency;
        $discounts = [];
        foreach ($productsOrder as $key => $productOrder) {
            $discount = $productOrder->discount;
            if (!empty($discount)) {
                $discounts[$key]["name"] = $productOrder->product->name;
                $discounts[$key]["discount_off"] = ($this->currency_ratio * $productOrder->discount_off) . $currency->symbol;
                if (in_array(
                    $discount->type,
                    [
                        DiscountTypeLookups::PERCENTAGE,
                        DiscountTypeLookups::SPECIAL_PERCENTAGE,
                    ]
                )) {
                    $discountSymbol = "%";
                } else {
                    $discountSymbol = '';
                }
                $discounts[$key]["discount_amount"] = $discount->amount . $discountSymbol;
                $discounts[$key]["discount_off"] = $currency->symbol . ($this->currency_ratio * $productOrder->discount_off);
            }
        }
        return [
            "Subtotal" => $currency->symbol.($this->currency_ratio * $this->sub_total),
            "Taxes" => $currency->symbol . ($this->currency_ratio * $this->taxes),
            "discount" => $discounts,
            "total" => $currency->symbol . ($this->currency_ratio * $this->total),

        ];
    }
}
