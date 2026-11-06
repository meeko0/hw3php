<?php require_once 'order_functions.php'; //starts the session before any output ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chez Session Bistro | Place an Order</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <a class="skip-link" href="#main">Skip to order form</a>
    <header class="site-header">
        <p class="brand">Chez Session Bistro</p>
        <nav aria-label="Main">
            <ul>
                <li><a href="index.php" aria-current="page">Order</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
        </nav>
    </header>

    <main id="main">
        <h1>Dessert &amp; Drink Order</h1>
        <p>Choose a dessert, a drink and a drink size. Your last choices are remembered for this session.</p>

        <form class="card" action="process_order.php" method="post">
            <div class="field">
                <label for="dessert">Dessert</label>
                <select id="dessert" name="dessert">
                    <option value="none">-- Choose a dessert --</option>
                    <?php foreach (array_keys(DESSERTS) as $dessert): ?>
                    <option value="<?= e($dessert) ?>" <?= dessertSelected($dessert) ?>><?= e($dessert) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="drink">Drink</label>
                <select id="drink" name="drink">
                    <option value="none">-- Choose a drink --</option>
                    <?php foreach (array_keys(DRINKS) as $drink): ?>
                    <option value="<?= e($drink) ?>" <?= drinkSelected($drink) ?>><?= e($drink) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <fieldset class="field">
                <legend>Drink size</legend>
                <?php foreach (array_keys(DRINK_SIZES) as $size): ?>
                <div class="radio">
                    <input type="radio" id="size-<?= e(strtolower($size)) ?>" name="drinkSize" value="<?= e($size) ?>" <?= drinkSizeSelected($size) ?>>
                    <label for="size-<?= e(strtolower($size)) ?>"><?= e($size) ?></label>
                </div>
                <?php endforeach; ?>
            </fieldset>

            <div class="actions">
                <button type="submit">Place Order</button>
                <a class="button secondary" href="forget_order.php">Forget Order</a>
            </div>
        </form>
    </main>

    <footer class="site-footer">
        <p>CSC 4370 Homework 3 &middot; PHP sessions demo</p>
    </footer>
</body>
</html>
