{{-- Coupon Application Component --}}
{{-- Include this in your cart or checkout page --}}

<div class="coupon-container">
    {{-- Applied Coupon Display --}}
    <div class="applied-coupon" style="display: none;">
        {{-- Content will be populated by JavaScript --}}
    </div>

    {{-- Coupon Input Form --}}
    <div class="coupon-input-group">
        <input type="text"
               class="coupon-input"
               placeholder="Enter coupon code"
               maxlength="50">
        <button type="button" class="apply-coupon-btn">
            Apply Coupon
        </button>
    </div>

    {{-- Validation Message --}}
    <div class="coupon-validation-message"></div>

    {{-- General Message --}}
    <div class="coupon-message"></div>

    {{-- Available Coupons (Optional) --}}
    @auth
    <div class="available-coupons" style="display: none;">
        <div class="available-coupons-title">Available Coupons</div>
        <div class="available-coupons-list">
            {{-- Will be populated by JavaScript --}}
        </div>
    </div>
    @endauth
</div>

{{-- Order Summary Integration --}}
<div class="order-summary">
    <div class="order-row">
        <span>Subtotal:</span>
        <span class="order-subtotal-amount">$0.00</span>
    </div>

    <div class="order-row coupon-discount" style="display: none;">
        <span>Coupon Discount:</span>
        <span class="coupon-discount-amount">-$0.00</span>
    </div>

    <div class="order-row total">
        <span><strong>Total:</strong></span>
        <span class="order-total-amount"><strong>$0.00</strong></span>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/coupon-styles.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/coupon-manager.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize coupon manager with custom options
            window.couponManager = new CouponManager({
                baseUrl: '{{ url("/api/coupons") }}',
                csrfToken: '{{ csrf_token() }}',
                cartItems: @json(session('cart_items', [])), // Pass your cart items here
                onSuccess: function(data) {
                    // Custom success handler
                    console.log('Coupon applied successfully:', data);

                    // Show/hide discount row
                    const discountRow = document.querySelector('.order-row.coupon-discount');
                    if (discountRow) {
                        discountRow.style.display = data.coupon ? 'flex' : 'none';
                    }

                    // You can trigger cart recalculation here
                    // updateCartTotals();
                },
                onError: function(message) {
                    // Custom error handler
                    console.error('Coupon error:', message);
                }
            });

            // Example of updating cart items when cart changes
            document.addEventListener('cartUpdated', function(e) {
                if (window.couponManager) {
                    window.couponManager.updateCartItems(e.detail.cartItems);
                }
            });
        });
    </script>
@endpush
