# Menu SP (Special) System - Implementation Plan

## ✅ Phase 1: COMPLETED - Weekly Breakfast SP

### What Was Implemented:
- **Weekly Breakfast Special**: One breakfast recipe selected for entire week
- All 7 days share the same breakfast (consistency and simplicity)
- Prominent display at top of menu
- Separate "Regenerate Weekly Breakfast SP" button
- Backend API endpoint: `regenerate_breakfast_sp`

### Changed Files:
- `classes/MenuGenerator.php` - Added `weekly_breakfast_sp` property and `regenerateWeeklyBreakfastSP()` method
- `index.php` - Added AJAX handler for `regenerate_breakfast_sp` action
- `templates/home.php` - Updated display to show weekly breakfast prominently, added regenerate button

### Current Menu Structure:
```php
Menu {
    week: "2026-W03"
    serving_size: 25
    weekly_breakfast_sp: Recipe {}  // ← NEW: Same for all days
    days: [
        {
            day: "Monday"
            breakfast_sp: Recipe {}  // Points to weekly_breakfast_sp
            soup: Recipe {}
            special: Recipe {}
            salad: Recipe {}
            burger: Recipe {}
        },
        // ... 6 more days
    ]
}
```

---

## 🎯 Phase 2: PLANNED - Breakfast, Lunch, Dinner SP Options

### Goal:
Add flexibility to choose between **Weekly SP** or **Daily SP** for Breakfast, Lunch, and Dinner categories.

### Proposed Menu Structure:

```php
Menu {
    week: "2026-W03"
    serving_size: 25
    
    // SP Mode Configuration
    sp_config: {
        breakfast: "weekly",  // Options: "weekly" | "daily"
        lunch: "weekly",      // Options: "weekly" | "daily"
        dinner: "weekly"      // Options: "weekly" | "daily"
    }
    
    // Weekly Specials (only populated if sp_config[meal] = "weekly")
    weekly_breakfast_sp: Recipe {} | null
    weekly_lunch_sp: Recipe {} | null
    weekly_dinner_sp: Recipe {} | null
    
    days: [
        {
            day: "Monday"
            
            // Breakfast (weekly or daily based on sp_config.breakfast)
            breakfast_sp: Recipe {}  // If weekly: points to weekly_breakfast_sp
                                    // If daily: unique recipe
            
            // Lunch (weekly or daily based on sp_config.lunch)
            lunch: {
                soup: Recipe {}       // If weekly SP: weekly_lunch_sp
                                     // If daily SP: unique daily soup
                salad: Recipe {}     // Always weekly? Or configurable?
            }
            
            // Dinner (weekly or daily based on sp_config.dinner)
            dinner: {
                special: Recipe {}    // If weekly SP: weekly_dinner_sp
                                     // If daily SP: unique daily special
                burger: Recipe {}    // Always weekly? Or configurable?
            }
        },
        // ... 6 more days
    ]
}
```

### User Interface Changes:

#### Generation Settings Panel:
```html
<h2>⚙️ Generator Settings</h2>

<!-- Week Selection -->
<input type="week" id="week-select">

<!-- Serving Size -->
<select id="serving-size">...</select>

<!-- SP Mode Selection -->
<div class="sp-mode-section">
    <h3>📋 Special Mode Configuration</h3>
    
    <label>
        🍳 Breakfast:
        <select id="sp-mode-breakfast">
            <option value="weekly">Weekly SP (same all week)</option>
            <option value="daily">Daily SP (different each day)</option>
        </select>
    </label>
    
    <label>
        🥗 Lunch:
        <select id="sp-mode-lunch">
            <option value="weekly">Weekly SP (same all week)</option>
            <option value="daily">Daily SP (different each day)</option>
        </select>
    </label>
    
    <label>
        🍽️ Dinner:
        <select id="sp-mode-dinner">
            <option value="weekly">Weekly SP (same all week)</option>
            <option value="daily">Daily SP (different each day)</option>
        </select>
    </label>
</div>

<button onclick="generateFullWeek()">🚀 Generate Full Week Menu</button>
```

#### Regeneration Buttons:
```html
<!-- Weekly SP Regeneration (only shown if SP mode = weekly) -->
<div id="weekly-sp-buttons">
    <button onclick="regenerateWeeklySP('breakfast')">🍳 Regenerate Breakfast SP</button>
    <button onclick="regenerateWeeklySP('lunch')">🥗 Regenerate Lunch SP</button>
    <button onclick="regenerateWeeklySP('dinner')">🍽️ Regenerate Dinner SP</button>
</div>

<!-- Daily SP Regeneration (only shown if SP mode = daily) -->
<div id="daily-sp-section">
    <select id="day-regenerate">
        <option value="0">Monday</option>
        ...
    </select>
    <select id="meal-regenerate">
        <option value="breakfast">Breakfast</option>
        <option value="lunch">Lunch</option>
        <option value="dinner">Dinner</option>
    </select>
    <button onclick="regenerateDailySP()">🔄 Regenerate Selected Meal</button>
</div>
```

---

## 🏗️ Implementation Steps for Phase 2

