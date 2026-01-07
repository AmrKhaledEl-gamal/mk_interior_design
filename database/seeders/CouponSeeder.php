<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => '10% off for new customers',
                'type' => 'percentage',
                'value' => 10.00,
                'minimum_amount' => 50.00,
                'maximum_discount' => 20.00,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'SAVE25',
                'name' => 'Save $25',
                'description' => '$25 off orders over $200',
                'type' => 'fixed',
                'value' => 25.00,
                'minimum_amount' => 200.00,
                'usage_limit' => 50,
                'usage_limit_per_user' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping',
                'description' => '$15 shipping discount',
                'type' => 'fixed',
                'value' => 15.00,
                'minimum_amount' => 100.00,
                'usage_limit' => null, // Unlimited
                'usage_limit_per_user' => null, // Unlimited per user
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER20',
                'name' => 'Summer Sale',
                'description' => '20% off summer collection',
                'type' => 'percentage',
                'value' => 20.00,
                'minimum_amount' => 75.00,
                'maximum_discount' => 50.00,
                'usage_limit' => 200,
                'usage_limit_per_user' => 1,
                'starts_at' => now()->subWeek(),
                'expires_at' => now()->addMonths(1),
                'is_active' => true,
            ],
            [
                'code' => 'EXPIRED50',
                'name' => 'Expired Coupon',
                'description' => 'This coupon has expired (for testing)',
                'type' => 'percentage',
                'value' => 50.00,
                'minimum_amount' => 30.00,
                'usage_limit' => 10,
                'usage_limit_per_user' => 1,
                'starts_at' => now()->subMonths(2),
                'expires_at' => now()->subWeek(), // Expired
                'is_active' => true,
            ],
            [
                'code' => 'INACTIVE15',
                'name' => 'Inactive Coupon',
                'description' => 'This coupon is inactive (for testing)',
                'type' => 'percentage',
                'value' => 15.00,
                'minimum_amount' => 40.00,
                'usage_limit' => 25,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(1),
                'is_active' => false, // Inactive
            ],
            [
                'code' => 'VIP30',
                'name' => 'VIP Discount',
                'description' => '$30 off for VIP customers',
                'type' => 'fixed',
                'value' => 30.00,
                'minimum_amount' => 150.00,
                'usage_limit' => 20,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(4),
                'is_active' => true,
            ],
            [
                'code' => 'BULK5',
                'name' => 'Bulk Order Discount',
                'description' => '5% off orders over $500',
                'type' => 'percentage',
                'value' => 5.00,
                'minimum_amount' => 500.00,
                'maximum_discount' => 100.00,
                'usage_limit' => null,
                'usage_limit_per_user' => null,
                'starts_at' => now(),
                'expires_at' => null, // No expiry
                'is_active' => true,
            ]
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }

        $this->command->info('Sample coupons created successfully!');
    }
}
