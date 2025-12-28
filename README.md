# Lara-Cart: Simple E-Commerce Shopping Cart System

A simple e-commerce shopping cart system built with Laravel and Vue.js. Users can browse products, add them to a cart, update quantities, and remove items. The system includes low stock notifications and daily sales reports.

## Tech Stack

- **Backend:** Laravel
- **Frontend:** Vue.js
- **Styling:** Tailwind CSS
- **Version Control:** Git/GitHub

## Features

- User authentication (Laravel built-in)
- Product browsing
- Shopping cart functionality (add, update, remove items)
- Low stock notification via email (admin)
- Daily sales report via email (admin)
- Manual low stock notification command

## Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone https://github.com/sudhakar259/lara-cart.git
   cd lara-cart
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup:**
   - Configured database settings in `.env` (although it should not be in .gitignore)
   - Set up mail configuration for notifications

4. **Database migration and seeding:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets:**
   ```bash
   npm run build
   ```

6. **Start the application:**
   ```bash
   php artisan serve
   ```

## User Accounts

- **Regular User:** test@example.com / password
- **Admin User:** admin@example.com / password

## Testing

### Manual Low Stock Notification

To manually trigger low stock notifications for products below a specified threshold:

```bash
php artisan low-stock:notify 50
```

This command will:
- Query products with stock_quantity <= 50
- Dispatch LowStockNotificationJob for each low stock product
- Send email notifications to the admin (admin@example.com)

### Automated Testing

1. **Low Stock Notification Job:**
   - Ensure products with low stock trigger email notifications
   - Verify email content and recipient

2. **Daily Sales Report:**
   - Run the scheduled job manually: `php artisan schedule:run`
   - Check for daily sales report email sent to admin

3. **Shopping Cart Functionality:**
   - Login as test@example.com
   - Browse products
   - Add items to cart
   - Update quantities
   - Remove items
   - Verify cart persistence for authenticated user

### Queue and Mail Testing

- Start queue worker: `php artisan queue:work`
- Configure mail settings in `.env` (use Mailtrap or similar for testing)
- Test email sending for notifications and reports

## Key Requirements

- Each shopping cart is associated with the authenticated user
- Low stock notifications are sent via Laravel Jobs/Queues
- Daily sales reports are scheduled to run every evening
- Follow Laravel best practices and guidelines

## Project Structure

- `app/Models/` - Eloquent models (User, Product, Cart, etc.)
- `app/Jobs/` - Queued jobs (LowStockNotificationJob, DailySalesReportJob)
- `app/Mail/` - Mailable classes
- `app/Console/Commands/` - Custom commands (SendLowStockNotification)
- `resources/js/` - Vue.js components
- `routes/` - Web and API routes
