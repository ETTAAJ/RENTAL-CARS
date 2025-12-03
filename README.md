# Car Rental Website

A complete PHP web system for a car rental website with admin panel, booking system, and WhatsApp integration.

## Features

- **Admin Panel**: Full CRUD operations for managing cars
- **Homepage**: Display all cars in a responsive grid with discount highlighting
- **Car Detail Page**: View car details and book a car
- **Booking System**: Send bookings directly via WhatsApp (no database storage)
- **Responsive Design**: Built with Bootstrap 5
- **Security**: Uses prepared statements to prevent SQL injection

## Installation

1. **Database Setup**
   - Import the `database.sql` file into your MySQL database using phpMyAdmin or command line:
   ```sql
   mysql -u root -p < database.sql
   ```
   Or manually create the database and import the SQL file through phpMyAdmin.

2. **Configuration**
   - Open `config.php` and update the database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'car_rental');
   ```
   - Update the WhatsApp number in `config.php`:
   ```php
   define('WHATSAPP_NUMBER', '1234567890'); // Format: country code + number (no + or spaces)
   ```

3. **Web Server**
   - Place all files in your web server directory (e.g., `htdocs/rental-cars/` for XAMPP)
   - Ensure PHP and MySQL are running
   - Access the site at `http://localhost/rental-cars/`

## File Structure

```
rental-cars/
├── admin/
│   ├── index.php          # Admin panel - list all cars
│   ├── add-car.php        # Add new car
│   └── edit-car.php       # Edit existing car
├── config.php             # Database configuration
├── database.sql           # Database schema and sample data
├── header.php             # Reusable header
├── footer.php             # Reusable footer
├── index.php              # Homepage
├── car-detail.php         # Car detail page with booking form
├── process-booking.php    # Booking processor (redirects)
└── README.md              # This file
```

## Usage

### Admin Panel
1. Navigate to `admin/index.php` to manage cars
2. Click "Add New Car" to add a car
3. Click "Edit" to modify a car's details
4. Click "Delete" to remove a car
5. Set discount percentage to highlight cars on the homepage

### Booking a Car
1. Browse cars on the homepage
2. Click "View Details" on any car
3. Fill in the booking form with your details
4. Select start and end dates
5. Click "Send to WhatsApp" - booking details are sent directly via WhatsApp
6. No booking data is stored in the database

## Database Schema

### cars table
- `id` - Primary key
- `name` - Car name
- `image` - Image URL
- `price` - Price per day
- `discount` - Discount percentage (0-100)
- `description` - Car description
- `availability` - Available or unavailable
- `created_at` - Timestamp
- `updated_at` - Timestamp

## Security Features

- Prepared statements for all database queries
- Input validation and sanitization
- SQL injection prevention
- XSS protection using `htmlspecialchars()`

## Technologies Used

- PHP 7.4+
- MySQL
- Bootstrap 5
- Bootstrap Icons

## Notes

- Make sure to update the WhatsApp number in `config.php` before using the booking system
- Image URLs should be publicly accessible
- The system uses placeholder images if image URLs fail to load
- Discounted cars are highlighted with a red border on the homepage

## License

This project is open source and available for educational purposes.

# RENTAL-CARS
