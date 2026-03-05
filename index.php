<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Improved path handling
$config = __DIR__ . '/config.php';
if (file_exists($config))
    require_once $config;
else
    die("Configuration file missing!");

$featured = [];
try {
    $stmt = $conn->prepare("SELECT * FROM hotels ORDER BY rating DESC LIMIT 3");
    $stmt->execute();
    $featured = $stmt->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels.com Clone | Premium Stays</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #d32f2f;
            --secondary: #003580;
            --accent: #ffb700;
            --bg: #f5f7f9;
            --white: #ffffff;
            --text: #1a1a1a;
            --shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
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

        /* Navbar */
        nav {
            background: var(--white);
            padding: 1.2rem 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
        }

        .logo span {
            color: var(--secondary);
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            margin-left: 2rem;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        /* Hero */
        .hero {
            height: 80vh;
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 0 2rem;
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 1.4rem;
            font-weight: 400;
            margin-bottom: 3rem;
        }

        /* Search Bar */
        .search-box {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 100px;
            display: flex;
            align-items: center;
            width: 95%;
            max-width: 1100px;
            box-shadow: var(--shadow);
            gap: 10px;
        }

        .search-field {
            flex: 1;
            padding: 0 20px;
            border-right: 1px solid #eee;
        }

        .search-field:last-of-type {
            border-right: none;
        }

        .search-field label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #777;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .search-field input {
            border: none;
            outline: none;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            color: var(--text);
        }

        .btn-search {
            background: var(--primary);
            color: white;
            border: none;
            padding: 18px 45px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.3s, background 0.3s;
        }

        .btn-search:hover {
            background: #b71c1c;
            transform: scale(1.05);
        }

        /* Featured */
        .container {
            padding: 6rem 8%;
            max-width: 1500px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.8rem;
            font-weight: 800;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
        }

        .card {
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: 0.4s;
            cursor: pointer;
            position: relative;
        }

        .card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow);
        }

        .card-img {
            height: 280px;
            width: 100%;
            object-fit: cover;
        }

        .card-content {
            padding: 2rem;
        }

        .badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--accent);
            padding: 6px 15px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card-loc {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f0f0f0;
            pt: 1.5rem;
            margin-top: 1.5rem;
            padding-top: 1.2rem;
        }

        .price {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
        }

        .price span {
            font-size: 0.85rem;
            color: #777;
            font-weight: 400;
        }

        .score {
            background: var(--secondary);
            color: white;
            padding: 5px 12px;
            border-radius: 8px;
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .hero h1 {
                font-size: 2.8rem;
            }

            .search-box {
                flex-direction: column;
                border-radius: 30px;
                padding: 2rem;
            }

            .search-field {
                border-right: none;
                border-bottom: 1px solid #eee;
                width: 100%;
                padding: 15px 0;
            }

            .btn-search {
                width: 100%;
                margin-top: 15px;
            }
        }
    </style>
</head>

<body>

    <nav>
        <a href="index.php" class="logo">Hotels<span>.com</span></a>
        <div class="nav-links">
            <a href="search.php">Browse Hotels</a>
            <a href="my_bookings.php">My Trips</a>
        </div>
    </nav>

    <div class="hero">
        <h1>Your Next Story Starts Here</h1>
        <p>Book the world’s most unique stays and luxury hotels.</p>
        <div class="search-box">
            <div class="search-field">
                <label>Where to?</label>
                <input type="text" id="destination" placeholder="Try 'London' or 'Paris'">
            </div>
            <div class="search-field">
                <label>Check-in</label>
                <input type="date" id="cin" value="<?php echo $today; ?>" min="<?php echo $today; ?>">
            </div>
            <div class="search-field">
                <label>Check-out</label>
                <input type="date" id="cout" value="<?php echo $tomorrow; ?>" min="<?php echo $today; ?>">
            </div>
            <button class="btn-search" onclick="doSearch()">Search</button>
        </div>
    </div>

    <div class="container">
        <div class="section-title">
            <h2>Hand-picked Luxury</h2>
            <p>The highest rated stays across the globe</p>
        </div>

        <?php if (isset($error)): ?>
            <p style="text-align:center; color:red;">Database Error: <?php echo $error; ?></p>
        <?php endif; ?>

        <div class="grid">
            <?php foreach ($featured as $h): ?>
                <div class="card" onclick="location.href='hotel.php?id=<?php echo $h['id']; ?>'">
                    <div class="badge">Featured Stay</div>
                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                        class="card-img">
                    <div class="card-content">
                        <h3 class="card-title"><?php echo htmlspecialchars($h['name']); ?></h3>
                        <p class="card-loc">📍 <?php echo htmlspecialchars($h['location']); ?></p>
                        <div class="card-footer">
                            <div class="price">$<?php echo $h['price_per_night']; ?> <span>/ night</span></div>
                            <div class="score"><?php echo $h['rating']; ?> ★</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function doSearch() {
            const loc = document.getElementById('destination').value.trim();
            const inDate = document.getElementById('cin').value;
            const outDate = document.getElementById('cout').value;

            if (!loc) {
                alert('Please enter a destination.');
                return;
            }

            // Fixed JS Redirection
            window.location.href = `search.php?location=${encodeURIComponent(loc)}&checkin=${inDate}&checkout=${outDate}`;
        }
    </script>

</body>

</html>
