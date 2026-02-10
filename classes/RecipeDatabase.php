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
     * Get random soup recipe, excluding already used names
     */
    public function getRandomSoup($excludeNames = [])
    {
        $soups = $this->recipes['soups'] ?? [];
        $soups = $this->filterExcluded($soups, $excludeNames);
        if (empty($soups)) {
            $soups = $this->recipes['soups'] ?? [];
        }
        return $soups[array_rand($soups)] ?? $this->getDefaultSoup();
    }

    /**
     * Get random daily special, excluding already used names and specific meat types
     */
    public function getRandomSpecial($excludeNames = [], $excludeMeatTypes = [])
    {
        $specials = $this->recipes['specials'] ?? [];
        $specials = $this->filterExcluded($specials, $excludeNames);
        if (!empty($excludeMeatTypes)) {
            $specials = $this->filterByMeatType($specials, $excludeMeatTypes);
        }
        if (empty($specials)) {
            $specials = $this->filterExcluded($this->recipes['specials'] ?? [], $excludeNames);
        }
        if (empty($specials)) {
            $specials = $this->recipes['specials'] ?? [];
        }
        return $specials[array_rand($specials)] ?? $this->getDefaultSpecial();
    }

    /**
     * Get random salad, excluding already used names
     */
    public function getRandomSalad($excludeNames = [])
    {
        $salads = $this->recipes['salads'] ?? [];
        $salads = $this->filterExcluded($salads, $excludeNames);
        if (empty($salads)) {
            $salads = $this->recipes['salads'] ?? [];
        }
        return $salads[array_rand($salads)] ?? $this->getDefaultSalad();
    }

    /**
     * Get random burger, excluding already used names and specific meat types
     */
    public function getRandomBurger($excludeNames = [], $excludeMeatTypes = [])
    {
        $burgers = $this->recipes['burgers'] ?? [];
        $burgers = $this->filterExcluded($burgers, $excludeNames);
        if (!empty($excludeMeatTypes)) {
            $burgers = $this->filterByMeatType($burgers, $excludeMeatTypes);
        }
        if (empty($burgers)) {
            $burgers = $this->filterExcluded($this->recipes['burgers'] ?? [], $excludeNames);
        }
        if (empty($burgers)) {
            $burgers = $this->recipes['burgers'] ?? [];
        }
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
     * Get the meat type for a recipe
     */
    public function getMeatType($recipe)
    {
        return $recipe['meat_type'] ?? 'none';
    }

    /**
     * Filter out recipes with names in the exclude list
     */
    private function filterExcluded($items, $excludeNames)
    {
        if (empty($excludeNames)) {
            return $items;
        }
        return array_values(array_filter($items, function ($item) use ($excludeNames) {
            return !in_array($item['name'], $excludeNames);
        }));
    }

    /**
     * Filter out recipes with meat types in the exclude list
     */
    private function filterByMeatType($items, $excludeMeatTypes)
    {
        return array_values(array_filter($items, function ($item) use ($excludeMeatTypes) {
            $meatType = $item['meat_type'] ?? 'none';
            return !in_array($meatType, $excludeMeatTypes);
        }));
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
                ],
                [
                    'name' => 'Cream of Mushroom Soup',
                    'description' => 'Rich and earthy mushroom soup with cream',
                    'ingredients' => [
                        'Button mushrooms, sliced - 6 lbs',
                        'Low-sodium chicken broth - 3 gallons',
                        'Heavy cream - 1 quart',
                        'Onion, diced - 1 lb',
                        'Butter - 0.5 lb',
                        'Garlic, minced - 0.25 lb'
                    ],
                    'notes' => 'Blend partially for chunky texture or fully for smooth.'
                ],
                [
                    'name' => 'Minestrone Soup',
                    'description' => 'Hearty Italian vegetable soup with pasta',
                    'ingredients' => [
                        'Low-sodium vegetable broth - 4 gallons',
                        'Diced tomatoes - 3 lbs',
                        'Zucchini, diced - 2 lbs',
                        'Carrots, diced - 1.5 lbs',
                        'Celery, diced - 1 lb',
                        'Small pasta - 2 lbs',
                        'Kidney beans - 2 lbs'
                    ],
                    'notes' => 'Cook pasta separately and add at end to prevent sogginess.'
                ],
                [
                    'name' => 'Potato Leek Soup',
                    'description' => 'Smooth and comforting potato soup with leeks',
                    'ingredients' => [
                        'Potatoes, peeled and cubed - 8 lbs',
                        'Leeks, sliced - 3 lbs',
                        'Low-sodium chicken broth - 3 gallons',
                        'Heavy cream - 1 quart',
                        'Butter - 0.5 lb'
                    ],
                    'notes' => 'Blend until smooth for silky texture.'
                ]
            ],
            'specials' => [
                [
                    'name' => 'Baked Salmon with Lemon',
                    'description' => 'Tender baked salmon fillet with fresh lemon butter sauce',
                    'meat_type' => 'fish',
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
                    'meat_type' => 'chicken',
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
                    'meat_type' => 'beef',
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
                ],
                [
                    'name' => 'Baked Tilapia with Herbs',
                    'description' => 'Light and flaky tilapia with herb butter',
                    'meat_type' => 'fish',
                    'ingredients' => [
                        'Tilapia fillets - 8 lbs',
                        'Butter - 0.5 lb',
                        'Fresh parsley - 1 cup',
                        'Lemon juice - 0.5 cup',
                        'Garlic, minced - 0.25 lb'
                    ],
                    'instructions' => [
                        'Preheat oven to 400°F',
                        'Place fillets on parchment-lined baking sheets',
                        'Top with herb butter mixture',
                        'Bake for 12-15 minutes until flakes easily'
                    ],
                    'notes' => 'Mild flavor, excellent for seniors.'
                ],
                [
                    'name' => 'Turkey Meatloaf',
                    'description' => 'Moist turkey meatloaf with savory glaze',
                    'meat_type' => 'turkey',
                    'ingredients' => [
                        'Ground turkey - 10 lbs',
                        'Breadcrumbs - 2 lbs',
                        'Eggs - 12',
                        'Onion, finely diced - 1 lb',
                        'Ketchup - 2 cups'
                    ],
                    'instructions' => [
                        'Mix turkey, breadcrumbs, eggs, and onion',
                        'Form into loaves on baking sheets',
                        'Top with ketchup glaze',
                        'Bake at 350°F for 45-55 minutes'
                    ],
                    'notes' => 'Lower fat alternative to beef meatloaf.'
                ],
                [
                    'name' => 'Pork Tenderloin with Apples',
                    'description' => 'Tender roasted pork with sweet apple compote',
                    'meat_type' => 'pork',
                    'ingredients' => [
                        'Pork tenderloin - 8 lbs',
                        'Apples, sliced - 4 lbs',
                        'Brown sugar - 1 cup',
                        'Cinnamon - 2 tablespoons',
                        'Butter - 0.5 lb'
                    ],
                    'instructions' => [
                        'Season pork with salt, pepper, and herbs',
                        'Sear in hot pan, then roast at 375°F for 20-25 minutes',
                        'Sauté apples with butter, brown sugar, and cinnamon',
                        'Slice pork and serve with apple compote'
                    ],
                    'notes' => 'Let pork rest before slicing for juicier results.'
                ],
                [
                    'name' => 'Vegetable Lasagna',
                    'description' => 'Layered pasta with seasonal vegetables and cheese',
                    'meat_type' => 'vegetarian',
                    'ingredients' => [
                        'Lasagna noodles - 3 lbs',
                        'Ricotta cheese - 4 lbs',
                        'Mozzarella cheese - 3 lbs',
                        'Zucchini, sliced - 3 lbs',
                        'Spinach - 2 lbs',
                        'Marinara sauce - 1 gallon'
                    ],
                    'instructions' => [
                        'Cook lasagna noodles until al dente',
                        'Layer noodles, ricotta, vegetables, sauce, mozzarella',
                        'Repeat layers, top with mozzarella',
                        'Bake at 375°F for 35-40 minutes'
                    ],
                    'notes' => 'Excellent vegetarian option. Cut into manageable portions.'
                ],
                [
                    'name' => 'Chicken Pot Pie',
                    'description' => 'Creamy chicken and vegetables in flaky pastry',
                    'meat_type' => 'chicken',
                    'ingredients' => [
                        'Cooked chicken breast, diced - 8 lbs',
                        'Mixed vegetables (peas, carrots, corn) - 4 lbs',
                        'Cream of chicken soup - 1 gallon',
                        'Pie crust dough - 3 lbs'
                    ],
                    'instructions' => [
                        'Mix chicken, vegetables, and cream soup',
                        'Pour into baking dishes lined with pie crust',
                        'Top with pie crust and crimp edges',
                        'Bake at 375°F for 30-35 minutes until golden'
                    ],
                    'notes' => 'Comfort food favorite. Ensure filling is hot throughout.'
                ],
                [
                    'name' => 'Beef Stroganoff',
                    'description' => 'Tender beef strips in creamy mushroom sauce over egg noodles',
                    'meat_type' => 'beef',
                    'ingredients' => [
                        'Beef sirloin, sliced thin - 8 lbs',
                        'Mushrooms, sliced - 3 lbs',
                        'Sour cream - 2 quarts',
                        'Egg noodles - 4 lbs',
                        'Onion, diced - 1 lb'
                    ],
                    'instructions' => [
                        'Sear beef strips in batches',
                        'Sauté mushrooms and onions',
                        'Add broth and simmer until tender',
                        'Stir in sour cream, serve over cooked noodles'
                    ],
                    'notes' => 'Very tender and easy to chew.'
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
                ],
                [
                    'name' => 'Fruit and Cottage Cheese Salad',
                    'description' => 'Refreshing fruit medley with creamy cottage cheese',
                    'ingredients' => [
                        'Cottage cheese - 5 lbs',
                        'Peaches, diced - 3 lbs',
                        'Pineapple chunks - 2 lbs',
                        'Grapes, halved - 2 lbs',
                        'Honey - 1 cup'
                    ],
                    'instructions' => [
                        'Portion cottage cheese into bowls',
                        'Top with mixed fruit',
                        'Drizzle with honey'
                    ],
                    'notes' => 'Good source of protein. Use soft, ripe fruit.'
                ],
                [
                    'name' => 'Waldorf Salad',
                    'description' => 'Classic apple and walnut salad with creamy dressing',
                    'ingredients' => [
                        'Apples, diced - 4 lbs',
                        'Celery, finely diced - 1 lb',
                        'Walnuts, chopped fine - 1 lb',
                        'Raisins - 1 lb',
                        'Mayonnaise - 1 quart'
                    ],
                    'instructions' => [
                        'Dice apples and toss with lemon juice',
                        'Mix with celery, walnuts, and raisins',
                        'Fold in mayonnaise gently'
                    ],
                    'notes' => 'Chop walnuts finely for easy chewing.'
                ],
                [
                    'name' => 'Cucumber Tomato Salad',
                    'description' => 'Light and refreshing summer salad with herb dressing',
                    'ingredients' => [
                        'Cucumbers, sliced - 5 lbs',
                        'Tomatoes, diced - 4 lbs',
                        'Red onion, thinly sliced - 0.5 lb',
                        'Olive oil - 1 cup',
                        'Red wine vinegar - 0.5 cup'
                    ],
                    'instructions' => [
                        'Slice cucumbers and dice tomatoes',
                        'Thinly slice red onion',
                        'Toss with oil and vinegar dressing'
                    ],
                    'notes' => 'Best served chilled. Peel cucumbers if needed.'
                ],
                [
                    'name' => 'Coleslaw',
                    'description' => 'Creamy cabbage slaw with carrots',
                    'ingredients' => [
                        'Green cabbage, shredded - 6 lbs',
                        'Carrots, shredded - 2 lbs',
                        'Mayonnaise - 1 quart',
                        'Sugar - 0.5 cup',
                        'Apple cider vinegar - 0.5 cup'
                    ],
                    'instructions' => [
                        'Shred cabbage and carrots finely',
                        'Mix dressing ingredients',
                        'Toss with vegetables and refrigerate'
                    ],
                    'notes' => 'Shred very fine for easier chewing.'
                ],
                [
                    'name' => 'Three Bean Salad',
                    'description' => 'Tangy marinated bean salad with fresh herbs',
                    'ingredients' => [
                        'Green beans, cut - 3 lbs',
                        'Kidney beans - 2 lbs',
                        'Chickpeas - 2 lbs',
                        'Red onion, diced - 0.5 lb',
                        'Italian dressing - 1 quart'
                    ],
                    'instructions' => [
                        'Cook green beans until tender',
                        'Drain and rinse canned beans',
                        'Toss all beans with dressing and refrigerate'
                    ],
                    'notes' => 'Excellent protein and fiber source.'
                ]
            ],
            'burgers' => [
                [
                    'name' => 'Lean Beef Burger',
                    'description' => 'Juicy ground beef patty on soft bun with toppings',
                    'meat_type' => 'beef',
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
                    'meat_type' => 'turkey',
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
                ],
                [
                    'name' => 'Chicken Burger',
                    'description' => 'Seasoned ground chicken patty with lettuce and tomato',
                    'meat_type' => 'chicken',
                    'ingredients' => [
                        'Ground chicken - 8 lbs',
                        'Breadcrumbs - 1 lb',
                        'Eggs - 8',
                        'Soft burger buns - 30 each',
                        'Lettuce and tomato - 2 lbs'
                    ],
                    'instructions' => [
                        'Mix chicken, breadcrumbs, eggs, and seasoning',
                        'Form into 2-3 oz patties',
                        'Cook on griddle to 165°F internal temp',
                        'Serve on soft buns with toppings'
                    ],
                    'notes' => 'Lean and flavorful option.'
                ],
                [
                    'name' => 'Veggie Burger',
                    'description' => 'Hearty black bean and vegetable patty',
                    'meat_type' => 'vegetarian',
                    'ingredients' => [
                        'Black beans, mashed - 6 lbs',
                        'Breadcrumbs - 2 lbs',
                        'Eggs - 8',
                        'Onion, finely diced - 1 lb',
                        'Soft burger buns - 30 each'
                    ],
                    'instructions' => [
                        'Mash beans and mix with breadcrumbs, eggs, onion',
                        'Form into 2-3 oz patties',
                        'Cook on greased griddle until firm and heated through'
                    ],
                    'notes' => 'Excellent vegetarian protein source.'
                ],
                [
                    'name' => 'Salmon Burger',
                    'description' => 'Flaky salmon patty with dill sauce on soft bun',
                    'meat_type' => 'fish',
                    'ingredients' => [
                        'Canned salmon, drained - 8 lbs',
                        'Breadcrumbs - 1 lb',
                        'Eggs - 8',
                        'Dill - 0.5 cup',
                        'Soft burger buns - 30 each'
                    ],
                    'instructions' => [
                        'Mix salmon, breadcrumbs, eggs, and dill',
                        'Form into 2-3 oz patties',
                        'Cook on griddle until golden on both sides'
                    ],
                    'notes' => 'Great source of omega-3 fatty acids.'
                ],
                [
                    'name' => 'Pork Burger',
                    'description' => 'Seasoned ground pork patty with BBQ sauce',
                    'meat_type' => 'pork',
                    'ingredients' => [
                        'Ground pork - 8 lbs',
                        'BBQ sauce - 1 cup',
                        'Breadcrumbs - 1 lb',
                        'Eggs - 8',
                        'Soft burger buns - 30 each'
                    ],
                    'instructions' => [
                        'Mix pork, breadcrumbs, eggs, and seasoning',
                        'Form into 2-3 oz patties',
                        'Cook on griddle to 160°F internal temp',
                        'Brush with BBQ sauce before serving'
                    ],
                    'notes' => 'Flavorful alternative to beef.'
                ],
                [
                    'name' => 'Mushroom Swiss Burger',
                    'description' => 'Beef patty topped with sautéed mushrooms and Swiss cheese',
                    'meat_type' => 'beef',
                    'ingredients' => [
                        'Lean ground beef - 8 lbs',
                        'Mushrooms, sliced - 3 lbs',
                        'Swiss cheese slices - 2 lbs',
                        'Soft burger buns - 30 each'
                    ],
                    'instructions' => [
                        'Form beef into 2-3 oz patties',
                        'Cook on griddle to 160°F internal temp',
                        'Sauté mushrooms in butter',
                        'Top patty with mushrooms and Swiss cheese'
                    ],
                    'notes' => 'A classic flavor combination.'
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