<?php
header('Content-Type: application/json');

// 1. Constraints
const MIN_AMOUNT = 2000;
const MAX_AMOUNT = 10000;
const MIN_PERIOD = 12;
const MAX_PERIOD = 60;

// 2. Mocked Data (Personal Codes to Modifiers)
$modifiers = [
    '49002010965' => 0,    // Debt
    '49002010976' => 100,  // Segment 1
    '49002010987' => 300,  // Segment 2
    '49002010998' => 1000, // Segment 3
];

// 3. Input Handling
$personalCode = $_POST['personal_code'] ?? '';
$requestedAmount = (int)($_POST['amount'] ?? 0);
$requestedPeriod = (int)($_POST['period'] ?? 0);

if (empty($personalCode) || $requestedAmount < MIN_AMOUNT || $requestedPeriod < MIN_PERIOD) {
    echo json_encode(['approved' => false, 'message' => 'Invalid input data.']);
    exit;
}

// 4. Decision Logic
$modifier = $modifiers[$personalCode] ?? null;

// Case: Debt or Unknown User
if ($modifier === null || $modifier === 0) {
    echo json_encode(['approved' => false, 'message' => 'Negative. Reason: We could not resolve your Personal Code or you have a debt on your account. Please verify your personal data and try again.']);
    exit;
}

// Logic: Maximize the sum for the current period
// Score = (Modifier / Amount) * Period -> Score >= 1 means Amount <= Modifier * Period
$maxAmountForPeriod = $modifier * $requestedPeriod;

if ($maxAmountForPeriod >= MIN_AMOUNT) {
    // Success for current period
    $finalAmount = min($maxAmountForPeriod, MAX_AMOUNT);
    echo json_encode([
        'approved' => true,
        'conditions_differ' => false,
        'amount' => $finalAmount,
        'period' => $requestedPeriod,
        'note' => $finalAmount > $requestedAmount ? "You qualify for a higher amount!" : null

    ]);
} else {
    // Search for a new suitable period for the minimum amount (2000)
    $requiredPeriod = (int) ceil(MIN_AMOUNT / $modifier);

    if ($requiredPeriod <= MAX_PERIOD) {
        echo json_encode([
            'approved' => true,
            'conditions_differ' => true,
            'amount' => MIN_AMOUNT,
            'period' => $requiredPeriod,
            'note' => "We couldn't approve the requested period, but we can offer this amount for $requiredPeriod months."
        ]);
    } else {
        echo json_encode(['approved' => false, 'message' => 'No suitable loan amount found even with maximum period.']);
    }
}