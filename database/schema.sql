-- Assisted Living Menu Generator Database Schema

-- Recipes Table
CREATE TABLE recipes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    description VARCHAR(500),
    type ENUM('soup', 'special', 'salad', 'burger', 'breakfast_monfri', 'breakfast_saturday', 'breakfast_sunday') NOT NULL,
    ingredients JSON NOT NULL,
    instructions JSON,
    notes TEXT,
    dietary_tags JSON,
    serving_size_base INT DEFAULT 1,
    prep_time INT COMMENT 'Minutes',
    cook_time INT COMMENT 'Minutes',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_active (is_active)
);

-- Menus Table (stores generated menus)
CREATE TABLE generated_menus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    menu_uuid VARCHAR(36) UNIQUE,
    week_number VARCHAR(10) NOT NULL,
    year INT NOT NULL,
    serving_size INT NOT NULL,
    menu_data JSON NOT NULL,
    export_count INT DEFAULT 0,
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_week (week_number, year),
    INDEX idx_created (created_at)
);

-- Dietary Restrictions Table
CREATE TABLE dietary_restrictions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Recipe Dietary Tags (Junction Table)
CREATE TABLE recipe_dietary_tags (
    recipe_id INT NOT NULL,
    dietary_id INT NOT NULL,
    PRIMARY KEY (recipe_id, dietary_id),
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (dietary_id) REFERENCES dietary_restrictions(id) ON DELETE CASCADE
);

-- Facility Preferences Table
CREATE TABLE facility_preferences (
    id INT PRIMARY KEY AUTO_INCREMENT,
    facility_name VARCHAR(255),
    default_serving_size INT DEFAULT 25,
    dietary_restrictions JSON,
    excluded_recipes JSON,
    preferred_recipes JSON,
    color_scheme VARCHAR(50) DEFAULT 'default',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create default dietary restrictions
INSERT INTO dietary_restrictions (name, description) VALUES
('Low Sodium', 'Restricted sodium content for heart health'),
('Vegetarian', 'No meat or poultry'),
('Vegan', 'No animal products'),
('Gluten Free', 'No wheat or gluten-containing ingredients'),
('Dairy Free', 'No milk or dairy products'),
('Pureed', 'Blended or finely chopped for swallowing difficulties'),
('Low Sugar', 'Reduced sugar content for diabetes management'),
('Nut Free', 'No tree nuts or peanuts'),
('Low Fat', 'Reduced fat content'),
('Kosher', 'Prepared according to Jewish dietary laws');

-- Add indexes for performance
CREATE INDEX idx_recipes_type_active ON recipes(type, is_active);
CREATE INDEX idx_menus_created_year ON generated_menus(created_at, year);
