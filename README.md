# Assisted Living Menu Generator

Professional PHP-based menu generation system for assisted living and senior care facilities.

## Features

✅ **Dynamic Menu Generation**
- Auto-generate weekly menus for senior residents
- 7-day weekly planning with customizable serving sizes
- Comprehensive recipe database with ingredients and instructions

✅ **Recipe Management**
- 25+ Soups
- 60+ Daily Specials (fish, poultry, beef, veal, seafood)
- Multiple salads and burger options
- Weekday, Saturday, and Sunday breakfast selections

✅ **Export Functionality**
- Export as Text format
- Export as CSV for spreadsheet apps
- Export as JSON for integration
- Print-ready formatting

✅ **SEO Optimized**
- Full SEO metadata and structured data
- Open Graph and Twitter Card support
- JSON-LD schema markup
- Responsive and accessible design

✅ **Senior-Friendly Features**
- Low-sodium recipes suitable for dietary restrictions
- Soft, easy-to-chew textures
- Detailed cooking notes and modifications
- Large, readable text and high contrast

## Installation

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher (optional, falls back to JSON storage)
- Apache with mod_rewrite enabled

### Setup

1. Clone/download the project:
```bash
cd /path/to/project
cp .env.example .env
```

2. Update `.env` with your database credentials (if using MySQL):
```
DB_HOST=your_db_host
DB_NAME=assisted_living_menu
DB_USER=db_user
DB_PASS=db_password
```

3. Create data directory for JSON storage (if not using MySQL):
```bash
mkdir -p data
chmod 755 data
```

4. Set proper permissions:
```bash
chmod 755 .
chmod 755 config templates classes
```

## Usage

### Web Access
Navigate to: `https://allaround.work/tools/menugen`

### Workflow
1. Select desired week using week picker
2. Choose serving size (10-100 residents)
3. Click "Generate Full Week Menu" to create menu
4. Use "Regenerate Single Day" to change any day
5. Export or print the generated menu

## Project Structure

```
assisted_living_menu_genorator/
├── index.php                 # Main application entry point
├── config/
│   ├── config.php           # Application configuration
│   └── database.php         # Database connection
├── classes/
│   ├── MenuGenerator.php    # Menu generation logic
│   ├── RecipeDatabase.php   # Recipe data management
│   └── ExportHandler.php    # Export functionality
├── templates/
│   ├── header.php           # HTML header with SEO
│   ├── home.php             # Main application UI
│   ├── footer.php           # HTML footer
│   └── 404.php              # 404 error page
├── data/
│   └── recipes.json         # Default recipe database
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── .htaccess                # Apache rewrite rules
├── web.config               # IIS configuration
└── .env.example             # Environment configuration template
```

## API Endpoints

All API calls use POST with `X-Requested-With: XMLHttpRequest` header and return JSON.

### Generate Weekly Menu
```javascript
POST /index.php
{
    "action": "generate_week",
    "week": "2026-W03",
    "serving_size": 25
}
```

### Regenerate Single Day
```javascript
POST /index.php
{
    "action": "regenerate_day",
    "day_index": 0
}
```

### Export Menu
```javascript
POST /index.php
{
    "action": "export",
    "format": "text|csv|json|pdf"
}
```

## SEO Features

- **Meta Tags**: Proper description, keywords, and author information
- **Open Graph**: Facebook and social media sharing optimization
- **JSON-LD Schema**: Structured data for search engines
- **Canonical URLs**: Proper URL management
- **Breadcrumbs**: Navigation structure
- **Mobile Responsive**: Mobile-first design
- **Fast Loading**: CSS inlining, optimized assets

## Security Features

- Input sanitization on all user inputs
- CSRF protection on forms
- Secure session handling
- SQL injection prevention with prepared statements
- XSS protection with proper HTML escaping
- Security headers (X-Content-Type-Options, X-Frame-Options, etc.)
- HTTPS enforcement via .htaccess

## Customization

### Adding Recipes
Edit `RecipeDatabase.php` `getDefaultRecipes()` method to add new recipes to soups, specials, salads, or burgers.

### Modifying Serving Sizes
Update `config/config.php` `serving_sizes` array:
```php
'serving_sizes' => [10, 15, 20, 25, 30, 40, 50, 75, 100],
```

### Dietary Restrictions
Add to recipes or enhance filtering logic:
```php
'dietary_restrictions' => ['vegetarian', 'vegan', 'gluten-free', 'dairy-free', 'low-sodium', 'pureed'],
```

## Database Schema (Optional)

If using MySQL, create the following tables:

```sql
CREATE TABLE recipes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    description TEXT,
    type ENUM('soup', 'special', 'salad', 'burger', 'breakfast'),
    ingredients JSON,
    instructions JSON,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE menus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    week VARCHAR(10),
    serving_size INT,
    data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Troubleshooting

### Menu Not Generating
- Check browser console for JavaScript errors
- Verify PHP error logs
- Ensure data directory exists and is writable

### Export Not Working
- Verify file permissions on data directory
- Check available disk space
- Browser may have blocked file download

### Database Errors
- Verify database credentials in .env
- Check MySQL server is running
- Application will fall back to JSON storage if DB unavailable

## Support

For support and feature requests, visit [allaround.work](https://allaround.work)

## License

Proprietary - Allaround Solutions

## Version

**v2.0.0** - Dynamic PHP Implementation with SEO Optimization

## Changelog

### v2.0.0 (Current)
- Converted from static HTML to dynamic PHP
- Added comprehensive recipe database
- Implemented AJAX-based menu generation
- Added SEO optimization
- Multiple export formats (Text, CSV, JSON)
- Responsive design for all devices
- Security hardening

### v1.0.0
- Original static HTML implementation
