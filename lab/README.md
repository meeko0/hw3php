# Lab prototype (U1)

1. Save a copy of the lab guide page into this folder (for example `lab/guide.html`).
2. Open its JS, search for `localStorage`, and confirm the order key and the form and receipt selectors at the top of `next-customer.js` (currently `chezSessionLabOrder`, `form`, `#receipt`).
3. Add this line just before `</body>` in the saved page:

```html
<!--U1 handoff: Next customer button, clears only the order key-->
<script src="next-customer.js" defer></script>
```
