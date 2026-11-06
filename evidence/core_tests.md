# Core PHP session tests

Environment: PHP 8.4.27 built-in server (`php -d display_errors=1 -d error_reporting=E_ALL -S localhost:8000` run from `hw3/`). Requests were sent with `curl -c jar.txt -b jar.txt` so the PHPSESSID cookie carries over between requests. Claude Code ran these on 2026-11-06. Re-run them in your own browser before you submit.

Prices come from the course code library: desserts $4.00 to $4.75; drinks $1.75 to $2.75; size upcharge Small +$0.00, Medium +$0.60, Large +$1.15.

| # | Steps | Expected | Actual | Pass/Fail |
|---|-------|----------|--------|-----------|
| 1 | POST `dessert=Tiramisu&drink=Coffee&drinkSize=Large` to `process_order.php` | Stored order, feedback, estimated total, order profile, "Order Again!" link | `Dessert: Tiramisu / Drink: Coffee / Drink size: Large`; "Your order is complete and ready to submit."; "Suggested pairing: Tiramisu and coffee double up on the espresso flavor."; $8.15 (4.75 + 2.25 + 1.15); "Full cafe combo"; "Order Again!" link to `index.php` present | Pass |
| 2 | POST `dessert=none&drink=Tea` (no `drinkSize` field) | Dessert and size show "(not selected)", no warnings/notices | `Dessert: (not selected) / Drink: Tea / Drink size: (not selected)`; "You have completed 1 of 3 order choices."; $2.00; "Light snack order"; "Choose a drink size to finalize the drink price."; 0 warnings/notices | Pass |
| 2b | POST with no fields at all | All three "(not selected)", $0.00, no warnings | All three `(not selected)`; "Start by choosing a dessert, a drink, and a size."; $0.00; "No selections yet"; 0 warnings/notices | Pass |
| 3 | POST `Carrot Cake / Hot Chocolate / Medium`, then GET `index.php` (Order Again) | Saved choices restored in the HTML | `<option value="Carrot Cake" selected>`, `<option value="Hot Chocolate" selected>`, `<input type="radio" ... value="Medium" checked>`; guidance card shows $7.35 / "Full cafe combo" | Pass |
| 3b | GET `process_order.php` again (refresh, no POST) | Order is kept, not wiped | Still `Carrot Cake / Hot Chocolate / Medium` | Pass |
| 4 | GET `forget_order.php`, then GET `index.php` | 302 redirect to `index.php`; form back to defaults | `HTTP/1.1 302 Found`, `Location: index.php`; 0 `selected`/`checked` attributes; $0.00, "No selections yet" | Pass |
| 5 | POST `dessert=<script>alert(1)</script>&drink=Milk&drinkSize=Small` | Off-menu value rejected, nothing executable in output | Dessert `(not selected)`, 0 `<script` in the response | Pass |
| 6 | U1: POST an order, then POST `next_customer.php`, then GET `index.php?handoff=1` | Order cleared, session cookie expired, next visit gets a new session ID | `302`, `Set-Cookie: PHPSESSID=deleted; expires=Thu, 01 Jan 1970 ...`, `Location: index.php?handoff=1`; cookie jar emptied; "Order cleared. Ready for the next customer." shown; 0 `selected`/`checked`; session ID changed from `c19aa261...` to `5aa386f7...` | Pass |

## Lint and warning scan

```
$ for f in hw3/*.php; do php -l "$f"; done
No syntax errors detected in about.php
No syntax errors detected in forget_order.php
No syntax errors detected in index.php
No syntax errors detected in next_customer.php
No syntax errors detected in order_functions.php
No syntax errors detected in process_order.php

$ cat <all saved responses> | grep -ciE 'warning|notice|deprecated|fatal'
0
$ grep -iE 'warning|notice|fatal' <server log>
(no output)
$ grep -rl '<script' hw3/
(no output: the app uses no JavaScript)
```

## Still to do by hand

- [SCREENSHOT: W3C validator result for index.php, paste here]
- [SCREENSHOT: W3C validator result for process_order.php, paste here]
- [OBSERVATION: keyboard-only walkthrough (Tab / Shift+Tab / arrow keys / Enter), paste what you saw]
