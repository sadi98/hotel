<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RestaurantSettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan restoran.
     */
    public function edit(): View
    {
        $settings = RestaurantSetting::current();

        return view(
            'admin.restaurant.settings.edit',
            compact('settings')
        );
    }

    /**
     * Memperbarui pengaturan restoran.
     */
    public function update(Request $request): RedirectResponse
    {
        $settings = RestaurantSetting::current();

        $validated = $request->validate([
            'restaurant_name' => [
                'required',
                'string',
                'max:150',
            ],

            'currency_code' => [
                'required',
                'string',
                'max:10',
            ],

            'currency_symbol' => [
                'required',
                'string',
                'max:10',
            ],

            'is_service_charge_active' => [
                'nullable',
                'boolean',
            ],

            'service_charge_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'is_tax_active' => [
                'nullable',
                'boolean',
            ],

            'tax_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'maximum_discount_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'allow_dine_in_pay_later' => [
                'nullable',
                'boolean',
            ],

            'allow_delivery_pay_later' => [
                'nullable',
                'boolean',
            ],

            'allow_room_service_pay_later' => [
                'nullable',
                'boolean',
            ],

            'order_number_prefix' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
            ],

            'payment_number_prefix' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
            ],

            'reservation_number_prefix' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
            ],

            'default_reservation_duration' => [
                'required',
                'integer',
                'min:15',
                'max:1440',
            ],

            'default_preparation_time' => [
                'required',
                'integer',
                'min:1',
                'max:1440',
            ],
        ]);

        $validated['currency_code'] = strtoupper(
            $validated['currency_code']
        );

        $validated['order_number_prefix'] = strtoupper(
            $validated['order_number_prefix']
        );

        $validated['payment_number_prefix'] = strtoupper(
            $validated['payment_number_prefix']
        );

        $validated['reservation_number_prefix'] = strtoupper(
            $validated['reservation_number_prefix']
        );

        $validated['is_service_charge_active'] =
            $request->boolean('is_service_charge_active');

        $validated['is_tax_active'] =
            $request->boolean('is_tax_active');

        $validated['allow_dine_in_pay_later'] =
            $request->boolean('allow_dine_in_pay_later');

        $validated['allow_delivery_pay_later'] =
            $request->boolean('allow_delivery_pay_later');

        $validated['allow_room_service_pay_later'] =
            $request->boolean('allow_room_service_pay_later');

        $settings->update($validated);

        return back()->with(
            'success',
            'Pengaturan restoran berhasil diperbarui.'
        );
    }
}
