<?php
/**
 * Recipe Database Class
 * Contains comprehensive recipe database
 */

class RecipeDatabase
{
    private $recipes = [];

    public function __construct()
    {
        $this->loadRecipes();
    }

    /**
     * Load all recipes from data store
     */
    private function loadRecipes()
    {
        // Load from JSON file or database
        $recipesFile = ROOT_PATH . '/data/recipes.json';

        if (file_exists($recipesFile)) {
            $this->recipes = json_decode(file_get_contents($recipesFile), true) ?? $this->getDefaultRecipes();
        } else {
            $this->recipes = $this->getDefaultRecipes();
            $this->saveRecipes();
        }
    }

    /**
     * Get random soup recipe
     */
    public function getRandomSoup()
    {
        $soups = $this->recipes['soups'] ?? [];
        return $soups[array_rand($soups)] ?? $this->getDefaultSoup();
    }

    /**
     * Get random daily special
     */
    public function getRandomSpecial()
    {
        $specials = $this->recipes['specials'] ?? [];
        return $specials[array_rand($specials)] ?? $this->getDefaultSpecial();
    }

    /**
     * Get random salad
     */
    public function getRandomSalad()
    {
        $salads = $this->recipes['salads'] ?? [];
        return $salads[array_rand($salads)] ?? $this->getDefaultSalad();
    }

    /**
     * Get random burger
     */
    public function getRandomBurger()
    {
        $burgers = $this->recipes['burgers'] ?? [];
        return $burgers[array_rand($burgers)] ?? $this->getDefaultBurger();
    }

    /**
     * Get random breakfast (Mon-Fri, Sat, or Sun)
     */
    public function getRandomBreakfast($monFri = true, $saturday = false)
    {
        if ($saturday) {
            $breakfasts = $this->recipes['breakfast_saturday'] ?? [];
        } else {
            $breakfasts = $monFri ? ($this->recipes['breakfast_monfri'] ?? []) : ($this->recipes['breakfast_sunday'] ?? []);
        }
        return $breakfasts[array_rand($breakfasts)] ?? $this->getDefaultBreakfast();
    }

