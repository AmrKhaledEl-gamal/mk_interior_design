# Coupon System Documentation

## Overview
This is a complete coupon management system for your Laravel application, featuring:
- Full admin panel for coupon management
- Frontend coupon application functionality
- Flexible discount types (fixed amount & percentage)
- Advanced validation and restrictions
- Usage tracking and analytics

## Features

### Admin Features
- ✅ Create, edit, delete, and manage coupons
- ✅ Set discount types (fixed amount or percentage)
- ✅ Configure usage limits (total and per-user)
- ✅ Set validity periods (start/end dates)
- ✅ Product and category restrictions
- ✅ Real-time coupon code generation
- ✅ Usage statistics and analytics
- ✅ Bulk operations and filtering

### Frontend Features
- ✅ Apply/remove coupons via AJAX
- ✅ Real-time coupon validation
- ✅ Automatic discount calculation
- ✅ Cart/checkout integration
- ✅ Responsive design
- ✅ User-friendly error handling

### Technical Features
- ✅ Comprehensive validation system
- ✅ Service-based architecture
- ✅ Database migrations and seeders
- ✅ Request validation
- ✅ Event-driven updates
- ✅ Security best practices

## Database Structure

### Coupons Table
```sql
- id (Primary Key)
- code (Unique coupon code)
- name (Display name)
- description (Optional description)
- type (fixed/percentage)
- value (Discount amount/percentage)
- minimum_amount (Minimum order requirement)
- maximum_discount (Max discount for percentage coupons)
- usage_limit (Total usage limit)
- usage_limit_per_user (Per-user limit)
- used_count (Times used)
- starts_at (Start date/time)
- expires_at (End date/time)
- is_active (Active status)
- applicable_products (JSON array of product IDs)
- applicable_categories (JSON array of category IDs)
- created_at/updated_at (Timestamps)
```

### Coupon Usages Table
```sql
- id (Primary Key)
- coupon_id (Foreign Key to coupons)
- user_id (Foreign Key to users, nullable)
- order_id (Order reference, nullable)
- discount_amount (Applied discount amount)
- used_at (Usage timestamp)
- created_at/updated_at (Timestamps)
```

## Installation & Setup

### 1. Files Created
The system has created the following files:

**Models:**
- `app/Models/Coupon.php`
- `app/Models/CouponUsage.php`

**Controllers:**
- `app/Http/Controllers/Admin/CouponController.php` (Admin panel)
- `app/Http/Controllers/CouponController.php` (Frontend API)

**Requests:**
- `app/Http/Requests/CouponRequest.php`

**Services:**
- `app/Services/CouponService.php`

**Traits:**
- `app/Traits/HasCoupons.php`

**Views:**
- `resources/views/admin/coupons/index.blade.php`
- `resources/views/admin/coupons/create.blade.php`
- `resources/views/admin/coupons/edit.blade.php`
- `resources/views/admin/coupons/show.blade.php`
- `resources/views/components/coupon-form.blade.php`

**Assets:**
- `public/assets/js/coupon-manager.js`
- `public/assets/css/coupon-styles.css`

**Database:**
- `database/migrations/2025_12_23_012755_create_coupons_table.php`
- `database/migrations/2025_12_23_014209_create_coupon_usages_table.php`
- `database/seeders/CouponSeeder.php`

### 2. Routes Added

**Admin Routes** (in `routes/admin.php`):
```php
// Coupon Management
Route::resource('coupons', CouponController::class);
Route::patch('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
Route::get('coupons/generate-code', [CouponController::class, 'generateCode'])->name('coupons.generate-code');
Route::post('coupons/validate-code', [CouponController::class, 'validateCode'])->name('coupons.validate-code');
Route::get('coupons/statistics', [CouponController::class, 'statistics'])->name('coupons.statistics');
```

