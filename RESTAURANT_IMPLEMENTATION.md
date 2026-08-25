# Restaurant implementation

The existing user migration/model is retained. The restaurant module begins at migration `2026_08_24_000001`; each restaurant table has exactly one create migration.

## Install

1. Back up any important database.
2. Run `composer install`.
3. Copy the restaurant and Midtrans variables from `.env.example` into `.env`.
4. Run `php artisan optimize:clear`.
5. Run `php artisan migrate`.
6. Run `php artisan db:seed`.
7. Check `php artisan route:list`.

Do not use `migrate:fresh` if user data must be retained.

## Midtrans Sandbox

In the Midtrans Sandbox dashboard open **Settings → Access Keys**, then configure:

```env
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_MERCHANT_ID=your-sandbox-merchant-id
MIDTRANS_CLIENT_KEY=SB-Mid-client-...
MIDTRANS_SERVER_KEY=SB-Mid-server-...
```

Never expose the Server Key in Blade, JavaScript, Git, screenshots, or chat. Set the Sandbox payment notification URL to:

```text
https://your-public-https-domain/webhooks/midtrans
```

Midtrans cannot call `127.0.0.1`; use a public HTTPS tunnel for local webhook tests.

## Test flow

1. `/menus`: add single menus and packages.
2. `/cart` then `/checkout`.
3. Delivery requires address and pay-now.
4. Room service requires room number and pay-now.
5. Normal dine-in requires pay-now.
6. `/table/scan/{qr_token}` enables pay-later for that valid table.
7. `/reservations/create` creates reservations.
8. `/management/restaurant/orders` processes kitchen status and cashier payment.
9. `/management/restaurant/reservations` manages reservations.

QR codes must contain `https://your-domain/table/scan/{qr_token}`. Re-running the table seeder preserves an existing QR token.

All user Blade pages extend `users.layouts.main`, render directly inside its `.container`, and use isolated selectors prefixed with `restaurant-menu-*`, `restaurant-cart-*`, `restaurant-checkout-*`, `restaurant-order-*`, `restaurant-payment-*`, or `restaurant-reservation-*`.
