<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = __DIR__ . '/config.php';
if (file_exists($config))
    require_once $config;
else
    die("Config missing!");

$bookings = [];
try {
    $stmt = $conn->prepare("SELECT b.*, h.name as h_name, h.location FROM bookings b JOIN hotels h ON b.hotel_id = h.id ORDER BY b.booking_date DESC");
    $stmt->execute();
    $bookings = $stmt->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Trips | Hotels.com Clone</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #d32f2f;
            --secondary: #003580;
            --bg: #f5f7f9;
            --white: #fff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: var(--bg);
            color: #1a1a1a;
        }

        nav {
            background: var(--white);
            padding: 1rem 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
        }

        .logo span {
            color: var(--secondary);
        }

        .container {
            padding: 4rem 8%;
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 3rem;
            text-align: center;
        }

        .book-item {
            background: white;
            padding: 2rem;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
        }

        .book-item:hover {
            transform: translateX(10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .b-icon {
            font-size: 2.5rem;
            background: #f0f7ff;
            color: var(--secondary);
            padding: 20px;
            border-radius: 20px;
        }

        .b-info {
            flex: 1;
        }

        .b-name {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .b-loc {
            color: #888;
            font-size: 0.9rem;
        }

        .b-dates {
            border-left: 2px solid #eee;
            padding-left: 2rem;
            min-width: 250px;
        }

        .d-label {
            color: #aaa;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .d-val {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .b-total {
            text-align: right;
            min-width: 120px;
            border-left: 2px solid #eee;
            padding-left: 2rem;
        }

        .t-price {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .empty {
            text-align: center;
            padding: 5rem;
            background: white;
            border-radius: 30px;
        }

        .btn {
            background: var(--secondary);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 800;
            display: inline-block;
            margin-top: 2rem;
        }
    </style>
</head>

<body>

    <nav>
        <a href="index.php" class="logo">Hotels<span>.com</span></a>
        <div class="nav-links"><a href="index.php" style="text-decoration:none; color:#1a1a1a; font-weight:600;">Back to
                Search</a></div>
    </nav>

    <div class="container">
        <h1>Your Reservations</h1>

        <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $b): ?>
                <div class="book-item">
                    <div class="b-icon">🏨</div>
                    <div class="b-info">
                        <div class="b-name"><?php echo htmlspecialchars($b['h_name']); ?></div>
                        <div class="b-loc">📍 <?php echo htmlspecialchars($b['location']); ?></div>
                        <div style="margin-top:10px; font-weight:600; color:var(--secondary); font-size:0.85rem;">Guest:
                            <?php echo htmlspecialchars($b['guest_name']); ?></div>
                    </div>
                    <div class="b-dates">
                        <div class="d-label">Check-in</div>
                        <div class="d-val"><?php echo date('D, M d Y', strtotime($b['check_in'])); ?></div>
                        <div class="d-label">Check-out</div>
                        <div class="d-val"><?php echo date('D, M d Y', strtotime($b['check_out'])); ?></div>
                    </div>
                    <div class="b-total">
                        <div class="d-label">Paid</div>
                        <div class="t-price">$<?php echo $b['total_price']; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty">
                <div style="font-size:4rem; margin-bottom:1rem;">☕</div>
                <h2>No trips found.</h2>
                <p>Ready to travel? Find your next dream stay now.</p>
                <a href="index.php" class="btn">Explore Hotels</a>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>