**Frontend API Routes** (in `routes/web.php`):
```php
// Coupon API Routes (for AJAX)
Route::group(['prefix' => 'api/coupons', 'as' => 'api.coupons.'], function () {
    Route::post('/apply', [CouponController::class, 'apply'])->name('apply');
    Route::post('/remove', [CouponController::class, 'remove'])->name('remove');
    Route::get('/current', [CouponController::class, 'current'])->name('current');
    Route::post('/validate', [CouponController::class, 'validate'])->name('validate');
    Route::get('/available', [CouponController::class, 'available'])->name('available');
    Route::post('/apply-checkout', [CouponController::class, 'applyToCheckout'])->name('apply-checkout');
});
```

### 3. Admin Sidebar
The coupon section has been added to the admin sidebar under "E-Commerce Management":
- All Coupons
- Add Coupon

## Usage Guide

### Admin Panel Usage

#### 1. Access Coupons
Navigate to Admin Panel → E-Commerce Management → Coupons

#### 2. Create New Coupon
1. Click "Add New Coupon"
2. Fill in the basic information:
   - **Name**: Display name for the coupon
   - **Code**: Unique coupon code (auto-generated option available)
   - **Description**: Optional description
   - **Active Status**: Toggle to enable/disable

3. Configure discount settings:
   - **Type**: Fixed amount or Percentage
   - **Value**: Discount amount ($ for fixed, % for percentage)
   - **Minimum Amount**: Optional minimum order requirement
   - **Maximum Discount**: For percentage coupons only

4. Set usage limits:
   - **Total Usage Limit**: Maximum total uses
   - **Per User Limit**: Maximum uses per user
   
5. Configure validity period:
   - **Start Date**: When coupon becomes active
   - **End Date**: When coupon expires

6. Set restrictions (optional):
   - **Applicable Products**: Specific products only
   - **Applicable Categories**: Specific categories only

#### 3. Manage Existing Coupons
- **View**: Click the eye icon to see detailed coupon information
- **Edit**: Click the edit icon to modify coupon settings
- **Toggle Status**: Click the play/pause icon to activate/deactivate
- **Delete**: Click the trash icon to remove the coupon

#### 4. Filter and Search
- Search by code, name, or description
- Filter by status (Active, Inactive, Expired)
- Filter by type (Fixed, Percentage)

### Frontend Integration

#### 1. Add Coupon Form to Your Views
Include the coupon component in your cart or checkout page:

```blade
@include('components.coupon-form')
```

#### 2. Include Required Assets
Make sure to include the CSS and JavaScript files:

```blade
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/coupon-styles.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/coupon-manager.js') }}"></script>
@endpush
```

#### 3. Initialize Coupon Manager
The JavaScript component auto-initializes, but you can customize it:

```javascript
window.couponManager = new CouponManager({
    baseUrl: '/api/coupons',
    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    cartItems: [], // Pass your cart items here
    onSuccess: function(data) {
        // Custom success handler
        console.log('Coupon applied:', data);
    },
    onError: function(message) {
        // Custom error handler
        console.error('Coupon error:', message);
    }
});
```

### API Usage

#### Apply Coupon
```javascript
fetch('/api/coupons/apply', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        code: 'WELCOME10',
        cart_items: [
            {product_id: 1, price: 50.00, quantity: 2},
            {product_id: 2, price: 25.00, quantity: 1}
        ]
    })
})
```

#### Validate Coupon
```javascript
fetch('/api/coupons/validate', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        code: 'WELCOME10',
        cart_items: cartItems
    })
})
```

### Using the CouponService

#### In Your Controllers
```php
use App\Services\CouponService;

class CheckoutController extends Controller
{
    protected $couponService;
    
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    
    public function calculateTotal($cartItems, $couponCode = null)
    {
        $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        
        if ($couponCode) {
            $coupon = $this->couponService->findByCode($couponCode);
            if ($coupon) {
                $result = $this->couponService->calculateDiscount($coupon, $cartItems, auth()->user());
                if ($result['success']) {
                    $discount = $result['discount'];
                    $total = max(0, $subtotal - $discount);
                    return ['subtotal' => $subtotal, 'discount' => $discount, 'total' => $total];
                }
            }
        }
        
        return ['subtotal' => $subtotal, 'discount' => 0, 'total' => $subtotal];
    }
}
```

