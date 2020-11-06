<?php

namespace Modules\Orders\Services;

use Modules\Orders\Exceptions\InvalidCurrencyException;
use Modules\Discounts\Services\Interfaces\DiscountServiceInterface;
use Modules\Infrastructure\Helpers\ConvertCurrencyConverterFetcher;
use Modules\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Orders\Services\Interfaces\OrderServiceInterface;
use Modules\Orders\Entities\Order;
use Modules\Products\Services\Interfaces\ProductServiceInterface;

class OrderService implements OrderServiceInterface
{
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function createOrder(array $cart, $currency): Order
    {
        \DB::beginTransaction();
        try {
            $productService = app(ProductServiceInterface::class);
            $discountService = app(DiscountServiceInterface::class);
            $countedProducts = array_count_values($cart);
            $productsCart = $productService->getProductsByNames($cart, ["discounts"]);
            if ($productsCart->isEmpty()) {
                return [];
            }
            $order["sub_total"] = 0;
            $order["total"] = 0;
            $productOrderQty = [];
            $productsOrder = [];
            foreach ($productsCart as $product) {
                $productOrderQty[$product->id] = $countedProducts[$product->name];
            }
            foreach ($productsCart as $key => $product) {
                $productsOrder[$key]["qty"] = $productOrderQty[$product->id];
                $productTotalPrice = ($productsOrder[$key]["qty"] * $product->price);
                $productsOrder[$key]["product_id"] = $product->id;
                $productsOrder[$key]["qty"] = $productOrderQty[$product->id];
                $productsOrder[$key]["discount_id"] = null;
                $productsOrder[$key]["discount_off"] = null;
                $discount = ($product->discounts->where('is_active', 1)->where('to', '>', \Carbon\Carbon::now())->first());
                $order["sub_total"] = $order["sub_total"] + ($productsOrder[$key]["qty"] * $product->price);
                if (!empty($discount)) {
                    $discountedPrice = $discountService->calculateDiscount($discount, $product->price, $productOrderQty, $product->id);
                    $productsOrder[$key]["discount_id"] = $discount->id;
                    $productsOrder[$key]["discount_off"] = $productTotalPrice - $discountedPrice;
                    $order["total"] = $order["total"] + $discountedPrice;
                } else {
                    $order["total"] = $order["total"] + $productTotalPrice;
                }
            }
            $currencyData = $this->convertOrderPrice(1, $currency);
            $order["taxes"] = config('orders.taxes') * $order["sub_total"];
            $order["total"] = $order["total"] + $order["taxes"];
            $order["currency_ratio"] = $currencyData['ratio'];
            $order["currency_id"] = $currencyData['id'];
            $createdOrder = $this->orderRepository->create($order);
            $createdOrder->products()->attach($productsOrder);
            //get all one time rather than eager load
            $this->orderRepository->where('id', $createdOrder->id)
                ->with(['products', 'productsOrder.discount', 'currency'])->first();
            \DB::commit();
            return $createdOrder;
        } catch (Exception $e) {
            \DB::rollBack();
        }
    }

    public function convertOrderPrice(int $amount, string $to) : array
    {
        $currency = $this->orderRepository->getCurrencyByCode($to);
        $from = config('orders.default_currency') ?? 'USD';
        if ($to == $from) {
            return ["id" => $currency->id ?? '', "ratio" => 1];
        }
        $currencyData = [];
        $currencyConverter = app(ConvertCurrencyConverterFetcher::class, ["amount" => $amount, "to" => $to, "from" => $from]);
        $currencyInfo = $currencyConverter->convert();
        if (!empty($currency) && !empty($currencyInfo['converted'])) {
            $currencyData["id"] = $currency->id;
            $currencyData["ratio"] = $currencyInfo["ratio"];
        } elseif (empty($currency) && !empty($currencyInfo['converted'])) {
            $currency = [
                "code" => $to, "name" => $currencyInfo['converted']["name"], "symbol" => $currencyInfo["converted"]["symbol"],
            ];
            $createdCurrency = $this->orderRepository->createCurrency($currency);
            $currencyData["id"] = $createdCurrency->id;
            $currencyData["ratio"] = $currencyInfo["ratio"];
        }
        if (empty($currencyData)) {
            throw new InvalidCurrencyException();
        }
        return $currencyData;
    }
}
