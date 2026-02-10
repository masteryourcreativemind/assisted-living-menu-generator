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
     * Get random dessert (includes individual fruits and fruit combinations)
     */
    public function getRandomDessert()
    {
        $desserts = $this->recipes['desserts'] ?? [];
        $fruits = $this->recipes['fruits'] ?? [];
        
        // Combine desserts and fruits into one pool
        $allDesserts = array_merge($desserts, $fruits);
        
        // Sometimes generate a mixed fruit cup
        if (count($fruits) >= 2 && rand(1, 100) <= 30) { // 30% chance of fruit combination
            return $this->generateFruitCombination();
        }
        
        return $allDesserts[array_rand($allDesserts)] ?? $this->getDefaultDessert();
    }

    /**
     * Generate a random fruit combination
     */
    private function generateFruitCombination()
    {
        $fruits = $this->recipes['fruits'] ?? [];
        
        if (count($fruits) < 2) {
            return $this->getDefaultDessert();
        }
        
        // Randomly select 2-3 fruits
        $numFruits = rand(2, min(3, count($fruits)));
        $selectedFruits = [];
        $shuffledFruits = $fruits;
        shuffle($shuffledFruits);
        
        for ($i = 0; $i < $numFruits; $i++) {
            $selectedFruits[] = $shuffledFruits[$i]['name'];
        }
        
        return [
            'name' => 'Mixed Fruit Cup',
            'description' => 'Fresh cubed fruit combination: ' . implode(', ', $selectedFruits),
            'ingredients' => [
                implode(', ', $selectedFruits) . ' - cubed, 1/2 inch pieces - 10 lbs total'
            ],
            'instructions' => [
                'Wash all fruits thoroughly',
                'Peel and remove seeds/pits as needed',
                'Cut into 1/2 inch cubes for easy eating',
                'Mix gently in large bowl',
                'Serve chilled in 4 oz portions'
            ],
            'notes' => 'Fresh fruit provides vitamins and fiber. Cubed for easy chewing.'
        ];
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
            ],
            'desserts' => [
                [
                    'name' => 'Sugar-Free Chocolate Pudding',
                    'description' => 'Smooth, creamy chocolate pudding made with sugar substitute',
                    'ingredients' => [
                        'Sugar-free chocolate pudding mix - 3 lbs',
                        'Low-fat milk - 2 gallons',
                        'Whipped topping (optional) - 1 lb'
                    ],
                    'instructions' => [
                        'Mix pudding powder with cold milk according to package',
                        'Whisk until smooth and thick',
                        'Portion into 4 oz cups',
                        'Refrigerate until set (at least 2 hours)',
                        'Top with whipped topping if desired'
                    ],
                    'notes' => 'Diabetic-friendly option. Soft texture for easy swallowing.'
                ],
                [
                    'name' => 'Vanilla Pudding',
                    'description' => 'Classic creamy vanilla pudding',
                    'ingredients' => [
                        'Sugar-free vanilla pudding mix - 3 lbs',
                        'Low-fat milk - 2 gallons',
                        'Vanilla extract - 2 tablespoons'
                    ],
                    'instructions' => [
                        'Combine pudding mix with cold milk',
                        'Whisk until smooth',
                        'Pour into individual cups',
                        'Refrigerate until firm'
                    ],
                    'notes' => 'Easy to digest. Can be thickened for swallowing difficulties.'
                ],
                [
                    'name' => 'Banana Pudding',
                    'description' => 'Layered dessert with vanilla pudding, bananas, and vanilla wafers',
                    'ingredients' => [
                        'Sugar-free vanilla pudding - prepared, 2 gallons',
                        'Ripe bananas, sliced - 5 lbs',
                        'Soft vanilla wafers - 2 lbs',
                        'Whipped topping - 2 lbs'
                    ],
                    'instructions' => [
                        'Prepare pudding according to package',
                        'Slice bananas into 1/4 inch rounds',
                        'Layer wafers, bananas, and pudding in cups',
                        'Top with whipped topping',
                        'Chill before serving'
                    ],
                    'notes' => 'Soft texture throughout. Bananas add potassium.'
                ],
                [
                    'name' => 'Applesauce',
                    'description' => 'Smooth, unsweetened applesauce with cinnamon',
                    'ingredients' => [
                        'Unsweetened applesauce - 3 gallons',
                        'Cinnamon - 2 tablespoons (optional)',
                        'Sugar substitute - to taste (optional)'
                    ],
                    'instructions' => [
                        'Portion applesauce into 4 oz cups',
                        'Sprinkle with cinnamon if desired',
                        'Serve chilled or at room temperature'
                    ],
                    'notes' => 'Very easy to swallow. High in fiber. No added sugar.'
                ],
                [
                    'name' => 'Jell-O (Sugar-Free)',
                    'description' => 'Light and refreshing flavored gelatin dessert',
                    'ingredients' => [
                        'Sugar-free gelatin mix (various flavors) - 2 lbs',
                        'Boiling water - 2 gallons',
                        'Cold water - 2 gallons'
                    ],
                    'instructions' => [
                        'Dissolve gelatin in boiling water',
                        'Add cold water and stir well',
                        'Pour into individual cups',
                        'Refrigerate 4 hours until firm'
                    ],
                    'notes' => 'Easy to swallow. Can add soft fruit pieces. Hydrating.'
                ],
                [
                    'name' => 'Rice Pudding',
                    'description' => 'Creamy rice pudding with raisins and cinnamon',
                    'ingredients' => [
                        'White rice, cooked - 8 lbs',
                        'Low-fat milk - 2 gallons',
                        'Sugar substitute - 2 cups',
                        'Eggs - 24',
                        'Raisins - 2 lbs',
                        'Cinnamon - 3 tablespoons',
                        'Vanilla extract - 3 tablespoons'
                    ],
                    'instructions' => [
                        'Combine cooked rice and milk in large pot',
                        'Heat gently, stirring often',
                        'Whisk eggs with sugar substitute',
                        'Temper eggs into rice mixture',
                        'Cook until thick, stirring constantly',
                        'Add raisins, cinnamon, vanilla',
                        'Serve warm or chilled'
                    ],
                    'notes' => 'Soft texture. Raisins add natural sweetness and fiber.'
                ],
                [
                    'name' => 'Peach Cobbler',
                    'description' => 'Warm fruit cobbler with soft cake topping',
                    'ingredients' => [
                        'Canned peaches (in juice, drained) - 3 large cans (#10)',
                        'Sugar substitute - 1 cup',
                        'Cinnamon - 2 tablespoons',
                        'Yellow cake mix - 2 boxes',
                        'Butter, melted - 1 lb'
                    ],
                    'instructions' => [
                        'Preheat oven to 350°F',
                        'Place peaches in large baking pans',
                        'Sprinkle with sugar substitute and cinnamon',
                        'Top with dry cake mix',
                        'Drizzle melted butter over top',
                        'Bake 35-40 minutes until golden'
                    ],
                    'notes' => 'Serve warm. Soft fruit is easy to eat.'
                ],
                [
                    'name' => 'Yogurt Parfait',
                    'description' => 'Layered low-fat yogurt with granola and berries',
                    'ingredients' => [
                        'Low-fat vanilla yogurt - 2 gallons',
                        'Soft granola - 2 lbs',
                        'Mixed berries (fresh or thawed frozen) - 4 lbs',
                        'Honey - 1 cup (optional)'
                    ],
                    'instructions' => [
                        'Layer yogurt in cups',
                        'Add layer of soft granola',
                        'Top with berries',
                        'Drizzle with honey if desired',
                        'Serve immediately'
                    ],
                    'notes' => 'Good source of calcium and probiotics. Use soft granola only.'
                ]
            ],
            'fruits' => [
                [
                    'name' => 'Watermelon Cubes',
                    'description' => 'Fresh seedless watermelon cut into bite-sized cubes',
                    'ingredients' => [
                        'Seedless watermelon - 20 lbs (yields ~12 lbs cubed)'
                    ],
                    'instructions' => [
                        'Wash watermelon exterior',
                        'Cut in half, then quarters',
                        'Remove rind',
                        'Cut flesh into 1/2 inch cubes',
                        'Check for any seeds and remove',
                        'Serve chilled in 4 oz portions'
                    ],
                    'notes' => 'Very hydrating. Soft and easy to chew. Seedless variety only.'
                ],
                [
                    'name' => 'Cantaloupe Cubes',
                    'description' => 'Sweet cantaloupe melon cut into soft cubes',
                    'ingredients' => [
                        'Ripe cantaloupe - 15 lbs (yields ~10 lbs cubed)'
                    ],
                    'instructions' => [
                        'Wash melon exterior thoroughly',
                        'Cut in half and scoop out seeds',
                        'Cut into wedges and remove rind',
                        'Cut flesh into 1/2 inch cubes',
                        'Serve chilled in 4 oz portions'
                    ],
                    'notes' => 'High in vitamin A and C. Choose very ripe for soft texture.'
                ],
                [
                    'name' => 'Honeydew Cubes',
                    'description' => 'Mild, sweet honeydew melon cubed',
                    'ingredients' => [
                        'Ripe honeydew melon - 15 lbs (yields ~10 lbs cubed)'
                    ],
                    'instructions' => [
                        'Wash exterior well',
                        'Cut in half and remove seeds',
                        'Cut into sections and remove rind',
                        'Cube into 1/2 inch pieces',
                        'Keep refrigerated until serving'
                    ],
                    'notes' => 'Gentle flavor. Very soft when ripe. Hydrating.'
                ],
                [
                    'name' => 'Strawberries (Quartered)',
                    'description' => 'Fresh strawberries hulled and quartered',
                    'ingredients' => [
                        'Fresh strawberries - 10 lbs'
                    ],
                    'instructions' => [
                        'Rinse strawberries gently',
                        'Remove stems and hull',
                        'Cut large berries into quarters',
                        'Cut medium berries in half',
                        'Leave small berries whole or halved',
                        'Serve in 4 oz portions'
                    ],
                    'notes' => 'High in vitamin C. Soft texture. Check for firmness.'
                ],
                [
                    'name' => 'Blueberries',
                    'description' => 'Fresh or frozen blueberries, soft and sweet',
                    'ingredients' => [
                        'Fresh or frozen blueberries - 8 lbs'
                    ],
                    'instructions' => [
                        'If using fresh: rinse gently and drain',
                        'If using frozen: thaw completely',
                        'Serve in 4 oz portions',
                        'Can be slightly mashed for easier swallowing'
                    ],
                    'notes' => 'Antioxidant-rich. Small size is manageable. Soft when ripe.'
                ],
                [
                    'name' => 'Banana Slices',
                    'description' => 'Ripe bananas sliced into rounds',
                    'ingredients' => [
                        'Ripe bananas - 10 lbs (about 30 bananas)'
                    ],
                    'instructions' => [
                        'Peel bananas',
                        'Slice into 1/4 inch rounds',
                        'Serve immediately to prevent browning',
                        'Can toss with lemon juice if needed'
                    ],
                    'notes' => 'Very soft texture. High in potassium. Easy to mash if needed.'
                ],
                [
                    'name' => 'Peaches (Diced, Canned)',
                    'description' => 'Canned peaches in juice, diced into small pieces',
                    'ingredients' => [
                        'Canned peaches in 100% juice - 2 large cans (#10)',
                        'Drain and dice into 1/2 inch pieces'
                    ],
                    'instructions' => [
                        'Drain peaches well',
                        'Dice into 1/2 inch cubes',
                        'Serve chilled in 4 oz portions'
                    ],
                    'notes' => 'Very soft. No added sugar when using juice-packed. Year-round availability.'
                ],
                [
                    'name' => 'Pears (Diced, Canned)',
                    'description' => 'Soft canned pears in juice, diced',
                    'ingredients' => [
                        'Canned pears in 100% juice - 2 large cans (#10)'
                    ],
                    'instructions' => [
                        'Drain pears',
                        'Cut into 1/2 inch cubes',
                        'Check for any hard pieces and remove',
                        'Serve chilled'
                    ],
                    'notes' => 'Very soft and gentle. Good fiber source. Easy to digest.'
                ],
                [
                    'name' => 'Mandarin Oranges',
                    'description' => 'Canned mandarin orange segments',
                    'ingredients' => [
                        'Canned mandarin oranges in juice - 3 large cans (#10)'
                    ],
                    'instructions' => [
                        'Drain oranges gently',
                        'Check segments for any tough membranes',
                        'Serve in 4 oz portions'
                    ],
                    'notes' => 'Soft segments. Vitamin C. Easy to eat. No peeling required.'
                ],
                [
                    'name' => 'Pineapple Chunks',
                    'description' => 'Canned pineapple chunks in juice',
                    'ingredients' => [
                        'Canned pineapple chunks in 100% juice - 2 large cans (#10)'
                    ],
                    'instructions' => [
                        'Drain pineapple',
                        'Check chunk size - cut larger pieces in half',
                        'Ensure all pieces are bite-sized',
                        'Serve chilled'
                    ],
                    'notes' => 'Sweet and tangy. Contains enzymes that aid digestion. Soft texture.'
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

    private function getDefaultDessert()
    {
        return ['name' => 'Sugar-Free Pudding', 'description' => 'Creamy vanilla pudding', 'ingredients' => [], 'instructions' => []];
    }

    private function getDefaultBreakfast()
    {
        return ['name' => 'Scrambled Eggs', 'description' => 'Soft scrambled eggs', 'ingredients' => [], 'instructions' => []];
    }
}
?>