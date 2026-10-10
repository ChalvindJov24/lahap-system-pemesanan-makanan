<?php
// tests/run.php — Minimal test runner untuk Setup Phase verification

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/session.php';

$passed = 0;
$failed = 0;
$tests = [];

function test(string $name, callable $callback): void {
    global $passed, $failed, $tests;
    $result = $callback();
    $tests[] = ['name' => $name, 'passed' => $result === true, 'msg' => $result === true ? '' : (string)$result];
    if ($result === true) {
        $passed++;
    } else {
        $failed++;
    }
}

// --- Test 1: Database connection ---
test('Database connection', function() use ($pdo) {
    return $pdo instanceof PDO;
});

// --- Test 2: Tables existence ---
test('Required tables exist', function() use ($pdo) {
    $tables = ['users', 'categories', 'menu_items', 'orders', 'order_items', 'order_histories'];
    $existing = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        if (!in_array($table, $existing)) {
            return "Table '$table' not found";
        }
    }
    return true;
});

// --- Test 3: e() helper ---
test("e() XSS escape", function() {
    $result = e('<script>alert("xss")</script>');
    return strpos($result, '<script>') === false;
});

// --- Test 4: generate_order_code() ---
test("generate_order_code() format", function() {
    $code = generate_order_code();
    return preg_match('/^ORD-\d{8}-\d{6}$/', $code) === 1;
});

// --- Test 5: current_user() when not logged in ---
test("current_user() returns null when not logged in", function() {
    if (is_logged_in()) return 'Already logged in';
    return current_user() === null;
});

// --- Test 6: flash() set & get ---
test("flash() set & get", function() {
    flash('test_msg', 'test value');
    return flash('test_msg') === 'test value';
});

// --- Test 7: payment_label() ---
test("payment_label() mapping", function() {
    return payment_label('cash') === 'Tunai' && payment_label('e-wallet') === 'E-Wallet';
});

// --- Output results ---
echo "\n";
echo str_repeat("=", 50) . "\n";
echo "  Lahap Test Runner\n";
echo str_repeat("=", 50) . "\n\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "MySQL:       " . ($pdo->query("SELECT VERSION()")->fetchColumn()) . "\n\n";

foreach ($tests as $t) {
    $status = $t['passed'] ? '[PASS]' : '[FAIL]';
    $color  = $t['passed'] ? 'green' : 'red';
    echo "$status {$t['name']}";
    if (!$t['passed'] && $t['msg']) {
        echo " -> {$t['msg']}";
    }
    echo "\n";
}

echo "\n";
echo str_repeat("-", 50) . "\n";
echo "Results: $passed passed, $failed failed\n";
echo str_repeat("=", 50) . "\n";

exit($failed > 0 ? 1 : 0);
