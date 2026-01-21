<?php
/**
 * Assisted Living Menu Generator
 * Dynamic PHP Application
 * URL: allaround.work/tools/menugen
 */

// Enable error reporting in development
error_reporting(E_ALL);
ini_set('display_errors', 0); // Log errors instead
ini_set('log_errors', 1);

// Start session
session_start();

// Define constants
define('APP_NAME', 'Assisted Living Menu Generator');
define('APP_VERSION', '2.0.0');
define('APP_URL', 'https://allaround.work/tools/menugen');
define('ROOT_PATH', dirname(__FILE__));
define('TEMPLATES_PATH', ROOT_PATH . '/templates');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// Include configuration
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';

// Include core classes
require_once ROOT_PATH . '/classes/MenuGenerator.php';
require_once ROOT_PATH . '/classes/RecipeDatabase.php';
require_once ROOT_PATH . '/classes/ExportHandler.php';

// Set default timezone
date_default_timezone_set('UTC');

// Initialize menu generator
$menuGenerator = new MenuGenerator();
$recipeDb = new RecipeDatabase();

// Handle AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');

    $action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';

    try {
        switch ($action) {
            case 'generate_week':
                $weekDate = isset($_POST['week']) ? sanitize_input($_POST['week']) : date('Y-W');
                $servingSize = isset($_POST['serving_size']) ? (int) $_POST['serving_size'] : 25;

                $menu = $menuGenerator->generateWeeklyMenu($weekDate, $servingSize);
                $_SESSION['current_menu'] = $menu;

                echo json_encode(['success' => true, 'menu' => $menu]);
                break;

            case 'regenerate_day':
                $dayIndex = isset($_POST['day_index']) ? (int) $_POST['day_index'] : 0;

                // Load current menu from session
                if (isset($_SESSION['current_menu'])) {
                    $menuGenerator->currentMenu = $_SESSION['current_menu'];
                }

                $menu = $menuGenerator->regenerateSingleDay($dayIndex);
                $_SESSION['current_menu'] = $menu;

                echo json_encode(['success' => true, 'menu' => $menu]);
                break;

            case 'regenerate_breakfast_sp':
                // Load current menu from session
                if (isset($_SESSION['current_menu'])) {
                    $menuGenerator->currentMenu = $_SESSION['current_menu'];
                }

                $menu = $menuGenerator->regenerateWeeklyBreakfastSP();
                $_SESSION['current_menu'] = $menu;

                echo json_encode(['success' => true, 'menu' => $menu]);
                break;

            case 'export':
                $format = isset($_POST['format']) ? sanitize_input($_POST['format']) : 'text';
                $menu = $_SESSION['current_menu'] ?? null;

                if (!$menu) {
                    throw new Exception('No menu generated');
                }

                $exporter = new ExportHandler($menu);
                $data = $exporter->export($format);

                echo json_encode([
                    'success' => true,
                    'data' => base64_encode($data),
                    'filename' => $exporter->getFilename($format)
                ]);
                break;

            default:
                throw new Exception('Unknown action');
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}

// Sanitize input function
function sanitize_input($input)
{
    if (is_array($input)) {
        return array_map('sanitize_input', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Load default view
$view = isset($_GET['view']) ? sanitize_input($_GET['view']) : 'home';

// Set page metadata for SEO
$page_title = APP_NAME . ' - Nutritious Weekly Menus for Senior Care Facilities';
$page_description = 'Generate customizable weekly menus for assisted living facilities. Professional recipes for seniors, meal planning, and export options.';
$page_keywords = 'assisted living, menu generator, senior nutrition, meal planning, recipes, dietary guidelines';
$canonical_url = APP_URL;

// Include header template
include TEMPLATES_PATH . '/header.php';

// Include view template
$view_file = TEMPLATES_PATH . '/' . $view . '.php';
if (file_exists($view_file)) {
    include $view_file;
} else {
    include TEMPLATES_PATH . '/404.php';
}

// Include footer template
include TEMPLATES_PATH . '/footer.php';
?>