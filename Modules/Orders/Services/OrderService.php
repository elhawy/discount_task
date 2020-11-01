<?php

namespace Modules\Orders\Services;

use Modules\Discounts\Services\Interfaces\DiscountServiceInterface;
use Modules\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Orders\Services\Interfaces\OrderServiceInterface;
use Modules\Products\Services\Interfaces\ProductServiceInterface;

class OrderService implements OrderServiceInterface
{
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function createOrder(array $cart): array
    {
        \DB::beginTransaction();
        try {
            $orders = [];
            $orderPrice = 0;
            $productService = app(ProductServiceInterface::class);
            $discountService = app(DiscountServiceInterface::class);
            $countedProducts = array_count_values($cart);
            $productsCart = $productService->getProductsByNames($cart, ["discounts"]);
            $countedProductsIDS = [];
            foreach ($productsCart as $product) {
                $countedProductsIDS[$product->id] = $countedProducts[$product->name];
            }
            foreach ($productsCart as $key => $product) {
                $orders[$key]["price"] = $product->price;
                $orders[$key]["product_id"] = $product->id;
                $orders[$key]["qty"] = $countedProductsIDS[$product->id];
                $orders[$key]["discount_id"] = null;
                $discount = ($product->discounts->where('is_active', 1)->where('to', '>', \Carbon\Carbon::now())->first());
                if (!empty($discount)) {
                    $discountedPrice = $discountService->calculateDiscount($discount, $product->price, $countedProductsIDS, $product->id);
                    $orders[$key]["price"] = $discountedPrice;
                    $orders[$key]["discount_id"] = $discount->id;
                } else {
                    $orders[$key]["price"] = $countedProductsIDS[$product->id] * $product->price;
                }
                $orderPrice += $orders[$key]["price"];
            }
            $createdOrder = $this->orderRepository->create(["price" => $orderPrice]);
            $createdOrder->products()->attach($orders);
            \DB::commit();
            return ["order_id" => $createdOrder->id, "total_price" => $createdOrder->price];
        } catch (Exception $e) {
            \DB::rollBack();
        }
    }
}
