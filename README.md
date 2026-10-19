# Pet Shop

An online pet store built with PHP and MySQL: customers browse food and essentials for dogs, cats and fish, order with online or cash payment, and track their orders. Shop staff manage products, categories and orders from an admin panel.

## Features

**Shop**
- Home page, product listing with categories and search, product page with photo zoom and related products
- Cart with Flipkart-style delivery addresses: saved addresses, PIN code lookup that fills city/state, shows the areas in that PIN and an estimated delivery date
- Online (PayPal sandbox) or cash / offline payment
- Fully responsive: app-style bottom navigation, 2-column product grid, cart cards and a sticky "Place order" bar on phones; tuned for iPhone/iPad (notch-safe, no zoom on form fields), tablets and large screens
- Light and dark mode on every page; product photos get their white background removed so they blend into both themes

**Customer account**
- Register / login side by side, forgot password (verify with registered email + phone number)
- Dashboard, "My Orders" with status tracker and history, pay now, cancel before shipping, invoices

**Admin panel**
- Dashboard with revenue, order counts and charts
- Orders: status tabs, search, unpaid filter; one-click steps Pending → Confirmed → Shipped → Delivered with notes and a full history
- Cash orders: payment must be received before an order can be marked delivered
- Products, categories, product photos, users

## Tech

PHP 8, MySQL / MariaDB, AdminLTE 3 + Bootstrap (admin), HTML template + Bootstrap 5 (shop), jQuery, Chart.js.

## Setup (XAMPP)

1. Copy this folder into a folder your web server serves, e.g. `C:\xampp\htdocs\pet-shop`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Create a database called `nsp_pet` and import `database/pet_shop.sql`
   (phpMyAdmin → New → `nsp_pet` → Import), or from a terminal:
   ```bash
   mysql -u root -e "CREATE DATABASE nsp_pet"
   mysql -u root nsp_pet < database/pet_shop.sql
   ```
4. If your MySQL user or password isn't `root` with no password (or on a web host), copy `admin/db_config.example.php` to `admin/db_config.php` and fill in your database details.
5. Copy `admin/recovery_config.example.php` to `admin/recovery_config.php` and set your own long random key (used to reset the admin password).
6. Open `http://localhost/pet-shop/` in your browser.

To put it online for free, follow **[DEPLOY.md](DEPLOY.md)** (InfinityFree).

You can also run it with PHP's built-in server: `php -S localhost:8081` from this folder (MySQL must be running).

## Logins

| Who | Where | Login |
|---|---|---|
| Admin | `/admin/index.php` | `admin@admin.com` / `admin`: **change this after setup** (Admin → Profile) |
| Customer | `/admin/userLogin.php` | Create an account on the Login / Register page |

## Notes

- `database/pet_shop.sql` contains demo categories and products only: no customers or orders.
- Customer uploads (ID proofs) and secrets (`admin/mail.php`, `admin/recovery_config.php`) are kept out of git; see `.gitignore`.
- PayPal runs in **sandbox** mode (`payment/payment.php`).
- Estimated delivery dates are a simple rule based on distance from the shop; change the shop location and days at the top of `admin/address_lib.php`.
- Passwords are stored as plain text by the original login code. Hash them before using this for a real shop.
