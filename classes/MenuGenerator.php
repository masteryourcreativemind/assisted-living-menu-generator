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
     * Generate a full week menu with no repeating items
     * and no double meat types for lunch (special) and dinner (burger) on the same day
     */
    public function generateWeeklyMenu($weekDate, $servingSize = 25)
    {
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $days = [];

        // Generate weekly breakfast special (same for all days)
        $weeklyBreakfastSP = $this->recipeDb->getRandomBreakfast(true, false);

        // Track used items across the week to prevent repeats
        $usedSoups = [];
        $usedSpecials = [];
        $usedSalads = [];
        $usedBurgers = [];

        foreach ($daysOfWeek as $index => $day) {
            $dayMenu = $this->generateDayMenuUnique(
                $day, $index, $servingSize, $weeklyBreakfastSP,
                $usedSoups, $usedSpecials, $usedSalads, $usedBurgers
            );
            $days[] = $dayMenu;

            // Track used items
            $usedSoups[] = $dayMenu['soup']['name'];
            $usedSpecials[] = $dayMenu['special']['name'];
            $usedSalads[] = $dayMenu['salad']['name'];
            $usedBurgers[] = $dayMenu['burger']['name'];
        }

        $this->currentMenu = [
            'week' => $weekDate,
            'serving_size' => $servingSize,
            'generated_at' => date('Y-m-d H:i:s'),
            'weekly_breakfast_sp' => $weeklyBreakfastSP,
            'days' => $days
        ];

        return $this->currentMenu;
    }

    /**
     * Generate a unique day menu avoiding already-used items
     * and preventing same meat type for special and burger
     */
    private function generateDayMenuUnique($dayName, $dayIndex, $servingSize, $weeklyBreakfastSP, $usedSoups, $usedSpecials, $usedSalads, $usedBurgers)
    {
        $soup = $this->recipeDb->getRandomSoup($usedSoups);
        $salad = $this->recipeDb->getRandomSalad($usedSalads);

        // Pick the special first, then pick a burger with a different meat type
        $special = $this->recipeDb->getRandomSpecial($usedSpecials);
        $specialMeatType = $this->recipeDb->getMeatType($special);

        // Burger must not share the same meat type as the special
        $burger = $this->recipeDb->getRandomBurger($usedBurgers, [$specialMeatType]);

        return [
            'day' => $dayName,
            'day_index' => $dayIndex,
            'breakfast_sp' => $weeklyBreakfastSP,
            'soup' => $soup,
            'special' => $special,
            'salad' => $salad,
            'burger' => $burger,
            'serving_size' => $servingSize
        ];
    }

    /**
     * Regenerate a single day in current menu, avoiding items used on other days
     */
    public function regenerateSingleDay($dayIndex)
    {
        if (!isset($this->currentMenu['days'][$dayIndex])) {
            throw new Exception('Invalid day index');
        }

        $day = $this->currentMenu['days'][$dayIndex];
        $dayName = $day['day'];
        $weeklyBreakfastSP = $this->currentMenu['weekly_breakfast_sp'] ?? null;

        // Collect items used on OTHER days to avoid repeats
        $usedSoups = [];
        $usedSpecials = [];
        $usedSalads = [];
        $usedBurgers = [];

        foreach ($this->currentMenu['days'] as $i => $d) {
            if ($i === $dayIndex) {
                continue;
            }
            $usedSoups[] = $d['soup']['name'];
            $usedSpecials[] = $d['special']['name'];
            $usedSalads[] = $d['salad']['name'];
            $usedBurgers[] = $d['burger']['name'];
        }

        $this->currentMenu['days'][$dayIndex] = $this->generateDayMenuUnique(
            $dayName,
            $dayIndex,
            $this->currentMenu['serving_size'],
            $weeklyBreakfastSP,
            $usedSoups, $usedSpecials, $usedSalads, $usedBurgers
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