### Step 1: Update RecipeDatabase.php
Add meal categorization:
```php
class RecipeDatabase {
    public function getRandomBreakfast()  // Breakfast items
    public function getRandomLunch()      // Lunch items (soups, salads)
    public function getRandomDinner()     // Dinner items (specials, burgers)
    
    // Or more granular:
    public function getRandomSoup()
    public function getRandomSalad()
    public function getRandomMainCourse()  // Dinner entree
    public function getRandomSide()
}
```

### Step 2: Update MenuGenerator.php
Add SP mode support:
```php
class MenuGenerator {
    public function generateWeeklyMenu($weekDate, $servingSize, $spConfig) {
        // $spConfig = ['breakfast' => 'weekly', 'lunch' => 'daily', 'dinner' => 'weekly']
        
        $weeklyBreakfastSP = null;
        $weeklyLunchSP = null;
        $weeklyDinnerSP = null;
        
        // Generate weekly SPs based on config
        if ($spConfig['breakfast'] === 'weekly') {
            $weeklyBreakfastSP = $this->recipeDb->getRandomBreakfast();
        }
        if ($spConfig['lunch'] === 'weekly') {
            $weeklyLunchSP = $this->recipeDb->getRandomLunch();
        }
        if ($spConfig['dinner'] === 'weekly') {
            $weeklyDinnerSP = $this->recipeDb->getRandomDinner();
        }
        
        // Generate days
        foreach ($days as $index => $day) {
            $days[] = $this->generateDayMenu(
                $day, 
                $index, 
                $servingSize, 
                $spConfig,
                $weeklyBreakfastSP,
                $weeklyLunchSP,
                $weeklyDinnerSP
            );
        }
        
        return $menu;
    }
    
    private function generateDayMenu($day, $index, $size, $spConfig, $bSP, $lSP, $dSP) {
        return [
            'day' => $day,
            'breakfast_sp' => $spConfig['breakfast'] === 'weekly' 
                ? $bSP 
                : $this->recipeDb->getRandomBreakfast(),
            'lunch_sp' => $spConfig['lunch'] === 'weekly'
                ? $lSP
                : $this->recipeDb->getRandomLunch(),
            'dinner_sp' => $spConfig['dinner'] === 'weekly'
                ? $dSP
                : $this->recipeDb->getRandomDinner()
        ];
    }
    
    public function regenerateWeeklySP($mealType) {
        // Regenerate weekly SP for specific meal
        // Update all days to use new SP
    }
    
    public function regenerateDailySP($dayIndex, $mealType) {
        // Regenerate specific meal for specific day
    }
}
```

### Step 3: Update index.php
Add new AJAX endpoints:
```php
case 'generate_week':
    $spConfig = [
        'breakfast' => sanitize_input($_POST['sp_breakfast'] ?? 'weekly'),
        'lunch' => sanitize_input($_POST['sp_lunch'] ?? 'weekly'),
        'dinner' => sanitize_input($_POST['sp_dinner'] ?? 'weekly')
    ];
    $menu = $menuGenerator->generateWeeklyMenu($weekDate, $servingSize, $spConfig);
    break;

case 'regenerate_weekly_sp':
    $mealType = sanitize_input($_POST['meal_type']); // 'breakfast', 'lunch', 'dinner'
    $menu = $menuGenerator->regenerateWeeklySP($mealType);
    break;

case 'regenerate_daily_sp':
    $dayIndex = (int)$_POST['day_index'];
    $mealType = sanitize_input($_POST['meal_type']);
    $menu = $menuGenerator->regenerateDailySP($dayIndex, $mealType);
    break;
```

### Step 4: Update templates/home.php
Add SP mode selectors and conditional button display:
```javascript
function generateFullWeek() {
    const spConfig = {
        breakfast: document.getElementById('sp-mode-breakfast').value,
        lunch: document.getElementById('sp-mode-lunch').value,
        dinner: document.getElementById('sp-mode-dinner').value
    };
    
    formData.append('sp_breakfast', spConfig.breakfast);
    formData.append('sp_lunch', spConfig.lunch);
    formData.append('sp_dinner', spConfig.dinner);
    
    // ... send request
}

function displayMenu() {
    // Show weekly SP boxes for meals in weekly mode
    if (menu.sp_config.breakfast === 'weekly') {
        html += renderWeeklySPBox('Breakfast', menu.weekly_breakfast_sp);
    }
    // ... similar for lunch and dinner
}

function regenerateWeeklySP(mealType) {
    // AJAX call to regenerate specific weekly SP
}

function regenerateDailySP() {
    // AJAX call to regenerate specific day's specific meal
}
```

---

## 📊 Database Schema Updates (Optional)

If persisting menus to database:

