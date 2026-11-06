<?php session_start(); //must run before any output so the session cookie header can be sent

//shared helpers for the Chez Session bistro order pages

const NOT_SELECTED = '(not selected)';

//menu: name => base price in dollars
const DESSERTS = [
    'Cheesecake'     => 4.50,
    'Chocolate Cake' => 4.25,
    'Carrot Cake'    => 3.95,
    'Tiramisu'       => 5.25,
];

const DRINKS = [
    'Coffee'        => 2.25,
    'Hot Chocolate' => 2.75,
    'Root Beer'     => 1.95,
    'Tea'           => 1.75,
    'Milk'          => 1.50,
];

//size => multiplier applied to the drink base price
const DRINK_SIZES = [
    'Small'  => 1.00,
    'Medium' => 1.25,
    'Large'  => 1.50,
];

//turn missing, empty, 'none' or unknown values into the fallback text
function cleanChoice($value, array $allowed): string
{
    $value = is_string($value) ? trim($value) : '';
    if ($value === '' || $value === 'none' || !array_key_exists($value, $allowed)) {
        return NOT_SELECTED;
    }
    return $value;
}

//copy the posted form values into the session (only on POST so a refresh/GET keeps the last order)
function order(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return;
    }
    $_SESSION['dessert']   = cleanChoice($_POST['dessert'] ?? '', DESSERTS);
    $_SESSION['drink']     = cleanChoice($_POST['drink'] ?? '', DRINKS);
    $_SESSION['drinkSize'] = cleanChoice($_POST['drinkSize'] ?? '', DRINK_SIZES);
}

//read one session value with the fallback text
function sessionValue(string $key): string
{
    return $_SESSION[$key] ?? NOT_SELECTED;
}

function isSelected(string $key): bool
{
    return sessionValue($key) !== NOT_SELECTED;
}

//restore helpers: return the attribute that re-selects the saved choice on index.php
function dessertSelected(string $option): string
{
    return sessionValue('dessert') === $option ? 'selected' : '';
}

function drinkSelected(string $option): string
{
    return sessionValue('drink') === $option ? 'selected' : '';
}

function drinkSizeSelected(string $option): string
{
    return sessionValue('drinkSize') === $option ? 'checked' : '';
}

//pricing helpers
function dessertPrice(): float
{
    return DESSERTS[sessionValue('dessert')] ?? 0.0;
}

function drinkPrice(): float
{
    $base = DRINKS[sessionValue('drink')] ?? 0.0;
    $multiplier = DRINK_SIZES[sessionValue('drinkSize')] ?? 1.0; //no size chosen = base (small) price
    return round($base * $multiplier, 2);
}

function orderTotal(): float
{
    return round(dessertPrice() + drinkPrice(), 2);
}

function formatPrice(float $amount): string
{
    return '$' . number_format($amount, 2);
}

//pairing helpers
const PAIRINGS = [
    'Cheesecake'     => ['Coffee', 'Tea'],
    'Chocolate Cake' => ['Milk', 'Coffee'],
    'Carrot Cake'    => ['Tea', 'Coffee'],
    'Tiramisu'       => ['Coffee', 'Hot Chocolate'],
];

function isGoodPairing(string $dessert, string $drink): bool
{
    return in_array($drink, PAIRINGS[$dessert] ?? [], true);
}

//one friendly feedback sentence based on what was (or was not) chosen
function pairingFeedback(): string
{
    $dessert = sessionValue('dessert');
    $drink = sessionValue('drink');

    if (!isSelected('dessert') && !isSelected('drink')) {
        return 'You have not chosen anything yet. Go back and pick a dessert or a drink.';
    }
    if (!isSelected('dessert')) {
        return "Just a $drink today? Try it with " . suggestDessert($drink) . ' next time.';
    }
    if (!isSelected('drink')) {
        return "$dessert on its own is great, but it pairs well with " . PAIRINGS[$dessert][0] . '.';
    }
    if (isGoodPairing($dessert, $drink)) {
        return "$dessert with $drink is a chef-approved pairing.";
    }
    return "$dessert with $drink is a bold choice. Our chef suggests " . PAIRINGS[$dessert][0] . ' instead.';
}

function suggestDessert(string $drink): string
{
    foreach (PAIRINGS as $dessert => $drinks) {
        if (in_array($drink, $drinks, true)) {
            return $dessert;
        }
    }
    return 'Cheesecake';
}

//extra note shown when a drink was chosen without a size, or a size without a drink
function sizeNote(): string
{
    if (isSelected('drink') && !isSelected('drinkSize')) {
        return 'No size was chosen, so the drink is priced as Small.';
    }
    if (!isSelected('drink') && isSelected('drinkSize')) {
        return 'A size was chosen without a drink, so no drink was added.';
    }
    return '';
}

//escape for HTML output
function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
