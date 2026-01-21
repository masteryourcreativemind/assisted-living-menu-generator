</div>

<footer style="background: #f0f0f0; padding: 30px; text-align: center; margin-top: 30px; border-top: 1px solid #ddd;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <p style="color: #666; margin-bottom: 10px;">
            &copy; <?php echo date('Y'); ?> <strong>Assisted Living Menu Generator</strong> v<?php echo APP_VERSION; ?>
        </p>
        <p style="color: #999; font-size: 0.9em; margin-bottom: 15px;">
            Professional Menu Planning for Senior Care Facilities
        </p>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p style="color: #666; font-size: 0.85em;">
                <strong>Accessibility:</strong> This application is designed to be accessible to all users.
                For support, please visit <a href="https://allaround.work"
                    style="color: #667eea; text-decoration: none;">allaround.work</a>
            </p>
        </div>
        <div style="margin-top: 15px;">
            <p style="color: #999; font-size: 0.8em;">
                URL: <a href="<?php echo APP_URL; ?>"
                    style="color: #667eea; text-decoration: none;"><?php echo APP_URL; ?></a>
            </p>
        </div>
    </div>
</footer>

<!-- Schema Markup for Local Business -->
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "<?php echo APP_NAME; ?>",
        "description": "<?php echo htmlspecialchars($page_description); ?>",
        "url": "<?php echo APP_URL; ?>",
        "applicationCategory": "UtilitiesApplication",
        "operatingSystem": "Web",
        "inLanguage": "en",
        "publisher": {
            "@type": "Organization",
            "name": "Allaround Solutions",
            "url": "https://allaround.work"
        },
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "USD",
            "availability": "https://schema.org/InStock"
        }
    }
    </script>

</body>

</html>