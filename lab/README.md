# Lab notes (U1 / U2)

This project uses PHP, HTML and CSS only.

The lab guide keeps its order in the browser's `localStorage`. Clearing a single localStorage key needs JavaScript, so the U1 "Next customer" prototype is built on the server side instead: [`hw3/next_customer.php`](../hw3/next_customer.php). It clears the order, destroys the session and expires the PHPSESSID cookie. The button is a plain HTML form on `hw3/index.php`.

U2 is PHP pseudocode in [`evidence/u1_u2_sketches.md`](../evidence/u1_u2_sketches.md).

To do: save a copy of the lab guide page here as `lab/guide.html` (unmodified, for reference), and record its real localStorage key names in `evidence/u1_u2_sketches.md`.
