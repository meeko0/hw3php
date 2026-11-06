# homework3-bistro-order

CSC 4370 Homework 3: a PHP sessions dessert-and-drink order app ("Chez Session Bistro").

## Layout

| Folder | Contents |
|--------|----------|
| `hw3/` | The PHP app: `index.php` (order form), `process_order.php` (receipt), `order_functions.php` (session + helpers), `forget_order.php` (reset), `about.php`, `styles.css` |
| `lab/` | U1 "Next customer" handoff script for the lab guide page (`next-customer.js`) |
| `evidence/` | `core_tests.md` (curl session tests), `u1_u2_sketches.md` (U1 handler, U2 pseudocode, manual test plans) |

## Run locally

```sh
cd hw3
php -S localhost:8000
```

Then open http://localhost:8000/index.php.

## How the session works

- `order_functions.php` calls `session_start()` on line 1, before any output.
- `process_order.php` calls `order()`, which copies the POSTed `dessert`, `drink` and `drinkSize` into `$_SESSION`. Missing, empty or `none` values become `(not selected)`.
- `index.php` restores the saved choices with `dessertSelected()` / `drinkSelected()` (`selected`) and `drinkSizeSelected()` (`checked`).
- `forget_order.php` unsets the three keys, destroys the session and redirects to `index.php`.
