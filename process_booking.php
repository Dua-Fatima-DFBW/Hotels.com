<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure JSON header is set early
header('Content-Type: application/json');

$config = __DIR__ . '/config.php';
if (file_exists($config))
    require_once $config;
else {
    echo json_encode(['success' => false, 'message' => 'Internal server error: Config missing.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Direct access forbidden.']);
    exit;
}

try {
    if (!isset($conn))
        throw new Exception("Database connection lost.");

    // Retrieve and Trim
    $h_id = (int) ($_POST['hotel_id'] ?? 0);
    $name = trim($_POST['guest_name'] ?? '');
    $email = trim($_POST['guest_email'] ?? '');
    $cin = $_POST['check_in'] ?? '';
    $cout = $_POST['check_out'] ?? '';

    // Validation
    if (!$h_id || !$name || !$email || !$cin || !$cout) {
        throw new Exception("Please fill in all the details correctly.");
    }

    $d_in = new DateTime($cin);
    $d_out = new DateTime($cout);

    if ($d_out <= $d_in) {
        throw new Exception("Check-out must be at least one day after check-in.");
    }

    $nights = $d_in->diff($d_out)->days;

    // Fetch Price
    $stmt = $conn->prepare("SELECT price_per_night FROM hotels WHERE id = ?");
    $stmt->execute([$h_id]);
    $hotel = $stmt->fetch();

    if (!$hotel)
        throw new Exception("Hotel not found.");

    $total = $hotel['price_per_night'] * $nights;

    // Insert
    $stmt = $conn->prepare("INSERT INTO bookings (hotel_id, guest_name, guest_email, check_in, check_out, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$h_id, $name, $email, $cin, $cout, $total]);

    $bookingId = $conn->lastInsertId();

    echo json_encode(['success' => true, 'booking_id' => $bookingId]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
