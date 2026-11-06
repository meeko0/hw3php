<?php session_start(); //must run before any output so the session cookie header can be sent

//shared helpers for the Chez Session bistro order pages

const NOT_SELECTED = '(not selected)';

//menu: name => base price in dollars
const DESSERTS = [
    'Cheesecake'     => 4.25,
    'Chocolate Cake' => 4.50,
    'Carrot Cake'    => 4.00,
    'Tiramisu'       => 4.75,
];

const DRINKS = [
    'Coffee'        => 2.25,
    'Hot Chocolate' => 2.75,
    'Root Beer'     => 2.50,
    'Tea'           => 2.00,
    'Milk'          => 1.75,
];

//size => upcharge added to the drink base price
const DRINK_SIZES = [
    'Small'  => 0.00,
    'Medium' => 0.60,
    'Large'  => 1.15,
];

const ORDER_KEYS = ['dessert', 'drink', 'drinkSize'];

//turn missing, empty, 'none' or off-menu values into the fallback text
function cleanChoice($value, array $menu): string
{
    $value = is_string($value) ? trim($value) : '';
    if ($value === '' || $value === 'none' || !array_key_exists($value, $menu)) {
        return NOT_SELECTED;
    }
    return $value;
}

//save the posted choices into the session and return the receipt summary
function order(): string
{
    //only a POST changes the order, so refreshing the receipt with GET keeps the saved choices
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        $_SESSION['dessert']   = cleanChoice($_POST['dessert'] ?? 'none', DESSERTS);
        $_SESSION['drink']     = cleanChoice($_POST['drink'] ?? 'none', DRINKS);
        $_SESSION['drinkSize'] = cleanChoice($_POST['drinkSize'] ?? '', DRINK_SIZES);
    }

    return 'Dessert: ' . e(sessionValue('dessert')) . '<br>'
        . 'Drink: ' . e(sessionValue('drink')) . '<br>'
        . 'Drink size: ' . e(sessionValue('drinkSize'));
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

//progress helpers
function completedChoiceCount(): int
{
    $count = 0;
    foreach (ORDER_KEYS as $key) {
        if (isSelected($key)) {
            $count++;
        }
    }
    return $count;
}

function completionMessage(): string
{
    $count = completedChoiceCount();

    if ($count === 3) {
        return 'Your order is complete and ready to submit.';
    }
    if ($count === 0) {
        return 'Start by choosing a dessert, a drink, and a size.';
    }
    return "You have completed {$count} of 3 order choices.";
}

//pairing helper
const PAIRINGS = [
    'Cheesecake|Coffee'   => 'Cheesecake and coffee make a classic cafe combo.',
    'Chocolate Cake|Milk' => 'Chocolate cake and milk are a rich, familiar pairing.',
    'Tiramisu|Coffee'     => 'Tiramisu and coffee double up on the espresso flavor.',
    'Carrot Cake|Tea'     => 'Carrot cake and tea make a lighter, balanced combination.',
];

function pairingSuggestion(): string
{
    if (!isSelected('dessert') || !isSelected('drink')) {
        return 'Select both a dessert and a drink to see a suggested pairing.';
    }

    $dessert = sessionValue('dessert');
    $drink = sessionValue('drink');

    return 'Suggested pairing: '
        . (PAIRINGS["$dessert|$drink"] ?? "$dessert with $drink gives the order a custom bistro feel.");
}

//pricing helpers
function dessertPrice(): float
{
    return DESSERTS[sessionValue('dessert')] ?? 0.0;
}

function drinkPrice(): float
{
    $base = DRINKS[sessionValue('drink')] ?? null;
    if ($base === null) {
        return 0.0; //a size on its own adds nothing
    }
    return $base + (DRINK_SIZES[sessionValue('drinkSize')] ?? 0.0);
}

function estimatedTotal(): float
{
    return round(dessertPrice() + drinkPrice(), 2);
}

function formattedTotal(): string
{
    return '$' . number_format(estimatedTotal(), 2);
}

function pricingNote(): string
{
    if (isSelected('drink') && !isSelected('drinkSize')) {
        return 'Choose a drink size to finalize the drink price.';
    }
    if (completedChoiceCount() === 0) {
        return 'Your estimated total will update as soon as you begin building the order.';
    }
    return 'The estimate is based on the current dessert, drink, and drink size selections.';
}

function orderProfile(): string
{
    $total = estimatedTotal();

    if ($total >= 6.5) {
        return 'Full cafe combo';
    }
    if ($total >= 4.0) {
        return 'Balanced dessert break';
    }
    if ($total > 0) {
        return 'Light snack order';
    }
    return 'No selections yet';
}

//restore helpers: dropdowns get 'selected', radios get 'checked'
function dessertSelected(string $dessert): string
{
    return sessionValue('dessert') === $dessert ? 'selected' : '';
}

function drinkSelected(string $drink): string
{
    return sessionValue('drink') === $drink ? 'selected' : '';
}

function drinkSizeSelected(string $drinkSize): string
{
    return sessionValue('drinkSize') === $drinkSize ? 'checked' : '';
}

//escape for HTML output
function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
