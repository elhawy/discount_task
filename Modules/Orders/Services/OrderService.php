<?php

namespace Modules\Orders\Services;

use Modules\Discounts\Services\Interfaces\DiscountServiceInterface;
use Modules\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Orders\Services\Interfaces\OrderServiceInterface;
use Modules\Products\Services\Interfaces\ProductServiceInterface;
use Modules\Infrastructure\Helpers\ConvertCurrencyConverterFetcher;

class OrderService implements OrderServiceInterface
{
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function createOrder(array $cart, $currency = 'USD'): array
    {
        $test = new ConvertCurrencyConverterFetcher(1, 'EGP');
        dd($test->convert());
        \DB::beginTransaction();
        try {
            $productsOrder = [];
            $order = [];
            $productService = app(ProductServiceInterface::class);
            $discountService = app(DiscountServiceInterface::class);
            $countedProducts = array_count_values($cart);
            $productsCart = $productService->getProductsByNames($cart, ["discounts"]);
            if ($productsCart->isEmpty()) {
                return [];
            }
            $countedProductsIDS = [];
            foreach ($productsCart as $product) {
                $countedProductsIDS[$product->id] = $countedProducts[$product->name];
            }
            foreach ($productsCart as $key => $product) {
                $order["sub_total"] = response["sub_total"] + $countedProductsIDS[$product->id] * $product->price;
                $productOrder[$key]["product_id"] = $product->id;
                $productOrder[$key]["qty"] = $countedProductsIDS[$product->id];
                $productOrder[$key]["discount_id"] = null;
                $discount = ($product->discounts->where('is_active', 1)->where('to', '>', \Carbon\Carbon::now())->first());
                if (!empty($discount)) {
                    $discountedPrice = $discountService->calculateDiscount($discount, $product->price, $countedProductsIDS, $product->id);
                    $productsOrder[$key]["discount_id"] = $discount->id;
                    $order["total"] = $order["total"] + $discountedPrice;
                } else {
                    $order["total"] = $order["total"] + $countedProductsIDS[$product->id] * $product->price;
                }
            }

            $createdOrder = $this->orderRepository->create($order);
            $createdOrder->products()->attach($productsOrder);
            \DB::commit();
            return ["order_id" => $createdOrder->id, "total_price" => $createdOrder->price];
        } catch (Exception $e) {
            \DB::rollBack();
        }
    }
}
