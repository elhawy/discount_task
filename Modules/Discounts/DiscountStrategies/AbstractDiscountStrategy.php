<?php

namespace Modules\Discounts\DiscountStrategies;

use Modules\Discounts\DiscountStrategies\AmountDiscountStrategy;
use Modules\Discounts\DiscountStrategies\DiscountStrategyInterface;
use Modules\Discounts\DiscountStrategies\PercentageDiscountStrategy;
use Modules\Discounts\DiscountStrategies\SpecialDiscountAmountStrategy;
use Modules\Discounts\DiscountStrategies\SpecialDiscountPercentageStrategy;
use Modules\Discounts\Entities\Discount;
use Modules\Discounts\Entities\Lookups\DiscountTypeLookups;

class AbstractDiscountStrategy
{
    protected $discount;
    protected $price;
    protected $countedCartProductIDS;
    protected $productID;

    public function __construct(Discount $discount, float $price, array $countedCartProductIDS, $productID)
    {
        $this->discount = $discount;
        $this->price = $price;
        $this->countedCartProductIDS = $countedCartProductIDS;
        $this->productID = $productID;
    }

    public function calculateDiscountQty()
    {
        return $this->countedCartProductIDS[$this->productID];
    }

    public static function createDiscountObject(Discount $discount, float $price, array $countedCartProductIDS, int $productID): DiscountStrategyInterface
    {
        if ($discount->type == DiscountTypeLookups::AMOUNT) {
            return new AmountDiscountStrategy($discount, $price, $countedCartProductIDS, $productID);
        } elseif ($discount->type == DiscountTypeLookups::PERCENTAGE) {
            return new PercentageDiscountStrategy($discount, $price, $countedCartProductIDS, $productID);
        } elseif ($discount->type == DiscountTypeLookups::SPECIAL_PERCENTAGE) {
            return new SpecialDiscountPercentageStrategy($discount, $price, $countedCartProductIDS, $productID);
        } elseif ($discount->type == DiscountTypeLookups::SPECIAL_AMOUNT) {
            return new SpecialDiscountAmountStrategy($discount, $price, $countedCartProductIDS, $productID);
        }
    }
}
