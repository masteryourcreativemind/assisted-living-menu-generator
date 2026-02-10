<div class="content">
    <!-- Settings Section -->
    <div class="section">
        <h2>⚙️ Generator Settings</h2>
        <div class="input-group">
            <label for="week-select">Select Week:</label>
            <input type="week" id="week-select">
        </div>
        <div class="input-group">
            <label for="serving-size">Serving Size (residents):</label>
            <select id="serving-size">
                <option value="10">10 residents</option>
                <option value="15">15 residents</option>
                <option value="20">20 residents</option>
                <option value="25" selected>25 residents</option>
                <option value="30">30 residents</option>
                <option value="40">40 residents</option>
                <option value="50">50 residents</option>
                <option value="75">75 residents</option>
                <option value="100">100 residents</option>
            </select>
        </div>
        <button class="btn-generate" onclick="generateFullWeek()">🚀 Generate Full Week Menu</button>
        <button class="btn-generate" onclick="regenerateWeeklyBreakfastSP()" style="background: #f59e0b;">🍳 Regenerate Weekly Breakfast SP</button>
        <button class="btn-generate" onclick="regenerateDay()" style="background: #3182ce;">🔄 Regenerate Single
            Day</button>
    </div>

    <!-- Day Selector for Regenerate -->
    <div class="section">
        <h2>📅 Select Day to Regenerate</h2>
        <div class="input-group">
            <label for="day-regenerate">Choose Day:</label>
            <select id="day-regenerate">
                <option value="0">Monday</option>
                <option value="1">Tuesday</option>
                <option value="2">Wednesday</option>
                <option value="3">Thursday</option>
                <option value="4">Friday</option>
                <option value="5">Saturday</option>
                <option value="6">Sunday</option>
            </select>
        </div>
        <p style="color: #666; font-size: 0.9em; margin-top: 10px;">💡 Tip: Use this to replace just one day's menu
            while keeping others.</p>

        <!-- Saved Menus Section -->
        <div id="saved-menus-section" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
            <h3 style="color: #667eea; margin-bottom: 10px;">📋 Previously Saved Menus</h3>
            <div id="saved-menus-list">
                <p style="color: #999; font-size: 0.9em;">No saved menus found.</p>
            </div>
        </div>
    </div>

    <!-- Menu Display Area -->
    <div class="menu-display" id="menu-display">
        <p class="loading">Select a week and click "Generate Full Week Menu" to create a menu</p>
    </div>

    <!-- Print/Export Section -->
    <div class="print-section">
        <button class="btn-export" onclick="exportMenuAsText()">📥 Export as Text</button>
        <button class="btn-export" onclick="exportMenuAsCSV()">📊 Export as CSV</button>
        <button class="btn-export" onclick="exportMenuAsJSON()">📑 Export as JSON</button>
        <button class="btn-export" onclick="window.print()">🖨️ Print Menu</button>
        <button class="btn-clear" onclick="clearMenu()">🗑️ Clear Menu</button>
    </div>
</div>
</div>

