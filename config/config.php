<?php
/**
 * Application Configuration
 */

return [
    // Application Settings
    'app' => [
        'name' => 'Assisted Living Menu Generator',
        'version' => '2.0.0',
        'url' => 'https://allaround.work/tools/menugen',
        'debug' => false,
    ],

    // Database Settings (uses environment variables)
    'database' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: 3306,
        'name' => getenv('DB_NAME') ?: 'assisted_living_menu',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
    ],

    // Serving Sizes (residents)
    'serving_sizes' => [10, 15, 20, 25, 30, 40, 50, 75, 100],
    'default_serving_size' => 25,

    // Menu Settings
    'menu' => [
        'items_per_day' => 5, // breakfast, soup, special, salad, burger
        'week_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        'dietary_restrictions' => ['vegetarian', 'vegan', 'gluten-free', 'dairy-free', 'low-sodium', 'pureed'],
    ],

    // SEO Settings
    'seo' => [
        'site_name' => 'Assisted Living Menu Generator',
        'site_description' => 'Professional menu generator for assisted living and senior care facilities',
        'keywords' => 'assisted living, menu generator, senior nutrition, meal planning',
        'og_image' => 'https://allaround.work/tools/menugen/assets/images/og-image.png',
        'twitter_handle' => '@allaroundwork',
    ],

    // Export Settings
    'export' => [
        'formats' => ['text', 'csv', 'json', 'pdf'],
        'max_file_size' => 10 * 1024 * 1024, // 10MB
    ],

    // Email Settings (for notifications)
    'email' => [
        'from_address' => getenv('EMAIL_FROM') ?: 'noreply@allaround.work',
        'from_name' => 'Assisted Living Menu Generator',
        'smtp_host' => getenv('SMTP_HOST') ?: '',
        'smtp_port' => getenv('SMTP_PORT') ?: 587,
        'smtp_user' => getenv('SMTP_USER') ?: '',
        'smtp_pass' => getenv('SMTP_PASS') ?: '',
    ],
];
?>