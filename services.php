<?php
session_start();
require 'db.php';
$db = Database::getInstance();
$conn = $db->getConnection();

$current_page = basename($_SERVER['PHP_SELF']);

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Store the logged-in user ID

// Initialize variables
$search_query = "";
$highlight_service = "";
$highlight_facility = "";

$search_results = [];
if (isset($_GET['query'])) {
    $search_query = strtolower(trim($_GET['query']));
    $search_term = "%" . $search_query . "%";

    // Define mapping of keywords to services and facilities
    $keyword_mapping = [
        'dine-in' => ['dine', 'dining', 'eat-in', 'restaurant', 'table service'],
        'delivery' => ['deliver', 'home delivery', 'food delivery'],
        'catering' => ['cater', 'catering service', 'events', 'party service'],
        'event hosting' => ['events', 'parties', 'functions', 'event space'],
        'free wi-fi' => ['wifi', 'internet', 'wireless internet', 'free wifi'],
        'parking' => ['car park', 'parking space', 'vehicle parking'],
        'private dining rooms' => ['private rooms', 'separate dining', 'private space'],
        'outdoor seating' => ['outside seating', 'garden seating', 'patio', 'outdoor dining'],
        'kids play area' => ['playground', 'children area', 'kids area'],
        'live music' => ['music', 'bands', 'live performance', 'entertainment']
    ];

    // Search through the keyword mappings
    foreach ($keyword_mapping as $service => $keywords) {
        if (strpos($service, $search_query) !== false || in_array($search_query, $keywords)) {
            if (in_array($service, ['dine-in', 'delivery', 'catering', 'event hosting'])) {
                $highlight_service = $service;
            } else {
                $highlight_facility = $service;
            }
            break;
        }
    }

    // Log user search activity
    $activity = "User searched for '{$search_query}'";
    $log_activity_query = "INSERT INTO user_activity (user_id, activity) VALUES (?, ?)";
    $stmt = $conn->prepare($log_activity_query);
    $stmt->bind_param('is', $user_id, $activity);
    $stmt->execute();
}

// Fetch services from the database
$services_query = "SELECT * FROM services";
$services_result = $conn->query($services_query);

// Fetch facilities from the database
$facilities_query = "SELECT * FROM facilities";
$facilities_result = $conn->query($facilities_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve'])) {
    $service_type = $_POST['reservation_type'];

    // Log reservation type selection
    $activity = "User selected reservation type '{$service_type}'";
    $log_activity_query = "INSERT INTO user_activity (user_id, activity) VALUES (?, ?)";
    $stmt = $conn->prepare($log_activity_query);
    $stmt->bind_param('is', $user_id, $activity);
    $stmt->execute();

    if ($service_type === 'dine-in') {
        header('Location: dinein_reservation.php');
        exit;
    } else {
        header('Location: menu.php'); // Redirect to menu page for delivery
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Restaurant - Services</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .highlight-service {
            background-color: #ff0000; /* background for service cards */
            border: 3px solid #ff8b00;  /* Darker border */
            box-shadow: 0 0 15px rgba(255, 243, 0); /* glowing shadow */
            transform: scale(1.05); /* Slightly increase the size of the card */
            transition: all 0.3s ease-in-out; /* Smooth transition for the effects */
        }
        .highlight-facility {
            background-color: #2196f3; 
            border: 3px solid #1976d2;  
            box-shadow: 0 0 15px rgba(33, 150, 243, 0.7); 
            transform: scale(1.05); 
            transition: all 0.3s ease-in-out; 
        }
        .highlight h3, .highlight h4 {
            font-weight: bold; /* Make the text bold */
            color: #fff; /* Ensure the text color is readable */
        }
/* Container Styles */
.reservation-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
    background-color: #f4f4f4;
}

/* Reservation Form Styles */
.reservation-section {
    margin-top: -1%; /* Center alignment */
    background: #ffffff;
    padding: 70px;
    border-radius: 15px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    max-width: 450px;
    width: 100%;
    text-align: center;
    transition: transform 0.3s ease-in-out;
    font-family: 'Poppins', sans-serif;
}

.reservation-section:hover {
    transform: scale(1.02);
}

.reservation-section h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
    position: relative;
}

.reservation-section h2::after {
    content: '';
    width: 40px;
    height: 2px;
    background-color: #ff8c00;
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
}

.input-group label {
    font-weight: bold;
    font-size: 14px;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

.input-group select,
.input-group input,
.input-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    color: #333;
    background-color: #f9f9f9;
    transition: border-color 0.3s, background-color 0.3s;
}

.input-group select:focus,
.input-group input:focus,
.input-group textarea:focus {
    border-color: #ff8c00;
    background-color: #fff;
    outline: none;
    box-shadow: 0 0 10px rgba(255, 140, 0, 0.3);
}

#proceed-btn {
    background-color: #ff8c00;
    color: #fff;
    padding: 12px 20px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
    width: 100%;
    text-transform: uppercase;
    font-weight: bold;
}

#proceed-btn:hover {
    background-color: #e67e22;
    transform: translateY(-3px);
}



