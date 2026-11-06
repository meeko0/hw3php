<?php
require_once 'order_functions.php'; //starts the session before any output
order(); //save the posted choices into $_SESSION
$note = sizeNote();
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
            <section class="card" aria-labelledby="receipt-heading">
                <h2 id="receipt-heading">Receipt</h2>
                <dl class="receipt">
                    <dt>Dessert</dt>
                    <dd><?= e(sessionValue('dessert')) ?></dd>
                    <dt>Drink</dt>
                    <dd><?= e(sessionValue('drink')) ?></dd>
                    <dt>Drink size</dt>
                    <dd><?= e(sessionValue('drinkSize')) ?></dd>
                </dl>
            </section>

            <section class="card" aria-labelledby="feedback-heading">
                <h2 id="feedback-heading">Chef's Feedback</h2>
                <p><?= e(pairingFeedback()) ?></p>
                <?php if ($note !== ''): ?>
                <p class="note"><?= e($note) ?></p>
                <?php endif; ?>
            </section>

            <section class="card" aria-labelledby="price-heading">
                <h2 id="price-heading">Price</h2>
                <table class="prices">
                    <caption class="visually-hidden">Price breakdown</caption>
                    <tbody>
                        <tr>
                            <th scope="row">Dessert</th>
                            <td><?= e(formatPrice(dessertPrice())) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Drink</th>
                            <td><?= e(formatPrice(drinkPrice())) ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row">Total</th>
                            <td><?= e(formatPrice(orderTotal())) ?></td>
                        </tr>
                    </tfoot>
                </table>
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
