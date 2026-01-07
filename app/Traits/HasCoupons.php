<?php

namespace App\Traits;

use App\Models\Coupon;
use App\Services\CouponService;

trait HasCoupons
{
    protected $appliedCoupon = null;
    protected $couponService;

    public function initializeCoupons()
    {
        $this->couponService = app(CouponService::class);
    }

    /**
     * Apply a coupon to the cart
     */
    public function applyCoupon(string $couponCode, array $cartItems = [])
    {
        $coupon = $this->couponService->findByCode($couponCode);

        if (!$coupon) {
            return [
                'success' => false,
                'message' => 'Coupon code not found.'
            ];
        }

        $result = $this->couponService->calculateDiscount($coupon, $cartItems, auth()->user());

        if ($result['success']) {
            $this->appliedCoupon = $coupon;
            session(['applied_coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'discount' => $result['discount']
            ]]);

            return [
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'discount' => $result['discount'],
                'coupon' => $coupon
            ];
        }

        return $result;
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon()
    {
        $this->appliedCoupon = null;
        session()->forget('applied_coupon');

        return [
            'success' => true,
            'message' => 'Coupon removed successfully.'
        ];
    }

    /**
     * Get currently applied coupon
     */
    public function getAppliedCoupon()
    {
        if ($this->appliedCoupon) {
            return $this->appliedCoupon;
        }

        $sessionCoupon = session('applied_coupon');
        if ($sessionCoupon) {
            $this->appliedCoupon = Coupon::find($sessionCoupon['id']);
            return $this->appliedCoupon;
        }

        return null;
    }

    /**
     * Calculate total discount
     */
    public function calculateDiscount(array $cartItems = [])
    {
        $coupon = $this->getAppliedCoupon();

        if (!$coupon) {
            return 0;
        }

        $result = $this->couponService->calculateDiscount($coupon, $cartItems, auth()->user());

        return $result['success'] ? $result['discount'] : 0;
    }

    /**
     * Get available coupons for current user
     */
    public function getAvailableCoupons()
    {
        return $this->couponService->getAvailableCoupons(auth()->user());
    }

    /**
     * Validate current coupon against cart
     */
    public function validateCurrentCoupon(array $cartItems = [])
    {
        $coupon = $this->getAppliedCoupon();

        if (!$coupon) {
            return ['valid' => true];
        }

        return $this->couponService->validateCoupon($coupon, auth()->user(), $cartItems);
    }
}
