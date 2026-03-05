<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = __DIR__ . '/config.php';
if (file_exists($config))
    require_once $config;
else
    die("Config missing!");

$location = $_GET['location'] ?? '';
$sort = $_GET['sort'] ?? 'price_low';

$hotels = [];
try {
    $sql = "SELECT * FROM hotels WHERE (location LIKE ? OR name LIKE ?)";
    $params = ["%$location%", "%$location%"];

    if ($sort === 'price_high')
        $sql .= " ORDER BY price_per_night DESC";
    elseif ($sort === 'rating')
        $sql .= " ORDER BY rating DESC";
    else
        $sql .= " ORDER BY price_per_night ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $hotels = $stmt->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Hotels | Hotels.com Clone</title>
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

        .container {
            display: flex;
            padding: 3rem 8%;
            gap: 3rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Sidebar */
        .sidebar {
            flex: 1;
            max-width: 300px;
            background: var(--white);
            border-radius: 20px;
            padding: 2rem;
            height: fit-content;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .sidebar h3 {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        .filter-group {
            margin-bottom: 2rem;
        }

        .filter-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-weight: 500;
            cursor: pointer;
            color: #555;
        }

        /* Main */
        .main {
            flex: 3;
        }

        .header {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .header h2 {
            font-size: 1.5rem;
        }

        .sort-select {
            padding: 10px 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            font-weight: 600;
            cursor: pointer;
        }

        .hotel-card {
            background: white;
            border-radius: 20px;
            display: flex;
            margin-bottom: 2rem;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
            cursor: pointer;
        }

        .hotel-card:hover {
            transform: scale(1.01);
        }

        .h-img {
            width: 350px;
            height: 240px;
            object-fit: cover;
        }

        .h-body {
            flex: 1;
            padding: 2rem;
            display: flex;
            flex-direction: column;
        }

        .h-name {
            font-size: 1.6rem;
            margin-bottom: 5px;
        }

        .h-loc {
            color: #888;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .h-amenities {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .tag {
            background: #eef2f7;
            color: var(--secondary);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .h-right {
            width: 200px;
            padding: 2rem;
            border-left: 1px solid #eee;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-end;
        }

        .h-rating {
            background: #2e7d32;
            color: white;
            padding: 5px 12px;
            border-radius: 8px;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .h-price {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
        }

        .h-price span {
            font-size: 0.8rem;
            color: #777;
            font-weight: 400;
        }

        .btn-view {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 700;
            width: 100%;
            margin-top: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-view:hover {
            background: #002b66;
        }

        @media (max-width: 1000px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                max-width: 100%;
            }

            .hotel-card {
                flex-direction: column;
            }

            .h-img {
                width: 100%;
            }

            .h-right {
                width: 100%;
                border-left: none;
                border-top: 1px solid #eee;
                align-items: flex-start;
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

    <div class="container">
        <div class="sidebar">
            <h3>Filter Deals</h3>
            <div class="filter-group">
                <div class="filter-item"><input type="checkbox" checked> Hotels</div>
                <div class="filter-item"><input type="checkbox"> Resorts</div>
                <div class="filter-item"><input type="checkbox"> Guest Houses</div>
            </div>
            <div class="filter-group">
                <div class="filter-item"><input type="checkbox" checked> Free WiFi</div>
                <div class="filter-item"><input type="checkbox"> Pool</div>
                <div class="filter-item"><input type="checkbox"> Free Breakfast</div>
            </div>
        </div>

        <div class="main">
            <div class="header">
                <h2><?php echo count($hotels); ?> results for "<?php echo htmlspecialchars($location); ?>"</h2>
                <select class="sort-select" onchange="applySort(this.value)">
                    <option value="price_low" <?php if ($sort === 'price_low')
                        echo 'selected'; ?>>Price: Low to High
                    </option>
                    <option value="price_high" <?php if ($sort === 'price_high')
                        echo 'selected'; ?>>Price: High to Low
                    </option>
                    <option value="rating" <?php if ($sort === 'rating')
                        echo 'selected'; ?>>Guest Rating</option>
                </select>
            </div>

            <?php foreach ($hotels as $h): ?>
                <div class="hotel-card" onclick="location.href='hotel.php?id=<?php echo $h['id']; ?>'">
                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                        class="h-img">
                    <div class="h-body">
                        <h3 class="h-name"><?php echo htmlspecialchars($h['name']); ?></h3>
                        <p class="h-loc">📍 <?php echo htmlspecialchars($h['location']); ?></p>
                        <div class="h-amenities">
                            <?php
                            $tags = explode(',', $h['amenities']);
                            foreach ($tags as $t)
                                echo "<span class='tag'>" . trim($t) . "</span>";
                            ?>
                        </div>
                        <p style="font-size: 0.85rem; color: #555; line-height: 1.5;">
                            <?php echo substr($h['description'], 0, 180); ?>...</p>
                    </div>
                    <div class="h-right">
                        <div class="h-rating"><?php echo $h['rating']; ?> ★</div>
                        <div class="h-price">$<?php echo $h['price_per_night']; ?></div>
                        <span>/ night</span>
                        <button class="btn-view">View Deal</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function applySort(val) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', val);
            window.location.href = url.href;
        }
    </script>
</body>

</html>
