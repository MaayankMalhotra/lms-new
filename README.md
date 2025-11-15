# LMS Test Project

This repository contains a Laravel-based learning management system.

## Local setup

1. Install PHP 8.1+, Composer, Node.js, and npm.
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install JavaScript dependencies (optional for front-end assets):
   ```bash
   npm install
   ```
4. Copy the example environment file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Configure the database connection in `.env`. For a quick SQLite setup you can:
   ```bash
   touch database/database.sqlite
   ```
   Then set `DB_CONNECTION=sqlite` and `DB_DATABASE=` to the absolute path of the file. Alternatively, update `phpunit.xml` to use the in-memory SQLite configuration.

## Running tests

After completing the setup steps above, run the test suite with:

```bash
php artisan test
```

If you only need to run PHPUnit directly, you can also use:

```bash
./vendor/bin/phpunit
```

Both commands require the Composer dependencies to be installed so that `vendor/autoload.php` exists.

## Ecommerce storefront

The `/shop` namespace now behaves like an independent Amazon-style storefront branded as **Aromea Market** (perfumes, sneakers, ritual kits). The landing screen is a ChatGPT-style assistant powered by Google Gemini (if you provide an API key). Treat it as a separate project by serving the same Laravel app on another port:

```bash
php artisan serve --port=8001
# visit http://127.0.0.1:8001/shop
```

1. Run the latest migrations and seed the sample catalog:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
2. Populate these environment variables to wire payments + analytics:
   ```
   RAZORPAY_KEY=rzp_test_xxx
   RAZORPAY_SECRET=xxxx
   META_PIXEL_ID=1234567890
   GEMINI_API_KEY=your_google_generative_ai_key   # optional but required for conversational replies
   SHOP_CURRENCY=INR
   SHOP_TAX_RATE=0.18
   SHOP_SHIPPING_FLAT=0
   ```
3. Navigate the storefront at `/shop`, use `/cart` to review orders, and `/checkout` for the Razorpay/COD workflow. All key Meta events (`ViewContent`, `AddToCart`, `InitiateCheckout`, `Purchase`) are emitted automatically.

The `CategorySeeder` + `ProductSeeder` ship with D2C-friendly records (signature perfumes, motion sneakers, ritual kits). Update them or drop in your own SKUs—rerun `php artisan db:seed` anytime to refresh the catalog.

### Admin panel

- Visit `/admin/shop/products` to manage catalog items (create, edit, delete). This UI is unprotected right now—wrap the route group with your preferred auth middleware before deploying.
- Forms accept JSON for the `specifications` field (e.g., `{"Notes":["vanilla"],"Volume":"50ml"}`) and let you assign existing categories via multi-select.
- Customers can initiate refunds at `/refunds/request` using their order number and email. Track and update tickets in `/admin/shop/refunds`.
