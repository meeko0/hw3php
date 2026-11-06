# U1 and U2 sketches (PHP, HTML and CSS only, no JavaScript)

> **Why there is no localStorage code here.** The lab guide stores its order in `localStorage` (key `chezSessionLabOrder` according to the assignment notes; [FILL: confirm from the guide's JS]). Removing one localStorage key requires JavaScript. The only no-JS way to clear it is the `Clear-Site-Data: "storage"` response header, which wipes **all** storage for the origin, including the quest progress and XP keys that must be kept. So the U1 and U2 prototypes are built on the PHP session side of the same workflow, and the written answers still explain the localStorage behaviour observed in the lab.

## U1: shared kiosk handoff

### Handler (final code: `hw3/next_customer.php`)

```php
<?php
//U1 shared-kiosk handoff (lab prototype, PHP only)
//Forget Order clears the order, but the browser keeps sending the same PHPSESSID cookie;
//this also drops that cookie so the next customer starts a brand-new session
session_start();

unset($_SESSION['dessert'], $_SESSION['drink'], $_SESSION['drinkSize']);
session_destroy();

//expire the session cookie with the same path/domain it was set with
$cookie = session_get_cookie_params();
setcookie(session_name(), '', time() - 3600, $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);

header('Location: index.php?handoff=1');
exit;
```

The button on `index.php` is a plain HTML form, so it works without JavaScript and from the keyboard:

```html
<!--U1 handoff: server-side "Next customer" for a shared kiosk browser (no JavaScript)-->
<form class="card handoff" action="next_customer.php" method="post">
    <button type="submit" class="button secondary">Next customer (clear this order)</button>
</form>
```

### Forget Order comparison (one line)

`forget_order.php` empties the order but the browser keeps the same PHPSESSID, while `next_customer.php` also expires that cookie, so the next customer gets a brand-new session ID. Neither one touches the lab page's localStorage.

### Manual test plan (to fill in)

| Step | Expected | Observed |
|------|----------|----------|
| Place an order, note PHPSESSID in DevTools > Application > Cookies, click "Next customer" | Redirect to the form with "Order cleared. Ready for the next customer."; form at defaults | [OBSERVATION U1-1: paste what you saw] |
| Check the cookie again after the next page load | A different PHPSESSID value | [OBSERVATION U1-2: old and new IDs] |
| Click "Next customer" with no order placed | No warnings; same status message | [OBSERVATION U1-3: paste what you saw] |

## U2: two tabs editing the same order (PHP pseudocode only)

```php
//stored with the order in the session: $_SESSION['order'] = ['rev' => int, 'savedAt' => string, 'data' => [...]]

//index.php: remember which revision this tab loaded, in a hidden field
$loadedRev = $_SESSION['order']['rev'] ?? 0;
//<input type="hidden" name="loadedRev" value="<?= $loadedRev ?>">

//process_order.php: newest saved revision wins
$storedRev = $_SESSION['order']['rev'] ?? 0;
$postedRev = (int)($_POST['loadedRev'] ?? 0);

if ($storedRev > $postedRev) {
    //another tab saved after this tab loaded the form: do NOT overwrite
    $conflict = 'This order was changed in another tab, reload to see it before saving.';
} else {
    $_SESSION['order'] = [
        'rev'     => $storedRev + 1,
        'savedAt' => date('c'),
        'data'    => ['dessert' => ..., 'drink' => ..., 'drinkSize' => ...],
    ];
}

//banner on index.php when this tab's copy is stale
//without JavaScript there is no live 'storage' event: the stale tab only finds out on its next
//request (reload or submit), when the server compares loadedRev with the stored rev
//(in the JS lab version, the storage event fires only in OTHER tabs, never in the tab that wrote)

//atomicity: PHP's default "files" session handler locks the session file for the whole request,
//so this check-then-write is serialized per session. A localStorage read-modify-write is NOT atomic:
//two tabs can both read rev 4 and both write rev 5.
```

### Manual test plan (to fill in)

| Step | Expected | Observed |
|------|----------|----------|
| Open the form in tab A and tab B; submit a change in A | A saves as the next revision | [OBSERVATION U2-1: paste what you saw] |
| Without reloading B, submit in B | Conflict warning in B; stored order is still A's | [OBSERVATION U2-2: paste what you saw] |
| Reload B, change, submit | Saves as the next revision | [OBSERVATION U2-3: paste what you saw] |
