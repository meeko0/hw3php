# Core PHP session tests

Environment: PHP 8.4.27 built-in server (`php -d display_errors=1 -d error_reporting=E_ALL -S localhost:8000` run from `hw3/`). Requests were sent with `curl -c jar.txt -b jar.txt` so the PHPSESSID cookie carries over between requests. Claude Code ran these on 2026-11-06. Re-run them in your own browser before you submit.

| # | Steps | Expected | Actual | Pass/Fail |
|---|-------|----------|--------|-----------|
| 1 | POST `dessert=Tiramisu&drink=Coffee&drinkSize=Large` to `process_order.php` | Receipt shows Tiramisu / Coffee / Large, pairing feedback, prices, "Order Again!" link | Receipt `Tiramisu`, `Coffee`, `Large`; feedback "Tiramisu with Coffee is a chef-approved pairing."; Dessert $5.25, Drink $3.38, Total $8.63; "Order Again!" link to `index.php` present | Pass |
| 2 | POST `dessert=none&drink=Tea` (no `drinkSize` field) | Dessert and size show "(not selected)", no warnings/notices | Dessert `(not selected)`, Drink `Tea`, Size `(not selected)`; note "No size was chosen, so the drink is priced as Small."; Total $1.75; 0 warnings/notices | Pass |
| 2b | POST with no fields at all | All three show "(not selected)", totals $0.00, no warnings | All three `(not selected)`, $0.00 / $0.00 / $0.00; 0 warnings/notices | Pass |
| 3 | POST `Carrot Cake / Hot Chocolate / Medium`, then GET `index.php` (Order Again) | Saved choices restored in the HTML | `<option value="Carrot Cake" selected>`, `<option value="Hot Chocolate" selected>`, `<input type="radio" ... value="Medium" checked>` | Pass |
| 3b | GET `process_order.php` again (refresh, no POST) | Order is kept, not wiped | Receipt still shows Carrot Cake / Hot Chocolate / Medium | Pass |
| 4 | GET `forget_order.php`, then GET `index.php` and `about.php` | 302 redirect to `index.php`; form back to defaults; session values gone | `HTTP/1.1 302 Found`, `Location: index.php`; 0 `selected`/`checked` attributes on `index.php`; `about.php` shows `(not selected)` x3 | Pass |
| 5 | POST `dessert=<script>alert(1)</script>&drink=Milk&drinkSize=Small` | Unknown value rejected, nothing executable in output | Dessert `(not selected)`, no `<script>` in the response | Pass |

## Lint and warning scan

```
$ for f in hw3/*.php; do php -l "$f"; done
No syntax errors detected in about.php
No syntax errors detected in forget_order.php
No syntax errors detected in index.php
No syntax errors detected in order_functions.php
No syntax errors detected in process_order.php

$ cat <all saved responses> | grep -ciE 'warning|notice|deprecated|fatal'
0
$ grep -iE 'warning|notice|fatal' <server log>
(no output)
```

## Still to do by hand

- [SCREENSHOT: W3C validator result for index.php, paste here]
- [SCREENSHOT: W3C validator result for process_order.php, paste here]
- [OBSERVATION: keyboard-only walkthrough (Tab / Shift+Tab / arrow keys / Enter), paste what you saw]