<script>
    // Menu Data Storage
    let weeklyMenu = {
        week: null,
        servingSize: 25,
        days: []
    };

    const daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    // Initialize week selector
    function initializeWeek() {
        const today = new Date();
        const year = today.getFullYear();
        const week = Math.ceil(((today - new Date(year, 0, 1)) / 86400000 + 1) / 7);
        const weekInput = document.getElementById('week-select');
        weekInput.value = year + '-W' + String(week).padStart(2, '0');
    }

    // Generate full week menu
    function generateFullWeek() {
        const week = document.getElementById('week-select').value;
        const servingSize = parseInt(document.getElementById('serving-size').value);

        if (!week) {
            alert('Please select a week');
            return;
        }

        showLoading();

        const formData = new FormData();
        formData.append('action', 'generate_week');
        formData.append('week', week);
        formData.append('serving_size', servingSize);

        fetch('<?php echo APP_URL; ?>/index.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    weeklyMenu = data.menu;
                    displayMenu();
                    loadSavedWeeks(); // Refresh saved menus list
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to generate menu');
            });
    }

    // Regenerate single day
    function regenerateDay() {
        if (weeklyMenu.days.length === 0) {
            alert('Please generate full week first');
            return;
        }

        const dayIndex = parseInt(document.getElementById('day-regenerate').value);

        const formData = new FormData();
        formData.append('action', 'regenerate_day');
        formData.append('day_index', dayIndex);

        fetch('<?php echo APP_URL; ?>/index.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    weeklyMenu = data.menu;
                    displayMenu();
                    loadSavedWeeks();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Regenerate weekly breakfast special
    function regenerateWeeklyBreakfastSP() {
        if (weeklyMenu.days.length === 0) {
            alert('Please generate full week first');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'regenerate_breakfast_sp');

        fetch('<?php echo APP_URL; ?>/index.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    weeklyMenu = data.menu;
                    displayMenu();
                    loadSavedWeeks();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Load saved weeks from server (cookie-based)
    function loadSavedWeeks() {
        const formData = new FormData();
        formData.append('action', 'get_saved_weeks');

        fetch('<?php echo APP_URL; ?>/index.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.weeks.length > 0) {
                    displaySavedWeeks(data.weeks);
                }
            })
            .catch(error => console.error('Error loading saved weeks:', error));
    }

    // Display saved weeks in the sidebar
    function displaySavedWeeks(weeks) {
        const container = document.getElementById('saved-menus-list');
        if (weeks.length === 0) {
            container.innerHTML = '<p style="color: #999; font-size: 0.9em;">No saved menus found.</p>';
            return;
        }

        let html = '';
        weeks.forEach(function(w) {
            html += '<button onclick="loadSavedMenu(\'' + w.week + '\')" ' +
                'style="display:block; width:100%; text-align:left; padding:8px 12px; margin:4px 0; ' +
                'background:#f0f4ff; border:1px solid #667eea; border-radius:4px; cursor:pointer; font-size:0.9em;">' +
                '📅 Week: ' + w.week +
                '<br><small style="color:#999;">Generated: ' + w.generated_at + '</small>' +
                '</button>';
        });
        container.innerHTML = html;
    }

    // Load a previously saved menu
    function loadSavedMenu(weekDate) {
        const formData = new FormData();
        formData.append('action', 'load_saved_menu');
        formData.append('week', weekDate);

        fetch('<?php echo APP_URL; ?>/index.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    weeklyMenu = data.menu;
                    displayMenu();
                } else {
                    alert('No saved menu found for this week');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Display menu
    function displayMenu() {
        const display = document.getElementById('menu-display');

        if (!weeklyMenu.days || weeklyMenu.days.length === 0) {
            display.innerHTML = '<div class="loading">No menu generated</div>';
            return;
        }

        let html = '<h2>📅 Weekly Menu (Serves ' + (weeklyMenu.serving_size || '?') + ' residents)</h2>';

        // Display Weekly Breakfast Special prominently
        if (weeklyMenu.weekly_breakfast_sp) {
            let breakfastName = typeof weeklyMenu.weekly_breakfast_sp === 'string'
                ? weeklyMenu.weekly_breakfast_sp
                : weeklyMenu.weekly_breakfast_sp.name;

            html += '<div class="weekly-special-box" style="background: #fef3c7; border: 2px solid #f59e0b; padding: 20px; margin: 20px 0; border-radius: 8px;">' +
                '<h3 style="color: #92400e; margin-top: 0;">🍳 Weekly Breakfast Special (All Days)</h3>';

            if (typeof weeklyMenu.weekly_breakfast_sp === 'object') {
                html += formatRecipeHTML('', weeklyMenu.weekly_breakfast_sp);
            } else {
                html += '<div class="menu-section"><h4>' + breakfastName + '</h4></div>';
            }
            html += '</div>';
        }

        html += '<div class="week-grid">';

        weeklyMenu.days.forEach(function(dayMenu) {
            html += formatDayHTML(dayMenu);
        });

        html += '</div>';
        display.innerHTML = html;
    }

    // Format day as HTML
    function formatDayHTML(dayMenu) {
        // Handle both full recipe objects and compact string names (from saved cookies)
        let soupName = typeof dayMenu.soup === 'object' ? dayMenu.soup.name : dayMenu.soup;
        let specialName = typeof dayMenu.special === 'object' ? dayMenu.special.name : dayMenu.special;
        let saladName = typeof dayMenu.salad === 'object' ? dayMenu.salad.name : dayMenu.salad;
        let burgerName = typeof dayMenu.burger === 'object' ? dayMenu.burger.name : dayMenu.burger;
        let breakfastName = dayMenu.breakfast_sp
            ? (typeof dayMenu.breakfast_sp === 'object' ? dayMenu.breakfast_sp.name : dayMenu.breakfast_sp)
            : 'Weekly Special (see above)';

        let html = '<div class="day-menu">' +
            '<h3>' + dayMenu.day + '</h3>' +
            '<div class="menu-section" style="background: #fef3c7; padding: 10px; border-radius: 4px; margin-bottom: 10px;">' +
            '<h4 style="color: #92400e;">🍳 Breakfast: ' + breakfastName + '</h4>' +
            '<div class="menu-item-desc" style="font-style: italic; color: #78350f;">→ See Weekly Breakfast Special above</div>' +
            '</div>';

        if (typeof dayMenu.soup === 'object') {
            html += formatRecipeHTML('🥘 Soup of the Day', dayMenu.soup);
        } else {
            html += '<div class="menu-section"><h4>🥘 Soup of the Day: ' + soupName + '</h4></div>';
        }

        if (typeof dayMenu.special === 'object') {
            html += formatRecipeHTML('⭐ Daily Special', dayMenu.special);
        } else {
            html += '<div class="menu-section"><h4>⭐ Daily Special: ' + specialName + '</h4></div>';
        }

        if (typeof dayMenu.salad === 'object') {
            html += formatRecipeHTML('🥗 Weekly Salad', dayMenu.salad);
        } else {
            html += '<div class="menu-section"><h4>🥗 Weekly Salad: ' + saladName + '</h4></div>';
        }

        if (typeof dayMenu.burger === 'object') {
            html += formatRecipeHTML('🍔 Weekly Burger', dayMenu.burger);
        } else {
            html += '<div class="menu-section"><h4>🍔 Weekly Burger: ' + burgerName + '</h4></div>';
        }

        html += '</div>';
        return html;
    }

    // Format recipe as HTML
    function formatRecipeHTML(type, recipe) {
        let html = '<div class="menu-section">' +
                '<h4>' + type + ': ' + recipe.name + '</h4>' +
                '<div class="menu-item-desc">' + recipe.description + '</div>';

        if (recipe.ingredients && recipe.ingredients.length > 0) {
            html += '<strong>Ingredients:</strong><ul class="ingredients-list">';
            recipe.ingredients.forEach(function(ing) {
                html += '<li>' + ing + '</li>';
            });
            html += '</ul>';
        }

        if (recipe.instructions && recipe.instructions.length > 0) {
            html += '<strong>Instructions:</strong><ol class="instructions-list">';
            recipe.instructions.forEach(function(inst) {
                html += '<li>' + inst + '</li>';
            });
            html += '</ol>';
        }

        if (recipe.notes) {
            html += '<div class="recipe-notes"><strong>Notes:</strong> ' + recipe.notes + '</div>';
        }

        html += '</div>';
        return html;
    }

    // Export as Text
    function exportMenuAsText() {
        if (!weeklyMenu.days || weeklyMenu.days.length === 0) {
            alert('Please generate a menu first');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'export');
        formData.append('format', 'text');

        fetch('<?php echo APP_URL; ?>/index.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    downloadFile(data.data, data.filename, 'text/plain');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Export as CSV
    function exportMenuAsCSV() {
        if (!weeklyMenu.days || weeklyMenu.days.length === 0) {
            alert('Please generate a menu first');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'export');
        formData.append('format', 'csv');

        fetch('<?php echo APP_URL; ?>/index.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    downloadFile(data.data, data.filename, 'text/csv');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Export as JSON
    function exportMenuAsJSON() {
        if (!weeklyMenu.days || weeklyMenu.days.length === 0) {
            alert('Please generate a menu first');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'export');
        formData.append('format', 'json');

        fetch('<?php echo APP_URL; ?>/index.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    downloadFile(data.data, data.filename, 'application/json');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Download file helper
    function downloadFile(base64Data, filename, mimeType) {
        const binaryString = atob(base64Data);
        const bytes = new Uint8Array(binaryString.length);
        for (let i = 0; i < binaryString.length; i++) {
            bytes[i] = binaryString.charCodeAt(i);
        }
        const blob = new Blob([bytes], { type: mimeType });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
    }

    // Clear Menu
    function clearMenu() {
        if (confirm('Clear the current menu?')) {
            weeklyMenu.days = [];
            document.getElementById('menu-display').innerHTML = '<p class="loading">Menu cleared</p>';
        }
    }

    // Show loading indicator
    function showLoading() {
        const display = document.getElementById('menu-display');
        display.innerHTML = '<div class="loading"><div class="spinner"></div>Generating menu...</div>';
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function () {
        initializeWeek();
        loadSavedWeeks(); // Load previously saved menus from cookies
    });
</script>