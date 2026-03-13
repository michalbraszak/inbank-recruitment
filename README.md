# Inbank Loan Decision Engine

A lightweight, high-performance loan calculator built with PHP and Vanilla JS/Tailwind CSS.

## Technical Decisions
- **Math over Loops:** Instead of iterating through possible amounts, I used a linear formula transformation to calculate the maximum allowed sum in $O(1)$ time complexity.
- **Separation of Concerns:** Logic is strictly handled by `engine.php` (API), while `index.html` serves as a thin client.
- **UX-First Design:** Used sliders for interactive feedback and dynamic color-coding (Green for success, Orange for alternative offers).

## How to run
1. Clone the repository.
2. Ensure you have a PHP server running (e.g., XAMPP, Laragon).
3. Open `index.html` in your browser.

## Assignment Question
**What is one thing you would improve about the take-home assignment?**

I would improve the system by introducing dual-alternative offers when the primary request cannot be met. Instead of automatically suggesting just one alternative, I would present the user with two distinct choices:
1. Liquidity First: The maximum possible amount for their originally selected period.
2. Amount First: The minimum period required to obtain the exact amount they originally requested.

This approach shifts the engine from a 'take-it-or-leave-it' model to a user-centric experience, allowing the customer to decide whether they prioritize getting cash quickly or getting the full amount they need.
