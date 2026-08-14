<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\PaymentWebhook;
use App\Models\Promotion;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\RoomTypeImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HotelBookingSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Pengguna dummy.
         */

        User::factory()->create([
            'name' => 'User Staff',
            'username' => 'staff',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '081234567892',
            'email_verified_at' => now(),
            'gender' => 'female',
        ]);


        $admin = User::factory()->create([
            'name' => 'Mario Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'email_verified_at' => now(),
            'gender' => 'male',
        ]);

        $customer = User::factory()->create([
            'name' => 'User Customer',
            'username' => 'customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567893',
            'email_verified_at' => now(),
            'gender' => 'male',
        ]);

        /*
         * Hotel dummy.
         */
        $hotel = Hotel::create([
            'name' => 'Nusantara Grand Hotel',
            'slug' => 'nusantara-grand-hotel',
            'description' => 'Hotel nyaman di pusat Jakarta dengan fasilitas lengkap.',
            'address' => 'Jl. Merdeka No. 10',
            'city' => 'Jakarta Pusat',
            'province' => 'DKI Jakarta',
            'postal_code' => '10110',
            'phone' => '0215551234',
            'email' => 'reservation@nusantarahotel.test',
            'latitude' => -6.1753924,
            'longitude' => 106.8271528,
            'check_in_time' => '14:00',
            'check_out_time' => '12:00',
            'is_active' => true,
        ]);

        /*
         * Fasilitas.
         */
        $wifi = Amenity::create([
            'name' => 'Wi-Fi Gratis',
            'icon' => 'wifi',
        ]);

        $ac = Amenity::create([
            'name' => 'AC',
            'icon' => 'air-conditioner',
        ]);

        $tv = Amenity::create([
            'name' => 'Smart TV',
            'icon' => 'tv',
        ]);

        $breakfast = Amenity::create([
            'name' => 'Sarapan',
            'icon' => 'utensils',
        ]);

        $bathtub = Amenity::create([
            'name' => 'Bathtub',
            'icon' => 'bath',
        ]);

        $minibar = Amenity::create([
            'name' => 'Minibar',
            'icon' => 'glass',
        ]);

        /*
         * Tipe kamar Standard.
         */
        $standard = RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Standard Room',
            'slug' => 'standard-room',
            'description' => 'Kamar nyaman untuk satu atau dua orang.',
            'capacity_adults' => 2,
            'capacity_children' => 1,
            'base_price' => 450000,
            'size_sqm' => 24,
            'bed_type' => 'Queen Bed',
            'is_active' => true,
        ]);

        $standard->amenities()->attach([
            $wifi->id,
            $ac->id,
            $tv->id,
        ]);

        /*
         * Tipe kamar Deluxe.
         */
        $deluxe = RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Deluxe Room',
            'slug' => 'deluxe-room',
            'description' => 'Kamar luas dengan pemandangan kota.',
            'capacity_adults' => 2,
            'capacity_children' => 2,
            'base_price' => 750000,
            'size_sqm' => 32,
            'bed_type' => 'King Bed',
            'is_active' => true,
        ]);

        $deluxe->amenities()->attach([
            $wifi->id,
            $ac->id,
            $tv->id,
            $breakfast->id,
            $minibar->id,
        ]);

        /*
         * Tipe kamar Suite.
         */
        $suite = RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Executive Suite',
            'slug' => 'executive-suite',
            'description' => 'Suite premium dengan ruang tamu terpisah.',
            'capacity_adults' => 3,
            'capacity_children' => 2,
            'base_price' => 1500000,
            'size_sqm' => 55,
            'bed_type' => 'King Bed',
            'is_active' => true,
        ]);

        $suite->amenities()->attach([
            $wifi->id,
            $ac->id,
            $tv->id,
            $breakfast->id,
            $bathtub->id,
            $minibar->id,
        ]);

        /*
         * Kamar fisik.
         */
        $standard101 = Room::create([
            'room_type_id' => $standard->id,
            'room_number' => '101',
            'floor' => '1',
            'status' => 'available',
        ]);

        Room::create([
            'room_type_id' => $standard->id,
            'room_number' => '102',
            'floor' => '1',
            'status' => 'available',
        ]);

        Room::create([
            'room_type_id' => $standard->id,
            'room_number' => '103',
            'floor' => '1',
            'status' => 'maintenance',
            'notes' => 'Perbaikan AC.',
        ]);

        $deluxe201 = Room::create([
            'room_type_id' => $deluxe->id,
            'room_number' => '201',
            'floor' => '2',
            'status' => 'available',
        ]);

        Room::create([
            'room_type_id' => $deluxe->id,
            'room_number' => '202',
            'floor' => '2',
            'status' => 'available',
        ]);

        $suite301 = Room::create([
            'room_type_id' => $suite->id,
            'room_number' => '301',
            'floor' => '3',
            'status' => 'available',
        ]);

        /*
         * Gambar tipe kamar.
         */
        RoomTypeImage::create([
            'room_type_id' => $standard->id,
            'image_path' => 'room-types/standard-1.jpg',
            'caption' => 'Standard Room',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        RoomTypeImage::create([
            'room_type_id' => $deluxe->id,
            'image_path' => 'room-types/deluxe-1.jpg',
            'caption' => 'Deluxe Room',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        RoomTypeImage::create([
            'room_type_id' => $suite->id,
            'image_path' => 'room-types/suite-1.jpg',
            'caption' => 'Executive Suite',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        /*
         * Promo.
         */
        $promotion = Promotion::create([
            'code' => 'WELCOME10',
            'name' => 'Diskon Pengguna Baru',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'minimum_amount' => 500000,
            'maximum_discount' => 150000,
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->addMonths(3),
            'usage_limit' => 100,
            'used_count' => 1,
            'is_active' => true,
        ]);

        /*
         * Booking pengguna login.
         * Deluxe 2 malam:
         * 750.000 x 2 = 1.500.000
         * Diskon 10% maksimal 150.000
         * Grand total = 1.350.000
         */
        $userBooking = Booking::create([
            'booking_code' => 'HTL-USER-0001',
            'user_id' => $customer->id,
            'hotel_id' => $hotel->id,
            'promotion_id' => $promotion->id,
            'guest_name' => $customer->name,
            'guest_email' => $customer->email,
            'guest_phone' => $customer->phone,
            'guest_identity_number' => '3173000000000001',
            'check_in_date' => now()->addDays(7)->toDateString(),
            'check_out_date' => now()->addDays(9)->toDateString(),
            'total_nights' => 2,
            'subtotal' => 1500000,
            'tax_amount' => 0,
            'service_amount' => 0,
            'discount_amount' => 150000,
            'grand_total' => 1350000,
            'currency' => 'IDR',
            'booking_status' => 'confirmed',
            'payment_status' => 'paid',
            'special_request' => 'Kamar non-smoking.',
            'confirmed_at' => now(),
        ]);

        $userBooking->items()->create([
            'room_type_id' => $deluxe->id,
            'room_id' => $deluxe201->id,
            'room_name' => $deluxe->name,
            'adults' => 2,
            'children' => 1,
            'price_per_night' => 750000,
            'total_nights' => 2,
            'subtotal' => 1500000,
        ]);

        $paidPayment = Payment::create([
            'booking_id' => $userBooking->id,
            'payment_reference' => Str::uuid(),
            'gateway' => 'midtrans',
            'gateway_transaction_id' => 'MIDTRANS-DUMMY-0001',
            'payment_method' => 'bank_transfer',
            'amount' => 1350000,
            'currency' => 'IDR',
            'status' => 'paid',
            'checkout_url' => 'https://example.test/payment/dummy-0001',
            'gateway_response' => [
                'transaction_status' => 'settlement',
                'fraud_status' => 'accept',
            ],
            'paid_at' => now(),
        ]);

        PaymentWebhook::create([
            'payment_id' => $paidPayment->id,
            'gateway' => 'midtrans',
            'event_id' => 'MIDTRANS-EVENT-DUMMY-0001',
            'event_type' => 'payment.settlement',
            'payload' => [
                'order_id' => $userBooking->booking_code,
                'transaction_status' => 'settlement',
                'gross_amount' => '1350000.00',
            ],
            'signature_valid' => true,
            'processed' => true,
            'processed_at' => now(),
        ]);

        /*
         * Booking guest.
         * user_id sengaja NULL.
         */
        $guestBooking = Booking::create([
            'booking_code' => 'HTL-GUEST-0001',
            'user_id' => null,
            'hotel_id' => $hotel->id,
            'promotion_id' => null,
            'guest_name' => 'Siti Aminah',
            'guest_email' => 'siti@example.test',
            'guest_phone' => '081355555555',
            'check_in_date' => now()->addDays(14)->toDateString(),
            'check_out_date' => now()->addDays(15)->toDateString(),
            'total_nights' => 1,
            'subtotal' => 450000,
            'tax_amount' => 0,
            'service_amount' => 0,
            'discount_amount' => 0,
            'grand_total' => 450000,
            'currency' => 'IDR',
            'booking_status' => 'pending',
            'payment_status' => 'pending',
            'special_request' => 'Check-in diperkirakan pukul 18:00.',
            'expires_at' => now()->addHours(24),
        ]);

        $guestBooking->items()->create([
            'room_type_id' => $standard->id,
            'room_id' => $standard101->id,
            'room_name' => $standard->name,
            'adults' => 2,
            'children' => 0,
            'price_per_night' => 450000,
            'total_nights' => 1,
            'subtotal' => 450000,
        ]);

        Payment::create([
            'booking_id' => $guestBooking->id,
            'payment_reference' => Str::uuid(),
            'gateway' => 'midtrans',
            'gateway_transaction_id' => null,
            'payment_method' => null,
            'amount' => 450000,
            'currency' => 'IDR',
            'status' => 'pending',
            'checkout_url' => 'https://example.test/payment/dummy-0002',
            'gateway_response' => [
                'transaction_status' => 'pending',
            ],
            'expired_at' => now()->addHours(24),
        ]);
    }
}
