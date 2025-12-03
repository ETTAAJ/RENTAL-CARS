# Car Rental Website

A complete PHP web system for a car rental website with admin panel, booking system, WhatsApp integration, currency conversion, and advanced filtering.

## Features

### Frontend Features
- **Homepage**: Display all cars in a responsive grid with discount highlighting
- **Car Detail Page**: View full car details with technical specifications and booking form
- **About Us Page**: Company information and features
- **Booking System**: Send bookings directly via WhatsApp (no database storage)
- **Currency Conversion**: Real-time price conversion (MAD, EUR, USD) with user preference saving
- **Dark/Light Mode**: Theme toggle with persistent user preference
- **Advanced Filtering**: Filter cars by name, fuel type, gear box, and sort by price (low to high/high to low)
- **Client-Side Filtering**: Instant filtering without page reload
- **Responsive Design**: Mobile-friendly interface with Bootstrap 5
- **Video Hero Section**: Engaging video background on homepage

### Admin Panel Features
- **Car Management**: Full CRUD operations for managing cars
- **Technical Specifications**: Manage car specifications (Gear Box, Fuel, Doors, Air Conditioner, Seats, Distance)
- **Image Upload**: Upload car images with automatic naming based on car name
- **Settings Management**:
  - Currency exchange rates (MAD to EUR, MAD to USD)
  - Default currency setting
  - WhatsApp number configuration
  - Logo upload and management
  - Social media links (Facebook, Twitter, Instagram, LinkedIn, YouTube)
- **Advanced Filtering**: Filter cars by availability, name, fuel type, gear box, and sort by price
- **Card-Based Layout**: Responsive card view for better mobile experience
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
│   ├── index.php          # Admin panel - list all cars with filters
│   ├── add-car.php        # Add new car with specifications
│   ├── edit-car.php       # Edit existing car with specifications
│   ├── settings.php       # Currency, logo, and social media settings
│   ├── logout.php         # Logout handler
│   ├── header.php         # Admin header
│   └── footer.php         # Admin footer
├── assets/
│   └── images/            # Car images and logo
├── vidio/                 # Video files
├── config.php             # Database configuration and helper functions
├── car_rental.sql         # Database schema and sample data
├── header.php             # Main site header (with sidebar, theme toggle, currency selector)
├── footer.php             # Main site footer (with social media links)
├── index.php              # Homepage with filters and video hero
├── car-detail.php         # Car detail page with booking form
├── about-us.php           # About Us page
├── process-booking.php    # Booking processor (redirects)
└── README.md              # This file
```

## Usage

### Admin Panel
1. Navigate to `admin/index.php` to manage cars
2. Use filters to find cars by:
   - Car name (dropdown)
   - Availability (All/Available/Unavailable)
   - Fuel type (Petrol/Diesel/Hybrid)
   - Gear box (Automat/Manual)
   - Sort by price (Default/Low to High/High to Low)
3. Click "Add New Car" to add a car with:
   - Car name, image, price, discount, description
   - Technical specifications (Gear Box, Fuel, Doors, Air Conditioner, Seats, Distance)
   - Image files are automatically named based on car name
4. Click "Edit" to modify a car's details and specifications
5. Click "Delete" to remove a car (also deletes associated image file)
6. Access Settings to manage:
   - Currency exchange rates
   - Default currency
   - WhatsApp number
   - Website logo
   - Social media links

### Booking a Car
1. Browse cars on the homepage
2. Use filters to find your preferred car:
   - Car name (dropdown)
   - Fuel type (Petrol/Diesel/Hybrid)
   - Gear box (Automat/Manual)
   - Sort by price (Default/Low to High/High to Low)
3. Click "View Details" on any car
4. Fill in the booking form with your details
5. Select start and end dates
6. Click "Send to WhatsApp" - booking details are sent directly via WhatsApp
7. No booking data is stored in the database

### Currency Conversion
- Select currency (MAD/EUR/USD) from the header dropdown
- Prices update instantly across the site
- Your preference is saved and remembered on next visit

### Theme Toggle
- Click the theme toggle button in the header to switch between dark and light mode
- Your preference is saved and remembered on next visit

## Database Schema

### cars table
- `id` - Primary key
- `name` - Car name (unique)
- `image` - Image path (stored in assets/images/)
- `price` - Price per day (DECIMAL)
- `discount` - Discount percentage (0-100)
- `description` - Car description (TEXT)
- `availability` - Available or unavailable (ENUM)
- `specifications` - Technical specifications (JSON)
  - `gear_box` - Automat or Manual
  - `fuel` - Petrol, Diesel, or Hybrid
  - `doors` - Number of doors
  - `air_conditioner` - Yes or No
  - `seats` - Number of seats
  - `distance` - Distance/range (e.g., "500 km")
- `created_at` - Timestamp
- `updated_at` - Timestamp

### settings table
- `id` - Primary key
- `setting_key` - Setting identifier (UNIQUE)
- `setting_value` - Setting value (TEXT)
- `updated_at` - Timestamp

**Default Settings:**
- `mad_to_eur` - Exchange rate from MAD to EUR
- `mad_to_usd` - Exchange rate from MAD to USD
- `default_currency` - Default currency (MAD/EUR/USD)
- `whatsapp_number` - WhatsApp number for bookings
- `logo_path` - Path to website logo
- `facebook_url` - Facebook page URL
- `twitter_url` - Twitter profile URL
- `instagram_url` - Instagram profile URL
- `linkedin_url` - LinkedIn page URL
- `youtube_url` - YouTube channel URL

## Security Features

- Prepared statements for all database queries
- Input validation and sanitization
- SQL injection prevention
- XSS protection using `htmlspecialchars()`
- File upload validation (type and size checking)
- Filename sanitization for uploaded images
- Duplicate car name prevention

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: 
  - Bootstrap 5
  - Bootstrap Icons
  - JavaScript (ES6+)
  - CSS Variables (for theming)
- **Features**:
  - JSON for specifications storage
  - LocalStorage for user preferences
  - WhatsApp API integration
  - Client-side filtering and sorting

## Key Features Details

### Technical Specifications
- Each car has customizable specifications stored as JSON
- Specifications include: Gear Box, Fuel Type, Doors, Air Conditioner, Seats, Distance
- Fully manageable through admin panel

### Currency System
- Real-time currency conversion
- Exchange rates configurable in admin settings
- User preference saved in browser localStorage
- Prices update instantly without page reload

### Image Management
- Images uploaded to `assets/images/` directory
- Filenames automatically generated from car name (sanitized)
- Old images automatically deleted when updating
- Supports JPEG, PNG, GIF, WebP formats (max 5MB)

### Filtering System
- Client-side filtering for instant results (no page reload)
- Filter by: Car name, Fuel type, Gear box, Availability (admin)
- Sort by: Price (Low to High, High to Low) or Default
- URL parameters updated for sharing/bookmarking

### Theme System
- Dark and Light mode support
- CSS variables for easy theme customization
- User preference saved in localStorage
- Smooth transitions between themes

## Notes

- Configure WhatsApp number, exchange rates, and logo in Admin Settings
- Image files are automatically named based on car name (e.g., "Toyota Camry" → `toyota-camry.png`)
- Social media links are optional - leave empty to hide icons in footer
- Currency conversion works in real-time across all pages
- Filtering works instantly without page reload for better user experience
- All car specifications are stored as JSON for flexibility

## License

This project is open source and available for educational purposes.

# RENTAL-CARS
