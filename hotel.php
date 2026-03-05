<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = __DIR__ . '/config.php';
if (file_exists($config))
    require_once $config;
else
    die("Config missing!");

$id = $_GET['id'] ?? 0;
$hotel = null;

try {
    $stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ?");
    $stmt->execute([$id]);
    $hotel = $stmt->fetch();
    if (!$hotel) {
        echo "<script>window.location.href='index.php';</script>";
        exit;
    }
} catch (Exception $e) {
    die("Error!");
}

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($hotel['name']); ?> | Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #d32f2f;
            --secondary: #003580;
            --bg: #f5f7f9;
            --white: #fff;
            --text: #1a1a1a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text);
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

        .gallery {
            height: 450px;
            display: flex;
            gap: 10px;
            padding: 10px 8%;
            background: white;
        }

        .g-main {
            flex: 2;
            height: 100%;
            object-fit: cover;
            border-radius: 20px 0 0 20px;
        }

        .g-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .g-img {
            height: calc(50% - 5px);
            width: 100%;
            object-fit: cover;
        }

        .g-img-top {
            border-radius: 0 20px 0 0;
        }

        .g-img-bot {
            border-radius: 0 0 20px 0;
        }

        .container {
            display: flex;
            padding: 3rem 8%;
            gap: 3rem;
            max-width: 1500px;
            margin: 0 auto;
        }

        .info {
            flex: 2;
            background: white;
            padding: 3rem;
            border-radius: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .info h1 {
            font-size: 2.8rem;
            margin-bottom: 15px;
        }

        .info .loc {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .info .desc {
            line-height: 1.8;
            color: #444;
            font-size: 1.05rem;
            margin-bottom: 3rem;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .a-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #555;
        }

        .a-icon {
            color: #2e7d32;
        }

        .booking-card {
            flex: 1;
            background: white;
            padding: 2.5rem;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .b-price {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 2rem;
        }

        .b-price span {
            font-size: 1rem;
            color: #777;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #777;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 14px;
            border: 1.5px solid #eee;
            border-radius: 12px;
            outline: none;
            font-size: 1rem;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: var(--secondary);
        }

        .btn-book {
            background: var(--primary);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 15px;
            font-weight: 800;
            font-size: 1.1rem;
            width: 100%;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 1rem;
        }

        .btn-book:hover {
            background: #b71c1c;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(211, 47, 47, 0.2);
        }

        @media (max-width: 1000px) {
            .container {
                flex-direction: column;
            }

            .gallery {
                display: none;
            }

            .booking-card {
                position: static;
            }
        }
    </style>
</head>

<body>

    <nav>
        <a href="index.php" class="logo">Hotels<span>.com</span></a>
        <div class="nav-links"><a href="my_bookings.php"
                style="text-decoration:none; color:var(--text); font-weight:600;">My Trips</a></div>
    </nav>

    <div class="gallery">
        <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80"
            class="g-main">
        <div class="g-side">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
                class="g-img g-img-top">
            <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
                class="g-img g-img-bot">
        </div>
    </div>

    <div class="container">
        <div class="info">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
                <h1 id="hotelName"><?php echo htmlspecialchars($hotel['name']); ?></h1>
                <div
                    style="background:#2e7d32; color:white; padding:10px 20px; border-radius:12px; font-weight:800; font-size:1.5rem;">
                    <?php echo $hotel['rating']; ?></div>
            </div>
            <p class="loc">📍 <?php echo htmlspecialchars($hotel['location']); ?> • <?php echo $hotel['hotel_type']; ?>
            </p>

            <p class="desc"><?php echo nl2br(htmlspecialchars($hotel['description'])); ?></p>

            <h3 style="margin-bottom:1.5rem;">Main Amenities</h3>
            <div class="amenities-grid">
                <?php
                $a = explode(',', $hotel['amenities']);
                foreach ($a as $item): ?>
                    <div class="a-item">
                        <span class="a-icon">✔</span>
                        <span><?php echo trim($item); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="booking-card">
            <div class="b-price">$<?php echo $hotel['price_per_night']; ?> <span>/ night</span></div>

            <form id="bookingForm">
                <input type="hidden" name="hotel_id" value="<?php echo $hotel['id']; ?>">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="guest_name" id="guest_name" required placeholder="John Doe">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="guest_email" id="guest_email" required placeholder="john@example.com">
                </div>

                <div style="display:flex; gap:10px;">
                    <div class="form-group" style="flex:1;">
                        <label>Check-in</label>
                        <input type="date" name="check_in" id="checkin" value="<?php echo $today; ?>"
                            min="<?php echo $today; ?>" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label>Check-out</label>
                        <input type="date" name="check_out" id="checkout" value="<?php echo $tomorrow; ?>"
                            min="<?php echo $tomorrow; ?>" required>
                    </div>
                </div>

                <div id="bookingMessage" style="color:red; font-size:0.9rem; margin-bottom:10px; display:none;"></div>

                <button type="submit" class="btn-book" id="submitBtn">Book Now</button>
            </form>

            <p style="text-align:center; font-size:0.8rem; color:#777; margin-top:1.5rem;">🔒 Secure Booking • No Hidden
                Fees</p>
        </div>
    </div>

    <script>
        document.getElementById('bookingForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const msg = document.getElementById('bookingMessage');

            btn.innerHTML = 'Processing...';
            btn.disabled = true;
            msg.style.display = 'none';

            const formData = new FormData(this);

            fetch('process_booking.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Fixed JS Redirection
                        window.location.href = `confirmation.php?booking_id=${data.booking_id}`;
                    } else {
                        msg.innerHTML = data.message;
                        msg.style.display = 'block';
                        btn.innerHTML = 'Book Now';
                        btn.disabled = false;
                    }
                })
                .catch(err => {
                    msg.innerHTML = "Network connection failed. Try again.";
                    msg.style.display = 'block';
                    btn.innerHTML = 'Book Now';
                    btn.disabled = false;
                });
        });
    </script>

</body>

</html>