/* Responsive Design */
@media (max-width: 768px) {
    .reservation-section {
        padding: 30px;
    }

    .reservation-section h2 {
        font-size: 28px;
    }

    #proceed-btn {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <section class="services-overview">
        <h2>Our Services</h2>
        <div class="services-grid">
            <?php 
            $services = [
                [
                    'name' => 'Dine-In',
                    'description' => 'Enjoy a cozy and elegant dining experience at our restaurant. Perfect for family gatherings, date nights, and more.',
                    'icon' => 'fas fa-utensils'
                ],
                [
                    'name' => 'Delivery',
                    'description' => 'Get your favorite meals delivered straight to your doorstep with our efficient and prompt delivery service.',
                    'icon' => 'fas fa-truck'
                ],
                [
                    'name' => 'Catering',
                    'description' => 'Our catering services provide delicious and beautifully presented food for events of all sizes.',
                    'icon' => 'fas fa-concierge-bell'
                ],
                [
                    'name' => 'Event Hosting',
                    'description' => 'Host your special events at our venue. We provide everything from event planning to exquisite dining.',
                    'icon' => 'fas fa-calendar-alt'
                ],
            ];

            foreach ($services as $service): ?>
                <div id="service-<?php echo strtolower(str_replace(' ', '-', $service['name'])); ?>" class="service-item <?php echo (strtolower($service['name']) === $highlight_service) ? 'highlight-service' : ''; ?>">
                    <i class="<?php echo $service['icon']; ?> service-icon"></i>
                    <h3><?php echo $service['name']; ?></h3>
                    <p><?php echo $service['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="facilities">
        <h2>Our Facilities</h2>
        <div class="facilities-grid">
            <?php 
            $facilities = [
                [
                    'name' => 'Free Wi-Fi',
                    'description' => 'Stay connected while you enjoy your meal with our complimentary high-speed Wi-Fi.',
                    'icon' => 'fas fa-wifi'
                ],
                [
                    'name' => 'Parking',
                    'description' => 'Convenient and secure parking available for all our guests.',
                    'icon' => 'fas fa-parking'
                ],
                [
                    'name' => 'Private Dining Rooms',
                    'description' => 'Exclusive private dining rooms for a more intimate experience.',
                    'icon' => 'fas fa-door-closed'
                ],
                [
                    'name' => 'Outdoor Seating',
                    'description' => 'Relax in our beautiful outdoor seating area, perfect for enjoying the fresh air.',
                    'icon' => 'fas fa-cloud-sun'
                ],
                [
                    'name' => 'Kids Play Area',
                    'description' => 'A fun and safe play area for children to enjoy while you dine.',
                    'icon' => 'fas fa-child'
                ],
                [
                    'name' => 'Live Music',
                    'description' => 'Enjoy live performances from talented local artists.',
                    'icon' => 'fas fa-music'
                ],
            ];

            foreach ($facilities as $facility): ?>
                <div id="facility-<?php echo strtolower(str_replace(' ', '-', $facility['name'])); ?>" class="facility-item <?php echo (strtolower($facility['name']) === $highlight_facility) ? 'highlight-facility' : ''; ?>">
                    <i class="<?php echo $facility['icon']; ?> facility-icon"></i>
                    <h3><?php echo $facility['name']; ?></h3>
                    <p><?php echo $facility['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    

    <div class="reservation-container">
    <section class="reservation-section">
        <h2>Reservation Type</h2><br>
        <form action="services.php" method="post">
            <div class="input-group">
                <label for="reservation_type">Choose Reservation Type:</label><br>
                <select name="reservation_type" id="reservation_type" required>
                    <option value="dine-in">Dine-In</option>
                    <option value="delivery">Delivery</option>
                </select>
            </div>
            <button type="submit" id="proceed-btn">Proceed</button>
        </form>
    </section>
</div>


    <footer>
        <div class="footer-content">
            <p>&copy; 2024 ABC Restaurant. All rights reserved.</p>
            <div class="footer-links">
                <a href="privacy.php">Privacy Policy</a> | 
                <a href="terms.php">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            <?php if ($highlight_service): ?>
                $('html, body').animate({
                    scrollTop: $("#service-<?php echo strtolower(str_replace(' ', '-', $highlight_service)); ?>").offset().top - 20
                }, 1000);
            <?php endif; ?>

            <?php if ($highlight_facility): ?>
                $('html, body').animate({
                    scrollTop: $("#facility-<?php echo strtolower(str_replace(' ', '-', $highlight_facility)); ?>").offset().top - 20
                }, 1000);
            <?php endif; ?>

            // Handle reservation type selection
            $('#proceed-btn').click(function(e) {
                e.preventDefault();
                let reservationType = $('#reservation_type').val();
                if (reservationType === 'delivery') {
                    window.location.href = 'menu.php'; // Redirect to menu page for delivery
                } else {
                    window.location.href = 'dinein_reservation.php'; // Redirect to a dine-in reservation form page
                }
            });
        });
    </script>
</body>
</html>

