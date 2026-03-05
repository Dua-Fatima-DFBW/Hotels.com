-- Database Schema for Hotels.com Clone
-- Database: rsoa_rsoa311_6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Table: hotels
CREATE TABLE IF NOT EXISTS `hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `rating` decimal(3,1) DEFAULT '0.0',
  `price_per_night` decimal(10,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `amenities` text,
  `hotel_type` enum('Luxury', 'Boutique', 'Resort', 'Budget') DEFAULT 'Luxury',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: bookings
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `guest_name` varchar(255) NOT NULL,
  `guest_email` varchar(255) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `booking_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`hotel_id`) REFERENCES hotels(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TRUNCATE and SEED
TRUNCATE TABLE `hotels`;
INSERT INTO `hotels` (`name`, `location`, `description`, `rating`, `price_per_night`, `image_url`, `amenities`, `hotel_type`) VALUES
('The Royal Grandeur', 'London, UK', 'A masterpiece of Victorian architecture combined with modern luxury. Located in the heart of London, offering world-class dining and a state-of-the-art spa.', 4.9, 550.00, 'h1.jpg', 'Spa, Pool, Free WiFi, Gym, 24/7 Room Service', 'Luxury'),
('Azure Palms Resort', 'Maldives', 'Escape to paradise in our overwater villas. Crystal clear turquoise waters and pristine white sands await your arrival at this tropical sanctuary.', 4.8, 890.00, 'h2.jpg', 'Private Beach, Scuba Diving, Infinity Pool, Butler Service', 'Resort'),
('Urban Chic Boutique', 'New York, USA', 'Sleek, edgy, and perfectly situated in SoHo. This boutique gem offers designer rooms and a rooftop bar with unparalleled city views.', 4.6, 420.00, 'h3.jpg', 'Rooftop Bar, Free WiFi, Concierge, Pet Friendly', 'Boutique'),
('Sakura Palace Hotel', 'Tokyo, Japan', 'Experience traditional Japanese hospitality with a modern twist. Elegant rooms with views over the Imperial Palace gardens.', 4.7, 350.00, 'h4.jpg', 'Tea Room, Zen Garden, Onsen, WiFi, Metro Access', 'Luxury'),
('The Parisienne Stay', 'Paris, France', 'Charming and romantic, located just steps from the Eiffel Tower. Quintessential French style with balcony views of the city of lights.', 4.5, 290.00, 'h5.jpg', 'Gourmet Breakfast, WiFi, Balcony, Library', 'Boutique'),
('Sunset Bay Lodge', 'Bali, Indonesia', 'Natural beauty meets modern comfort. Set among rice terraces and overlooking the ocean, perfect for a peaceful getaway.', 4.4, 180.00, 'h6.jpg', 'Yoga Deck, Infinity Pool, Organic Café, WiFi', 'Resort');

COMMIT;
