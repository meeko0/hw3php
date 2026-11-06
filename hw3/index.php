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
        <?php if (isset($_GET['handoff'])): ?>
        <p class="status" role="status">Order cleared. Ready for the next customer.</p>
        <?php endif; ?>
        <p>Choose a dessert, a drink and a drink size. Your last choices are remembered for this session.</p>

        <div class="layout">
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

            <div class="side">
                <section class="card insight-card" aria-labelledby="guidance-heading">
                    <h2 id="guidance-heading">Live guidance</h2>
                    <p><?= e(completionMessage()) ?></p>
                    <p class="insight-note"><?= e(pairingSuggestion()) ?></p>
                </section>

                <section class="card insight-card pricing-grid" aria-label="Pricing">
                    <div>
                        <h2>Estimated total</h2>
                        <p class="price-figure"><?= e(formattedTotal()) ?></p>
                    </div>
                    <div>
                        <h2>Order profile</h2>
                        <p><?= e(orderProfile()) ?></p>
                    </div>
                </section>

                <!--U1 handoff: server-side "Next customer" for a shared kiosk browser (no JavaScript)-->
                <form class="card handoff" action="next_customer.php" method="post">
                    <button type="submit" class="button secondary">Next customer (clear this order)</button>
                </form>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <p>CSC 4370 Homework 3 &middot; PHP sessions demo</p>
    </footer>
</body>
</html>
