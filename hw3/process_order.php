<?php
require_once 'order_functions.php'; //starts the session before any output
$orderSummary = order(); //saves the posted choices into $_SESSION; values in the summary are already escaped
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chez Session Bistro | Your Receipt</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <a class="skip-link" href="#main">Skip to receipt</a>
    <header class="site-header">
        <p class="brand">Chez Session Bistro</p>
        <nav aria-label="Main">
            <ul>
                <li><a href="index.php">Order</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
        </nav>
    </header>

    <main id="main">
        <h1>Your Receipt</h1>

        <div class="cards">
            <section class="card receipt-card" aria-labelledby="receipt-heading">
                <h2 id="receipt-heading">Stored order</h2>
                <p><?= $orderSummary ?></p>
            </section>

            <section class="card insight-card" aria-labelledby="feedback-heading">
                <h2 id="feedback-heading">Order feedback</h2>
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
                <p class="insight-note full-width-note"><?= e(pricingNote()) ?></p>
            </section>
        </div>

        <div class="actions">
            <a class="button" href="index.php">Order Again!</a>
            <a class="button secondary" href="forget_order.php">Forget Order</a>
        </div>
    </main>

    <footer class="site-footer">
        <p>CSC 4370 Homework 3 &middot; PHP sessions demo</p>
    </footer>
</body>
</html>
