<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RestaurantSettingController extends Controller
{
    public function edit(): View
    {
        $settings = RestaurantSetting::current();

        return view(
            'admin.restaurant.settings.edit',
            compact('settings')
        );
    }

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
            'service_charge_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
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
            'order_number_prefix' => [
                'required',
                'string',
                'max:20',
            ],
            'payment_number_prefix' => [
                'required',
                'string',
                'max:20',
            ],
            'reservation_number_prefix' => [
                'required',
                'string',
                'max:20',
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

        $validated['restaurant_name'] = trim(
            $validated['restaurant_name']
        );

        $validated['currency_code'] = Str::upper(
            trim($validated['currency_code'])
        );

        $validated['currency_symbol'] = trim(
            $validated['currency_symbol']
        );

        $validated['order_number_prefix'] = $this->normalizePrefix(
            $validated['order_number_prefix']
        );

        $validated['payment_number_prefix'] = $this->normalizePrefix(
            $validated['payment_number_prefix']
        );

        $validated['reservation_number_prefix'] = $this->normalizePrefix(
            $validated['reservation_number_prefix']
        );

        $validated['is_service_charge_active'] = $request->boolean(
            'is_service_charge_active'
        );

        $validated['is_tax_active'] = $request->boolean(
            'is_tax_active'
        );

        $validated['allow_pay_later_dine_in'] = $request->boolean(
            'allow_pay_later_dine_in'
        );

        $validated['allow_pay_later_delivery'] = $request->boolean(
            'allow_pay_later_delivery'
        );

        $validated['allow_pay_later_room_service'] = $request->boolean(
            'allow_pay_later_room_service'
        );

        $settings->update($validated);

        return redirect()
            ->route('management.restaurant-settings.edit')
            ->with(
                'success',
                'Pengaturan restoran berhasil diperbarui.'
            );
    }

    private function normalizePrefix(string $prefix): string
    {
        return Str::upper(
            preg_replace(
                '/[^A-Za-z0-9]/',
                '',
                trim($prefix)
            ) ?: 'NUM'
        );
    }
}
