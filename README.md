# homework3-bistro-order

CSC 4370 Homework 3: a PHP sessions dessert-and-drink order app ("Chez Session Bistro"), built with PHP, HTML and CSS only (no JavaScript).

## Layout

| Folder | Contents |
|--------|----------|
| `hw3/` | The PHP app: `index.php` (order form + live guidance), `process_order.php` (receipt, feedback, pricing), `order_functions.php` (session + helpers), `forget_order.php` (reset), `next_customer.php` (U1 kiosk handoff), `about.php`, `styles.css` |
| `lab/` | Notes on the U1/U2 lab prototype |
| `evidence/` | `core_tests.md` (curl session tests), `u1_u2_sketches.md` (U1 handler, U2 PHP pseudocode, manual test plans) |

## Run locally

```sh
cd hw3
php -S localhost:8000
```

Then open http://localhost:8000/index.php.

## How the session works

- `order_functions.php` calls `session_start()` on line 1, before any output.
- `process_order.php` calls `order()`. It copies the POSTed `dessert`, `drink` and `drinkSize` into `$_SESSION` and returns the escaped receipt summary. Missing, empty, `none` or off-menu values become `(not selected)`.
- `index.php` restores the saved choices with `dessertSelected()` / `drinkSelected()` (`selected`) and `drinkSizeSelected()` (`checked`).
- `forget_order.php` unsets the three keys, destroys the session and redirects to `index.php`.
- `next_customer.php` does the same and also expires the PHPSESSID cookie, so a shared browser starts a new session.