```sql
-- Add SP configuration columns
ALTER TABLE menus ADD COLUMN sp_breakfast_mode ENUM('weekly', 'daily') DEFAULT 'weekly';
ALTER TABLE menus ADD COLUMN sp_lunch_mode ENUM('weekly', 'daily') DEFAULT 'weekly';
ALTER TABLE menus ADD COLUMN sp_dinner_mode ENUM('weekly', 'daily') DEFAULT 'weekly';

-- Store weekly SPs
ALTER TABLE menus ADD COLUMN weekly_breakfast_sp_id INT NULL;
ALTER TABLE menus ADD COLUMN weekly_lunch_sp_id INT NULL;
ALTER TABLE menus ADD COLUMN weekly_dinner_sp_id INT NULL;

-- Daily menu items reference either weekly SP or unique recipe
ALTER TABLE daily_menus ADD COLUMN breakfast_recipe_id INT NULL;
ALTER TABLE daily_menus ADD COLUMN lunch_recipe_id INT NULL;
ALTER TABLE daily_menus ADD COLUMN dinner_recipe_id INT NULL;
```

---

## 🎨 UI/UX Considerations

### Visual Differentiation:
- **Weekly SP**: Yellow/gold background (#fef3c7)
- **Daily SP**: White/default background
- Clear icons: 📌 for weekly, 🔄 for daily

### Display Strategy:

**Option A: Separate Sections**
```
┌─────────────────────────────────┐
│  🍳 WEEKLY BREAKFAST SP         │
│  (All 7 Days)                   │
│  Scrambled Eggs & Toast         │
└─────────────────────────────────┘

┌─────────────────────────────────┐
│  Monday                         │
│  Lunch: Chicken Noodle Soup     │
│  Dinner: Baked Salmon           │
└─────────────────────────────────┘
```

**Option B: Inline with Notation**
```
┌─────────────────────────────────┐
│  Monday                         │
│  🍳 Breakfast: Scrambled Eggs  │
│     → Weekly Special            │
│  🥗 Lunch: Chicken Soup (Daily) │
│  🍽️ Dinner: Baked Salmon       │
│     → Weekly Special            │
└─────────────────────────────────┘
```

---

## ⚡ Performance Considerations

### Database Queries:
- Weekly SP mode: 3 recipe queries per week (1 per meal type)
- Daily SP mode: 21 recipe queries per week (3 meals × 7 days)
- **Optimization**: Cache weekly SPs in session

### Frontend Rendering:
- Conditional display based on SP mode
- Dynamic button visibility (show weekly regen buttons only for weekly SPs)

---

## 🧪 Testing Checklist

- [ ] Generate menu with all weekly SPs
- [ ] Generate menu with all daily SPs
- [ ] Generate menu with mixed (breakfast weekly, lunch daily, dinner weekly)
- [ ] Regenerate weekly breakfast SP
- [ ] Regenerate weekly lunch SP
- [ ] Regenerate weekly dinner SP
- [ ] Regenerate single day's breakfast (daily mode)
- [ ] Regenerate single day's lunch (daily mode)
- [ ] Regenerate single day's dinner (daily mode)
- [ ] Switch from weekly to daily mode mid-session
- [ ] Export menu with weekly SPs
- [ ] Export menu with daily SPs
- [ ] Session persistence of SP configuration
- [ ] Database persistence (if implemented)

---

## 📝 Documentation Updates Needed

1. **README.md** - Update features section with SP modes
2. **IMPLEMENTATION_GUIDE.md** - Document SP mode architecture
3. **AI_CONTEXT.md** - Update menu structure examples
4. **QUICK_START.md** - Add SP mode usage examples

---

## 🚀 Rollout Strategy

### Version 2.1.0: Weekly Breakfast SP (CURRENT)
- ✅ Implemented weekly breakfast special
- ✅ One breakfast for all 7 days
- ✅ Regenerate weekly breakfast button

### Version 2.2.0: Full SP Mode System (PLANNED)
- Add breakfast/lunch/dinner SP mode selectors
- Implement weekly vs daily logic for all meals
- Add granular regeneration controls
- Update UI to show SP mode indicators
- Comprehensive testing

### Version 2.3.0: Enhanced SP Features (FUTURE)
- Save SP preferences per facility
- SP mode templates (e.g., "Budget Mode" = all weekly, "Variety Mode" = all daily)
- SP scheduling (weekly for weekdays, daily for weekends)
- Nutritional balancing across SP modes

---

## 💡 Additional Ideas

### SP Mode Presets:
```javascript
const SP_PRESETS = {
    'consistency': { breakfast: 'weekly', lunch: 'weekly', dinner: 'weekly' },
    'variety': { breakfast: 'daily', lunch: 'daily', dinner: 'daily' },
    'balanced': { breakfast: 'weekly', lunch: 'daily', dinner: 'weekly' },
    'budget': { breakfast: 'weekly', lunch: 'weekly', dinner: 'weekly' }
};
```

### Smart SP Recommendations:
- Analyze recipe costs and suggest weekly SP for expensive items
- Suggest daily SP for perishable ingredients
- Recommend weekly SP for resident favorites

### SP Analytics:
- Track which SP mode is most popular
- Monitor resident satisfaction by SP mode
- Cost analysis: weekly vs daily SP modes

---

**Plan Created**: January 20, 2026  
**Current Version**: 2.1.0 (Weekly Breakfast SP)  
**Next Version**: 2.2.0 (Full SP Mode System)  
**Status**: Phase 1 Complete, Phase 2 Planned
