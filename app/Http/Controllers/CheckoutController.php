<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;


class CheckoutController extends BaseController
{
    private $checkoutService;

    /**
     * CheckoutController constructor.
     * @param CheckoutServiceInterface $checkoutService
     */
    public function __construct(CheckoutServiceInterface $checkoutService)
    {
        $this->CheckoutService = $checkoutService;
    }


    /**
     * @param CheckoutIndexRequest $request
     * @return mixed
     */
    public function index(CheckoutIndexRequest $request)
    {
        $paramsKeys = ['perPage', 'referral_id', 'type'];
        $attributes = $request->only($paramsKeys);
        return $this->CheckoutService->CheckoutList($attributes, \Auth::user());
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return;
    }


    /**
     * @param ReferralCheckoutRequest $request
     * @param Checkout $Checkout
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ReferralCheckoutRequest $request)
    {
        $isReferralAttached = $this->CheckoutService->uploadReferralCheckout($request->all(), \Auth::user());

        if (!empty($isReferralAttached)) {
            return $this->created(["message" => trans('infrastructure::messages.success_attache_file'), 'data' => $isReferralAttached]);
        } else {
            return $this->errorResponse(500, trans('infrastructure::messages.failed_attach_file'));
        }
    }

    /**
     * download the specified Checkout.
     * @param int $id
     */
    public function download($CheckoutID)
    {
        return $this->CheckoutService->download($CheckoutID, \Auth::user());
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(DeleteCheckoutRequest $request)
    {
        $isDestroyed = $this->CheckoutService->destroy($request->ids, \Auth::user());
        if (!empty($isDestroyed)) {
            return $this->ok(["message" => trans('Checkout::messages.delete_attache_file_successfully')]);
        } else {
            return $this->errorResponse(500, trans('Checkout::messages.delete_attach_file_failed'));
        }
    }
}
