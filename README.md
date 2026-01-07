# Real Shop - E-Commerce Shopping Cart

A simple e-commerce shopping cart application built with Laravel 12 and Livewire 3.

## Features

- 🛒 **Shopping Cart** - Add products, update quantities, remove items
- 📦 **Product Catalog** - Browse products with search and pagination
- �� **Checkout** - Simple checkout flow with order creation
- 📉 **Low Stock Alerts** - Automatic email notifications when stock runs low (via Queue)
- 📊 **Daily Sales Report** - Scheduled command for daily sales summary email
- 🔐 **Authentication** - User registration and login with Laravel Breeze

## Tech Stack

- **Framework:** Laravel 12
- **Frontend:** Livewire 3 + Tailwind CSS
- **Database:** MySQL
- **Queue:** Database driver
- **Mail:** SMTP (Mailtrap for development)

## Requirements

- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 18+

## Installation

\`\`\`bash
# Clone repository
git clone https://github.com/hijrahassalam/real-shop.git
cd real-shop

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_DATABASE=db_real_shop
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations and seed
php artisan migrate --seed

# Build assets
npm run build
\`\`\`

## Running the Application

\`\`\`bash
# Terminal 1 - Web Server
php artisan serve

# Terminal 2 - Queue Worker (for notifications)
php artisan queue:work
\`\`\`

Visit http://localhost:8000

### Test Accounts

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password | Admin |
| test@example.com | password | User |

## Key Features Explained

### 1. Shopping Cart
- Guest users redirected to login
- Authenticated users can add products to cart
- Real-time cart updates with Livewire
- Stock validation before checkout

### 2. Low Stock Notification
- Uses Eloquent Observer pattern
- Triggered when stock drops below threshold (default: 5)
- Queued job for async email delivery
- Configurable admin email via \`MAIL_ADMIN_ADDRESS\`

\`\`\`php
// Triggered automatically when stock falls below threshold
// See: app/Observers/ProductObserver.php
\`\`\`

### 3. Daily Sales Report
- Artisan command: \`php artisan report:daily-sales\`
- Scheduled to run daily at 08:00 AM
- Includes order summary, top products, order details

\`\`\`bash
# Run manually
php artisan report:daily-sales

# Run for specific date
php artisan report:daily-sales --date=2026-01-07
\`\`\`

## Project Structure

\`\`\`
app/
├── Console/Commands/
│   └── SendDailySalesReport.php    # Daily sales report command
├── Jobs/
│   └── SendLowStockNotification.php # Queued notification job
├── Livewire/
│   ├── Cart/
│   │   ├── CartIcon.php            # Cart icon with counter
│   │   └── CartPage.php            # Shopping cart page
│   ├── Checkout/
│   │   └── CheckoutPage.php        # Checkout flow
│   ├── Orders/
│   │   └── OrderList.php           # Order history
│   └── Products/
│       └── ProductList.php         # Product catalog
├── Mail/
│   ├── DailySalesReport.php        # Daily report email
│   └── LowStockAlert.php           # Low stock email
├── Models/
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   └── User.php
└── Observers/
    └── ProductObserver.php         # Stock change observer
\`\`\`

## Environment Variables

\`\`\`env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ADMIN_ADDRESS=admin@yourcompany.com

# Queue (use database for development)
QUEUE_CONNECTION=database
\`\`\`

## Scheduler Setup (Production)

Add to crontab:
\`\`\`bash
* * * * * cd /path/to/real-shop && php artisan schedule:run >> /dev/null 2>&1
\`\`\`

## Testing

\`\`\`bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=CartTest
\`\`\`

## Database Schema

\`\`\`
users
├── id, name, email, password

products
├── id, name, description, price, stock_quantity, low_stock_threshold, image, is_active

carts
├── id, user_id

cart_items
├── id, cart_id, product_id, quantity, price

orders
├── id, user_id, order_number, status, total

order_items
├── id, order_id, product_id, quantity, price
\`\`\`

## License

MIT License