#### Using the HasCoupons Trait
```php
use App\Traits\HasCoupons;

class CartController extends Controller
{
    use HasCoupons;
    
    public function __construct()
    {
        $this->initializeCoupons();
    }
    
    public function applyCoupon(Request $request)
    {
        $cartItems = session('cart_items', []);
        $result = $this->applyCoupon($request->code, $cartItems);
        
        return response()->json($result);
    }
}
```

## Sample Coupons
The system includes sample coupons for testing:

1. **WELCOME10** - 10% off for new customers (min $50, max $20 discount)
2. **SAVE25** - $25 off orders over $200
3. **FREESHIP** - $15 shipping discount (min $100)
4. **SUMMER20** - 20% off summer collection (min $75, max $50 discount)
5. **EXPIRED50** - Expired coupon (for testing)
6. **INACTIVE15** - Inactive coupon (for testing)
7. **VIP30** - $30 off for VIP customers (min $150)
8. **BULK5** - 5% off orders over $500 (no expiry)

## Customization

### Custom Validation Rules
You can extend the `CouponService` to add custom validation:

```php
// In CouponService.php
public function validateCoupon(Coupon $coupon, ?User $user = null, array $cartItems = []): array
{
    $errors = [];
    
    // Add your custom validation logic here
    if ($user && $user->email === 'banned@example.com') {
        $errors[] = 'This coupon is not available for your account.';
    }
    
    // ... existing validation logic
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}
```

### Custom Discount Calculation
Override the discount calculation method:

```php
// In Coupon.php model
public function calculateDiscount(float $orderAmount): float
{
    // Add custom calculation logic
    $discount = parent::calculateDiscount($orderAmount);
    
    // Apply additional business rules
    if ($this->code === 'SPECIAL' && now()->isWeekend()) {
        $discount *= 1.5; // 50% bonus on weekends
    }
    
    return $discount;
}
```

### Frontend Customization
Modify the JavaScript component behavior:

```javascript
// Custom success handler
window.couponManager = new CouponManager({
    onSuccess: function(data) {
        // Custom animation or notification
        showNotification('Coupon applied successfully!', 'success');
        updateCartDisplay();
        triggerConfetti();
    }
});
```

## Security Considerations

1. **CSRF Protection**: All AJAX requests include CSRF tokens
2. **Input Validation**: All inputs are validated server-side
3. **SQL Injection Prevention**: Using Eloquent ORM and parameter binding
4. **Rate Limiting**: Consider adding rate limiting to API endpoints
5. **User Authorization**: Admin routes protected by authentication middleware

## Performance Optimization

1. **Database Indexing**: Indexes on `code`, `is_active`, and date fields
2. **Caching**: Consider caching frequently used coupons
3. **Eager Loading**: Coupon relationships are eager loaded when needed
4. **Query Optimization**: Efficient queries with minimal N+1 problems

## Troubleshooting

### Common Issues

1. **"Coupon not found" error**
   - Check if the coupon code exists in the database
   - Ensure the code is entered correctly (case-sensitive)

2. **"This coupon has expired" error**
   - Check the `expires_at` field in the database
   - Verify the server time is correct

3. **JavaScript not working**
   - Ensure CSRF token is present in the page head
   - Check browser console for JavaScript errors
   - Verify the CSS and JS files are loading correctly

4. **Discount not calculating correctly**
   - Check the coupon type (fixed vs percentage)
   - Verify minimum amount requirements
   - Check product/category restrictions

### Debug Mode
Enable debug mode in the JavaScript:

```javascript
window.couponManager = new CouponManager({
    debug: true, // Add this for debug logging
    // ... other options
});
```

## Support
If you need help or have questions about the coupon system:

1. Check the Laravel logs for server-side errors
2. Use browser developer tools to debug JavaScript issues
3. Verify database structure matches the migrations
4. Test with the provided sample coupons first

## Future Enhancements
Possible improvements you might consider:

- [ ] Coupon templates and bulk creation
- [ ] Advanced reporting and analytics
- [ ] Integration with email marketing
- [ ] Automatic coupon generation for abandoned carts
- [ ] Referral program coupons
- [ ] Social media sharing incentives
- [ ] Mobile app integration
- [ ] Multi-currency support
