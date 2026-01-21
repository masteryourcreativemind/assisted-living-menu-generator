<?php
/**
 * Menu Generator Class
 * Handles menu generation logic
 */

class MenuGenerator
{
    private $recipeDb;
    private $currentMenu;

    public function __construct()
    {
        $this->recipeDb = new RecipeDatabase();
    }

    /**
     * Generate a full week menu
     */
    public function generateWeeklyMenu($weekDate, $servingSize = 25)
    {
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $days = [];

        foreach ($daysOfWeek as $index => $day) {
            $days[] = $this->generateDayMenu($day, $index, $servingSize);
        }

        $this->currentMenu = [
            'week' => $weekDate,
            'serving_size' => $servingSize,
            'generated_at' => date('Y-m-d H:i:s'),
            'days' => $days
        ];

        return $this->currentMenu;
    }

    /**
     * Generate menu for a single day
     */
    private function generateDayMenu($dayName, $dayIndex, $servingSize)
    {
        // Determine breakfast pool based on day
        $isMondayFriday = ($dayIndex >= 0 && $dayIndex <= 4);
        $isSaturday = ($dayIndex === 5);

        return [
            'day' => $dayName,
            'day_index' => $dayIndex,
            'breakfast' => $this->recipeDb->getRandomBreakfast($isMondayFriday, $isSaturday),
            'soup' => $this->recipeDb->getRandomSoup(),
            'special' => $this->recipeDb->getRandomSpecial(),
            'salad' => $this->recipeDb->getRandomSalad(),
            'burger' => $this->recipeDb->getRandomBurger(),
            'serving_size' => $servingSize
        ];
    }

    /**
     * Regenerate a single day in current menu
     */
    public function regenerateSingleDay($dayIndex)
    {
        if (!isset($this->currentMenu['days'][$dayIndex])) {
            throw new Exception('Invalid day index');
        }

        $day = $this->currentMenu['days'][$dayIndex];
        $dayName = $day['day'];

        $this->currentMenu['days'][$dayIndex] = $this->generateDayMenu(
            $dayName,
            $dayIndex,
            $this->currentMenu['serving_size']
        );

        return $this->currentMenu;
    }

    /**
     * Get current menu
     */
    public function getCurrentMenu()
    {
        return $this->currentMenu;
    }

    /**
     * Validate menu data
     */
    public function validateMenu($menu)
    {
        if (!isset($menu['week']) || !isset($menu['serving_size']) || !isset($menu['days'])) {
            return false;
        }

        if (count($menu['days']) !== 7) {
            return false;
        }

        if ($menu['serving_size'] < 10 || $menu['serving_size'] > 100) {
            return false;
        }

        return true;
    }
}
?>