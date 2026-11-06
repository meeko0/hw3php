<?php require_once 'order_functions.php'; //keeps the same session on every page ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chez Session Bistro | About</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    <header class="site-header">
        <p class="brand">Chez Session Bistro</p>
        <nav aria-label="Main">
            <ul>
                <li><a href="index.php">Order</a></li>
                <li><a href="about.php" aria-current="page">About</a></li>
            </ul>
        </nav>
    </header>

    <main id="main">
        <h1>About This Site</h1>
        <section class="card">
            <h2>How it works</h2>
            <ol>
                <li><code>index.php</code> shows the order form. Saved choices are restored with the <code>selected</code> and <code>checked</code> attributes.</li>
                <li><code>process_order.php</code> calls <code>order()</code>, which copies the posted values into <code>$_SESSION</code>, then shows the receipt, feedback and price.</li>
                <li><code>forget_order.php</code> removes the three order keys, destroys the session and redirects back to the form.</li>
                <li><code>next_customer.php</code> does the same reset and also expires the session cookie, so the next person on a shared computer starts a brand-new session.</li>
            </ol>
            <p>The order is stored on the server in a PHP session. The browser only keeps the session ID cookie.</p>
        </section>

        <section class="card">
            <h2>Current session</h2>
            <dl class="receipt">
                <dt>Dessert</dt>
                <dd><?= e(sessionValue('dessert')) ?></dd>
                <dt>Drink</dt>
                <dd><?= e(sessionValue('drink')) ?></dd>
                <dt>Drink size</dt>
                <dd><?= e(sessionValue('drinkSize')) ?></dd>
            </dl>
        </section>
    </main>

    <footer class="site-footer">
        <p>CSC 4370 Homework 3 &middot; PHP sessions demo</p>
    </footer>
</body>
</html>
