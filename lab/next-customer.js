//U1 shared-kiosk handoff: "Next customer" button for the saved lab guide page
//add to the saved guide with: <script src="next-customer.js" defer></script> just before </body>
//removes ONLY the lab order key; quest progress and XP keys are never touched
(function () {
    //TODO confirm all three against the guide page's own JS (search it for "localStorage")
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
        status.setAttribute('role', 'status'); //screen readers announce the confirmation

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
