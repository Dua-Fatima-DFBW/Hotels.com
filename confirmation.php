<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = __DIR__ . '/config.php';
if (file_exists($config))
    require_once $config;
else
    die("Config missing!");

$b_id = $_GET['booking_id'] ?? 0;
$booking = null;

try {
    $stmt = $conn->prepare("SELECT b.*, h.name as h_name, h.location FROM bookings b JOIN hotels h ON b.hotel_id = h.id WHERE b.id = ?");
    $stmt->execute([$b_id]);
    $booking = $stmt->fetch();
    if (!$booking) {
        echo "<script>window.location.href='index.php';</script>";
        exit;
    }
} catch (Exception $e) {
    die("Error!");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Booking Confirmed! | Hotels.com Clone</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2e7d32;
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 4rem;
            border-radius: 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 30px 100px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.8s ease-out;
        }

        .icon {
            font-size: 5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        h1 {
            font-size: 2.5rem;
            color: #1a1a1a;
            margin-bottom: 1rem;
        }

        p {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .details {
            background: #f9f9f9;
            padding: 2rem;
            border-radius: 20px;
            text-align: left;
            margin-bottom: 2.5rem;
            border: 1px dashed #ddd;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .label {
            font-weight: 700;
            color: #555;
            font-size: 0.9rem;
        }

        .val {
            font-weight: 600;
            color: #1a1a1a;
        }

        .btn {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
        }

        .btn:hover {
            background: #002b66;
            transform: scale(1.05);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="icon">✅</div>
        <h1>Booking Confirmed!</h1>
        <p>Pack your bags! Your reservation at <strong><?php echo htmlspecialchars($booking['h_name']); ?></strong> is
            successfully locked in.</p>

        <div class="details">
            <div class="row"><span class="label">Booking ID</span><span
                    class="val">#BK-<?php echo 1000 + $booking['id']; ?></span></div>
            <div class="row"><span class="label">Check-in</span><span
                    class="val"><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></span></div>
            <div class="row"><span class="label">Check-out</span><span
                    class="val"><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></span></div>
            <div class="row" style="margin-top:10px; border-top:2px solid #ddd; pt:15px;"><span class="label"
                    style="font-size:1.1rem; color:#1a1a1a;">Total Paid</span><span class="val"
                    style="color:var(--primary); font-size:1.4rem;">$<?php echo $booking['total_price']; ?></span></div>
        </div>

        <a href="index.php" class="btn">Return to Home</a>
    </div>

</body>

</html>
