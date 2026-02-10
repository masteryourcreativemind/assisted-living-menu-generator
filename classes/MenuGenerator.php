<?php
/**
 * Menu Generator Class
 * Handles menu generation logic
 */

class MenuGenerator
{
    private $recipeDb;
    public $currentMenu; // Changed to public for session loading

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

        // Generate weekly items (same for all days)
        $weeklyBreakfastSP = $this->recipeDb->getRandomBreakfast(true, false);
        $weeklyBurger = $this->recipeDb->getRandomBurger();
        $weeklySalad = $this->recipeDb->getRandomSalad();

        foreach ($daysOfWeek as $index => $day) {
            $days[] = $this->generateDayMenu($day, $index, $servingSize, $weeklyBreakfastSP, $weeklyBurger, $weeklySalad);
        }

        $this->currentMenu = [
            'week' => $weekDate,
            'serving_size' => $servingSize,
            'generated_at' => date('Y-m-d H:i:s'),
            'weekly_breakfast_sp' => $weeklyBreakfastSP,
            'weekly_burger' => $weeklyBurger,
            'weekly_salad' => $weeklySalad,
            'days' => $days
        ];

        return $this->currentMenu;
    }

    /**
     * Generate menu for a single day
     */
    private function generateDayMenu($dayName, $dayIndex, $servingSize, $weeklyBreakfastSP = null, $weeklyBurger = null, $weeklySalad = null)
    {
        return [
            'day' => $dayName,
            'day_index' => $dayIndex,
            'breakfast_sp' => $weeklyBreakfastSP, // Weekly breakfast special (same all week)
            'soup' => $this->recipeDb->getRandomSoup(),
            'special' => $this->recipeDb->getRandomSpecial(),
            'salad' => $weeklySalad, // Weekly salad (same all week)
            'burger' => $weeklyBurger, // Weekly burger (same all week)
            'dessert' => $this->recipeDb->getRandomDessert(), // Daily dessert
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
        $weeklyBreakfastSP = $this->currentMenu['weekly_breakfast_sp'] ?? null;
        $weeklyBurger = $this->currentMenu['weekly_burger'] ?? null;
        $weeklySalad = $this->currentMenu['weekly_salad'] ?? null;

        $this->currentMenu['days'][$dayIndex] = $this->generateDayMenu(
            $dayName,
            $dayIndex,
            $this->currentMenu['serving_size'],
            $weeklyBreakfastSP,
            $weeklyBurger,
            $weeklySalad
        );

        return $this->currentMenu;
    }

    /**
     * Regenerate weekly breakfast special for all days
     */
    public function regenerateWeeklyBreakfastSP()
    {
        if (!isset($this->currentMenu['days'])) {
            throw new Exception('No menu generated');
        }

        // Generate new weekly breakfast special
        $newBreakfastSP = $this->recipeDb->getRandomBreakfast(true, false);
        $this->currentMenu['weekly_breakfast_sp'] = $newBreakfastSP;

        // Update all days with new breakfast SP
        foreach ($this->currentMenu['days'] as $index => $day) {
            $this->currentMenu['days'][$index]['breakfast_sp'] = $newBreakfastSP;
        }

        return $this->currentMenu;
    }

    /**
     * Regenerate weekly burger for all days
     */
    public function regenerateWeeklyBurger()
    {
        if (!isset($this->currentMenu['days'])) {
            throw new Exception('No menu generated');
        }

        // Generate new weekly burger
        $newBurger = $this->recipeDb->getRandomBurger();
        $this->currentMenu['weekly_burger'] = $newBurger;

        // Update all days with new burger
        foreach ($this->currentMenu['days'] as $index => $day) {
            $this->currentMenu['days'][$index]['burger'] = $newBurger;
        }

        return $this->currentMenu;
    }

    /**
     * Regenerate weekly salad for all days
     */
    public function regenerateWeeklySalad()
    {
        if (!isset($this->currentMenu['days'])) {
            throw new Exception('No menu generated');
        }

        // Generate new weekly salad
        $newSalad = $this->recipeDb->getRandomSalad();
        $this->currentMenu['weekly_salad'] = $newSalad;

        // Update all days with new salad
        foreach ($this->currentMenu['days'] as $index => $day) {
            $this->currentMenu['days'][$index]['salad'] = $newSalad;
        }

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