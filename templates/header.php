<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <meta name="author" content="Allaround Solutions">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:image" content="https://allaround.work/tools/menugen/assets/images/og-image.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="twitter:image" content="https://allaround.work/tools/menugen/assets/images/og-image.png">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo ASSETS_PATH; ?>/images/favicon.svg">
    <link rel="alternate icon" href="<?php echo ASSETS_PATH; ?>/images/favicon.ico">

    <!-- Preconnect to external resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title><?php echo htmlspecialchars($page_title); ?></title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>/css/style.css">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "<?php echo APP_NAME; ?>",
        "description": "<?php echo htmlspecialchars($page_description); ?>",
        "url": "<?php echo APP_URL; ?>",
        "applicationCategory": "UtilitiesApplication",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "USD"
        }
    }
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        header h1 {
            font-size: 2.5em;
            margin-bottom: 8px;
            font-weight: 700;
        }

        header p {
            font-size: 1.1em;
            opacity: 0.95;
        }

        .breadcrumb {
            padding: 10px 30px;
            font-size: 0.9em;
            background: #f9f9f9;
            border-bottom: 1px solid #e0e0e0;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
        }

        @media (max-width: 1024px) {
            .content {
                grid-template-columns: 1fr;
            }
        }

        .section {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            background: #f9f9f9;
        }

        .section h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.5em;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
            font-size: 0.9em;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 0.95em;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.95em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-generate {
            background: #48bb78;
            color: white;
            font-size: 1.1em;
            width: 100%;
            padding: 15px;
            margin-top: 20px;
        }

        .btn-generate:hover {
            background: #38a169;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
        }

        .btn-export {
            background: #ed8936;
            color: white;
            margin-top: 5px;
        }

        .btn-export:hover {
            background: #dd6b20;
        }

        .btn-clear {
            background: #ef5350;
            color: white;
        }

        .btn-clear:hover {
            background: #e53935;
        }

        .menu-display {
            grid-column: 1 / -1;
            margin-top: 30px;
            border-top: 3px solid #667eea;
            padding-top: 30px;
        }

        .menu-display h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #667eea;
            font-size: 2em;
        }

        .day-menu {
            background: white;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        }

        .day-menu h3 {
            color: #764ba2;
            font-size: 1.4em;
            margin-bottom: 15px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .menu-section {
            margin: 15px 0;
            padding: 15px;
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }

        .menu-section h4 {
            color: #764ba2;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .menu-item-name {
            font-weight: 600;
            color: #333;
            font-size: 1.05em;
            margin-bottom: 8px;
        }

        .menu-item-desc {
            color: #666;
            font-size: 0.95em;
            margin-bottom: 10px;
            font-style: italic;
        }

        .ingredients-list,
        .instructions-list {
            margin-left: 20px;
        }

        .ingredients-list li,
        .instructions-list li {
            margin: 5px 0;
            color: #333;
            font-size: 0.9em;
        }

        .instructions-list li {
            margin: 8px 0;
            line-height: 1.5;
        }

        .recipe-notes {
            background: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 10px 0;
            border-radius: 4px;
            color: #666;
            font-size: 0.9em;
        }

        .week-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }

        .print-section {
            text-align: center;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 8px;
            margin-top: 20px;
            grid-column: 1 / -1;
        }

        .print-section button {
            display: inline-block;
            margin: 0 5px;
        }

        .loading {
            text-align: center;
            padding: 40px 20px;
            font-style: italic;
            color: #999;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 10px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #c33;
        }

        .success-message {
            background: #efe;
            color: #3c3;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #3c3;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
            }

            .content>.section:nth-child(n+1):nth-child(-n+5) {
                display: none;
            }

            .print-section {
                display: none;
            }

            .breadcrumb {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>🍽️ <?php echo APP_NAME; ?></h1>
            <p>Auto-Generate Recipes, Soups, Specials & Breakfast Menu</p>
        </header>

        <nav class="breadcrumb">
            <a href="<?php echo APP_URL; ?>">Home</a>
            <?php if ($view !== 'home'): ?>
                > <span><?php echo ucfirst($view); ?></span>
            <?php endif; ?>
        </nav>