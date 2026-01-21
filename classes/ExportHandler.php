<?php
/**
 * Export Handler Class
 * Handles menu exports in various formats
 */

class ExportHandler
{
    private $menu;
    private $filename;

    public function __construct($menu)
    {
        $this->menu = $menu;
    }

    /**
     * Export menu to specified format
     */
    public function export($format = 'text')
    {
        switch ($format) {
            case 'text':
                return $this->exportAsText();
            case 'csv':
                return $this->exportAsCSV();
            case 'json':
                return $this->exportAsJSON();
            case 'pdf':
                return $this->exportAsPDF();
            default:
                throw new Exception('Unsupported export format: ' . $format);
        }
    }

    /**
     * Export as plain text
     */
    private function exportAsText()
    {
        $text = "=== ASSISTED LIVING WEEKLY MENU ===\n";
        $text .= "Week of: " . $this->menu['week'] . "\n";
        $text .= "Serving Size: " . $this->menu['serving_size'] . " residents\n";
        $text .= "Generated: " . date('Y-m-d H:i:s') . "\n";
        $text .= str_repeat('=', 70) . "\n\n";

        foreach ($this->menu['days'] as $day) {
            $text .= $this->formatDayAsText($day);
        }

        $this->filename = 'Weekly_Menu_' . str_replace('-', '_', $this->menu['week']) . '.txt';
        return $text;
    }

    /**
     * Format single day as text
     */
    private function formatDayAsText($day)
    {
        $text = str_repeat('=', 70) . "\n";
        $text .= strtoupper($day['day']) . "\n";
        $text .= str_repeat('=', 70) . "\n\n";

        // Breakfast
        $text .= $this->formatRecipeAsText('Breakfast', $day['breakfast']);

        // Soup
        $text .= $this->formatRecipeAsText('Soup', $day['soup']);

        // Special
        $text .= $this->formatRecipeAsText('Daily Special', $day['special']);

        // Salad
        $text .= $this->formatRecipeAsText('Salad', $day['salad']);

        // Burger
        $text .= $this->formatRecipeAsText('Burger', $day['burger']);

        $text .= "\n";
        return $text;
    }

    /**
     * Format recipe as text
     */
    private function formatRecipeAsText($type, $recipe)
    {
        $text = "\n--- $type: " . $recipe['name'] . " ---\n";
        $text .= $recipe['description'] . "\n\n";

        if (!empty($recipe['ingredients'])) {
            $text .= "Ingredients:\n";
            foreach ($recipe['ingredients'] as $ingredient) {
                $text .= "  • " . $ingredient . "\n";
            }
        }

        if (!empty($recipe['instructions'])) {
            $text .= "\nInstructions:\n";
            foreach ($recipe['instructions'] as $index => $instruction) {
                $text .= "  " . ($index + 1) . ". " . $instruction . "\n";
            }
        }

        if (!empty($recipe['notes'])) {
            $text .= "\nNotes: " . $recipe['notes'] . "\n";
        }

        $text .= "\n";
        return $text;
    }

    /**
     * Export as CSV
     */
    private function exportAsCSV()
    {
        $csv = "Day,Item Type,Name,Description,Ingredients,Instructions,Notes\n";

        foreach ($this->menu['days'] as $day) {
            // Breakfast
            $csv .= $this->formatRecipeAsCSV($day['day'], 'Breakfast', $day['breakfast']);
            // Soup
            $csv .= $this->formatRecipeAsCSV($day['day'], 'Soup', $day['soup']);
            // Special
            $csv .= $this->formatRecipeAsCSV($day['day'], 'Special', $day['special']);
            // Salad
            $csv .= $this->formatRecipeAsCSV($day['day'], 'Salad', $day['salad']);
            // Burger
            $csv .= $this->formatRecipeAsCSV($day['day'], 'Burger', $day['burger']);
        }

        $this->filename = 'Weekly_Menu_' . str_replace('-', '_', $this->menu['week']) . '.csv';
        return $csv;
    }

    /**
     * Format recipe as CSV row
     */
    private function formatRecipeAsCSV($day, $type, $recipe)
    {
        $ingredients = implode(' | ', $recipe['ingredients'] ?? []);
        $instructions = implode(' | ', $recipe['instructions'] ?? []);
        $notes = $recipe['notes'] ?? '';

        return $this->escapeCSV($day) . ',' .
            $this->escapeCSV($type) . ',' .
            $this->escapeCSV($recipe['name']) . ',' .
            $this->escapeCSV($recipe['description']) . ',' .
            $this->escapeCSV($ingredients) . ',' .
            $this->escapeCSV($instructions) . ',' .
            $this->escapeCSV($notes) . "\n";
    }

    /**
     * Escape CSV field
     */
    private function escapeCSV($field)
    {
        if (strpos($field, ',') !== false || strpos($field, '"') !== false || strpos($field, "\n") !== false) {
            return '"' . str_replace('"', '""', $field) . '"';
        }
        return $field;
    }

    /**
     * Export as JSON
     */
    private function exportAsJSON()
    {
        $this->filename = 'Weekly_Menu_' . str_replace('-', '_', $this->menu['week']) . '.json';
        return json_encode($this->menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Export as PDF (requires TCPDF or similar library)
     */
    private function exportAsPDF()
    {
        // This would require a PDF library like TCPDF, mPDF, or similar
        // For now, return text format with PDF extension
        $this->filename = 'Weekly_Menu_' . str_replace('-', '_', $this->menu['week']) . '.pdf';
        // In production, integrate proper PDF library
        return $this->exportAsText();
    }

    /**
     * Get generated filename
     */
    public function getFilename($format = 'text')
    {
        if (empty($this->filename)) {
            $this->export($format);
        }
        return $this->filename;
    }
}
?>