    /**
     * Save recipes to file
     */
    private function saveRecipes()
    {
        $dataDir = ROOT_PATH . '/data';
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }
        file_put_contents($dataDir . '/recipes.json', json_encode($this->recipes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Default recipes data
     */
    private function getDefaultRecipes()
    {
        return [
            'soups' => [
                [
                    'name' => 'Chicken Noodle Soup',
                    'description' => 'Light and comforting classic with soft noodles',
                    'ingredients' => [
                        'Low-sodium chicken broth - 4 gallons',
                        'Diced chicken breast - 5 lbs',
                        'Carrots, diced - 2 lbs',
                        'Celery, diced - 2 lbs',
                        'Onion, diced - 1 lb',
                        'Egg noodles - 3 lbs',
                        'Parsley or mixed herbs - 1 cup',
                        'Black pepper - to taste'
                    ],
                    'notes' => 'Chop vegetables small for easier chewing. Low-sodium broth suitable for seniors with dietary restrictions.'
                ],
                [
                    'name' => 'Tomato Basil Soup',
                    'description' => 'Smooth, velvety tomato soup with fresh basil notes',
                    'ingredients' => [
                        'Low-sodium tomato puree - 3 gallons',
                        'Vegetable broth - 1 gallon',
                        'Onion, diced - 1.5 lbs',
                        'Garlic, minced - 0.5 lb',
                        'Olive oil - 2 cups',
                        'Fresh basil - 1 cup',
                        'Black pepper - to taste'
                    ],
                    'notes' => 'Can add cream for richer texture. Basil adds nutritional value.'
                ],
                [
                    'name' => 'Lentil Soup',
                    'description' => 'Hearty, protein-rich soup with vegetables',
                    'ingredients' => [
                        'Dried lentils - 4 lbs',
                        'Low-sodium vegetable broth - 5 gallons',
                        'Onion, diced - 1.5 lbs',
                        'Carrot, diced - 2 lbs',
                        'Celery, diced - 1.5 lbs',
                        'Garlic, minced - 0.5 lb'
                    ],
                    'notes' => 'Excellent source of fiber and protein.'
                ],
                [
                    'name' => 'Butternut Squash Soup',
                    'description' => 'Smooth, creamy soup with warm spices',
                    'ingredients' => [
                        'Butternut squash, cubed - 8 lbs',
                        'Onion, diced - 1.5 lbs',
                        'Garlic, minced - 0.5 lb',
                        'Low-sodium vegetable broth - 4 gallons'
                    ],
                    'notes' => 'Blend until smooth for best texture.'
                ],
                [
                    'name' => 'Split Pea Soup',
                    'description' => 'Classic creamy pea soup with a hint of ham',
                    'ingredients' => [
                        'Dried split peas - 4 lbs',
                        'Low-sodium chicken/vegetable broth - 5 gallons',
                        'Onion, diced - 1.5 lbs',
                        'Carrot, diced - 2 lbs'
                    ],
                    'notes' => 'Cook until peas are very soft.'
                ]
            ],
            'specials' => [
                [
                    'name' => 'Baked Salmon with Lemon',
                    'description' => 'Tender baked salmon fillet with fresh lemon butter sauce',
                    'ingredients' => [
                        'Salmon fillets - 8 lbs',
                        'Butter - 1 lb',
                        'Fresh lemon juice - 1 cup',
                        'Garlic, minced - 0.5 lb',
                        'Fresh dill - 1 cup'
                    ],
                    'instructions' => [
                        'Preheat oven to 375°F',
                        'Place salmon on parchment-lined baking sheets',
                        'Mix butter, lemon juice, garlic, and dill',
                        'Spread mixture over salmon',
                        'Bake for 15-18 minutes until flakes easily'
                    ],
                    'notes' => 'Excellent omega-3 source.'
                ],
                [
                    'name' => 'Herb Roasted Chicken Breast',
                    'description' => 'Moist, tender chicken with Italian herbs and garlic',
                    'ingredients' => [
                        'Chicken breasts - 10 lbs',
                        'Olive oil - 1 cup',
                        'Garlic, minced - 0.5 lb',
                        'Fresh rosemary - 0.5 cup'
                    ],
                    'instructions' => [
                        'Preheat oven to 375°F',
                        'Coat chicken with olive oil and herbs',
                        'Roast for 25-30 minutes until internal temp is 165°F'
                    ],
                    'notes' => 'Ensure chicken is cooked to safe temperature.'
                ],
                [
                    'name' => 'Slow Cooker Pot Roast',
                    'description' => 'Tender beef with vegetables in broth',
                    'ingredients' => [
                        'Beef chuck roast - 10 lbs',
                        'Potatoes, chunked - 4 lbs',
                        'Carrots, cut thick - 3 lbs',
                        'Celery, chunked - 1.5 lbs'
                    ],
                    'instructions' => [
                        'Place vegetables in slow cooker',
                        'Add beef and broth',
                        'Cook on low 6-8 hours'
                    ],
                    'notes' => 'Very tender, easy to chew.'
                ]
            ],
            'salads' => [
                [
                    'name' => 'Grilled Chicken Caesar Salad',
                    'description' => 'Classic Caesar with tender grilled chicken, parmesan, and soft croutons',
                    'ingredients' => [
                        'Grilled chicken breast strips - 6 lbs',
                        'Romaine lettuce, chopped - 8 lbs',
                        'Parmesan cheese, shaved - 1 lb',
                        'Soft bread croutons - 2 lbs'
                    ],
                    'instructions' => [
                        'Wash and dry lettuce thoroughly',
                        'Chop into bite-sized pieces',
                        'Slice grilled chicken into strips',
                        'Toss lettuce with dressing'
                    ],
                    'notes' => 'Use soft croutons for easy chewing.'
                ],
                [
                    'name' => 'Garden Vegetable Salad',
                    'description' => 'Fresh seasonal vegetables with light vinaigrette',
                    'ingredients' => [
                        'Mixed greens - 8 lbs',
                        'Tomatoes, diced - 2 lbs',
                        'Cucumbers, sliced - 2 lbs',
                        'Carrots, shredded - 1 lb'
                    ],
                    'instructions' => [
                        'Wash and dry all vegetables',
                        'Chop into appropriate sizes',
                        'Mix greens and vegetables'
                    ],
                    'notes' => 'Use soft, ripe vegetables.'
                ]
            ],
            'burgers' => [
                [
                    'name' => 'Lean Beef Burger',
                    'description' => 'Juicy ground beef patty on soft bun with toppings',
                    'ingredients' => [
                        'Lean ground beef - 8 lbs',
                        'Soft burger buns - 30 each',
                        'Cheddar cheese slices - 2 lbs',
                        'Tomato slices - 2 lbs'
                    ],
                    'instructions' => [
                        'Form beef into 2-3 oz patties',
                        'Cook on griddle to 160°F internal temp',
                        'Toast buns lightly',
                        'Assemble: bun, mayo, lettuce, burger, cheese, tomato, top bun'
                    ],
                    'notes' => 'Keep patties small for easier handling.'
                ],
                [
                    'name' => 'Turkey Burger',
                    'description' => 'Lean ground turkey patty with herb seasoning',
                    'ingredients' => [
                        'Ground turkey - 8 lbs',
                        'Breadcrumbs - 1 lb',
                        'Eggs - 8',
                        'Soft burger buns - 30 each'
                    ],
                    'instructions' => [
                        'Mix turkey, breadcrumbs, eggs until combined',
                        'Form into 2-3 oz patties',
                        'Cook on griddle to 160°F internal temp'
                    ],
                    'notes' => 'Lower in fat than beef.'
                ]
            ],
            'breakfast_monfri' => [
                [
                    'name' => 'Scrambled Eggs and Toast',
                    'description' => 'Soft scrambled eggs with buttered whole wheat toast',
                    'ingredients' => [
                        'Eggs - 48',
                        'Butter - 1 lb',
                        'Whole wheat bread - 2 loaves'
                    ],
                    'instructions' => [
                        'Beat eggs gently',
                        'Melt butter in large pans over medium heat',
                        'Add eggs, stir slowly and gently',
                        'Cook until soft, creamy curds form'
                    ],
                    'notes' => 'Don\'t overcook eggs - they should be soft and creamy.'
                ],
                [
                    'name' => 'Steel Cut Oatmeal with Fruit',
                    'description' => 'Warm, creamy oatmeal topped with berries and nuts',
                    'ingredients' => [
                        'Steel cut oats - 5 lbs',
                        'Water - 8 gallons',
                        'Butter - 0.5 lb',
                        'Honey - 1 cup',
                        'Mixed berries (frozen) - 3 lbs'
                    ],
                    'instructions' => [
                        'Bring water and salt to boil',
                        'Add oats slowly, stirring constantly',
                        'Reduce heat and simmer 30-40 minutes'
                    ],
                    'notes' => 'Excellent fiber source.'
                ]
            ],
            'breakfast_saturday' => [
                [
                    'name' => 'Pancakes with Maple Syrup',
                    'description' => 'Fluffy pancakes with warm maple syrup and butter',
                    'ingredients' => [
                        'All-purpose flour - 3 lbs',
                        'Baking powder - 3 tablespoons',
                        'Eggs - 36',
                        'Milk - 2 gallons'
                    ],
                    'instructions' => [
                        'Mix flour, baking powder, salt in large bowl',
                        'Whisk eggs, milk, melted butter together',
                        'Combine wet and dry ingredients gently',
                        'Cook on greased griddle at medium heat'
                    ],
                    'notes' => 'Keep warm in oven until all cooked.'
                ]
            ],
            'breakfast_sunday' => [
                [
                    'name' => 'French Toast with Berries',
                    'description' => 'Soft, eggy French toast with fresh berries',
                    'ingredients' => [
                        'White bread, sliced - 2 loaves',
                        'Eggs - 40',
                        'Milk - 1 quart',
                        'Cinnamon - 2 tablespoons',
                        'Maple syrup - 1 quart'
                    ],
                    'instructions' => [
                        'Whisk eggs, milk, cinnamon, vanilla',
                        'Dip bread slices briefly in mixture',
                        'Cook on buttered griddle until golden'
                    ],
                    'notes' => 'Use soft bread. Don\'t soak too long.'
                ]
            ]
        ];
    }

    private function getDefaultSoup()
    {
        return ['name' => 'Chicken Noodle Soup', 'description' => 'Classic comfort soup', 'ingredients' => []];
    }

    private function getDefaultSpecial()
    {
        return ['name' => 'Baked Salmon', 'description' => 'Fresh baked salmon', 'ingredients' => [], 'instructions' => []];
    }

    private function getDefaultSalad()
    {
        return ['name' => 'Garden Salad', 'description' => 'Fresh garden salad', 'ingredients' => [], 'instructions' => []];
    }

    private function getDefaultBurger()
    {
        return ['name' => 'Beef Burger', 'description' => 'Classic beef burger', 'ingredients' => [], 'instructions' => []];
    }

    private function getDefaultBreakfast()
    {
        return ['name' => 'Scrambled Eggs', 'description' => 'Soft scrambled eggs', 'ingredients' => [], 'instructions' => []];
    }
}
?>