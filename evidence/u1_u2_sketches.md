# U1 and U2 sketches

> **Key names not yet verified.** The saved lab guide page was not in the repo when this was written, so its JS could not be inspected. `chezSessionLabOrder` comes from the assignment notes. Before relying on this, open the saved guide, search its JS for `localStorage`, and write the real keys here:
>
> - Order key: [FILL: confirmed order key]
> - Quest progress key(s): [FILL]
> - XP key(s): [FILL]
> - Form selector / receipt selector: [FILL]

## U1: shared kiosk handoff

### Handler (final code, also in `lab/next-customer.js`)

```js
//U1 shared-kiosk handoff: "Next customer" button for the saved lab guide page
//removes ONLY the lab order key; quest progress and XP keys are never touched
(function () {
    //TODO confirm all three against the guide page's own JS
    const ORDER_KEY = 'chezSessionLabOrder';
    const FORM_SELECTOR = 'form';
    const RECEIPT_SELECTOR = '#receipt';
    const RECEIPT_DEFAULT = 'No order yet.';

    function nextCustomer(status) {
        localStorage.removeItem(ORDER_KEY); //one key only, never localStorage.clear()

        const form = document.querySelector(FORM_SELECTOR);
        if (form) {
            form.reset(); //back to the defaults written in the HTML
        }

        const receipt = document.querySelector(RECEIPT_SELECTOR);
        if (receipt) {
            receipt.textContent = RECEIPT_DEFAULT;
        }

        //no change/input events are dispatched on purpose: an autosave listener could write the key straight back
        status.textContent = 'Order cleared. Ready for the next customer.';
        if (form) {
            const first = form.querySelector('select, input, textarea');
            if (first) {
                first.focus();
            }
        }
    }

    function addButton() {
        const form = document.querySelector(FORM_SELECTOR);
        const button = document.createElement('button');
        button.type = 'button'; //type=button so it never submits the form
        button.id = 'next-customer';
        button.textContent = 'Next customer (clear this order)';

        const status = document.createElement('p');
        status.setAttribute('role', 'status');

        button.addEventListener('click', function () {
            nextCustomer(status);
        });

        const anchor = form || document.body.firstElementChild;
        anchor.insertAdjacentElement('afterend', button);
        button.insertAdjacentElement('afterend', status);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', addButton);
    } else {
        addButton();
    }
})();
```

### Forget Order comparison (one line)

`forget_order.php` clears the order on the **server** (`unset` + `session_destroy()`) for whoever holds that session cookie, while "Next customer" clears **one localStorage key** in this browser profile for this origin and sends nothing to the server.

### Manual test plan (to fill in)

| Step | Expected | Observed |
|------|----------|----------|
| Save an order, earn some XP/quest progress, click "Next customer" | Form and receipt reset; DevTools > Application > Local Storage shows the order key gone and the quest/XP keys unchanged | [OBSERVATION U1-1: paste what you saw] |
| Reload the page after clicking | Form stays at defaults (no order restored) | [OBSERVATION U1-2: paste what you saw] |
| Click "Next customer" when no order is saved | No error in the console; status message still shown | [OBSERVATION U1-3: paste what you saw] |

## U2: two tabs editing the same order (pseudocode only)

```js
//stored value shape: localStorage[ORDER_KEY] = JSON.stringify({ rev, savedAt, data })
const ORDER_KEY = 'chezSessionLabOrder';
let loadedRev = 0; //the revision this tab last read or wrote

function readOrder() {
    const raw = localStorage.getItem(ORDER_KEY);
    if (!raw) return { rev: 0, savedAt: null, data: null };
    try { return JSON.parse(raw); } catch { return { rev: 0, savedAt: null, data: null }; }
}

//on page load
const stored = readOrder();
loadedRev = stored.rev;
if (stored.data) fillForm(stored.data);

//rule: the newest saved revision wins (highest rev; savedAt is only for display/tie info)
function saveOrder(data) {
    const current = readOrder();
    if (current.rev > loadedRev) {
        //another tab saved after we loaded: warn instead of silently overwriting it
        showWarning('This order was changed in another tab. Reload to see it before saving.');
        return false;
    }
    const next = { rev: current.rev + 1, savedAt: new Date().toISOString(), data };
    localStorage.setItem(ORDER_KEY, JSON.stringify(next));
    loadedRev = next.rev;
    return true;
}

form.addEventListener('submit', function (event) {
    event.preventDefault();
    saveOrder(readForm());
});

//other tabs find out about the change here
window.addEventListener('storage', function (event) {
    //NOTE: the storage event fires only in OTHER tabs/windows of the same origin, never in the tab that wrote the value
    if (event.key !== ORDER_KEY) return; //ignore quest/XP keys
    const incoming = event.newValue ? JSON.parse(event.newValue) : { rev: 0 };
    if (incoming.rev > loadedRev || event.newValue === null) {
        showBanner('This order was changed in another tab, reload to see it.');
    }
});

//NOTE: readOrder() -> compare -> setItem is a read-modify-write that is NOT atomic.
//Two tabs can both read rev 4 and both write rev 5 in the gap; the last setItem wins.
//The rev check makes the conflict visible in the common case; it does not make it impossible.
```

### Manual test plan (to fill in)

| Step | Expected | Observed |
|------|----------|----------|
| Open tab A and tab B, save in A | Banner appears in B, not in A | [OBSERVATION U2-1: paste what you saw] |
| Without reloading B, submit in B | Conflict warning in B; stored order still A's | [OBSERVATION U2-2: paste what you saw] |
| Reload B, change, submit | Saves as rev+1; banner appears in A | [OBSERVATION U2-3: paste what you saw] |
