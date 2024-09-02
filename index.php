<?php
// Start session
session_start();

// Include database connection
require 'db.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$current_page = basename($_SERVER['PHP_SELF']);

// Fetch services and offers from the database
$services_query = "SELECT * FROM services LIMIT 3";
$services_result = $conn->query($services_query);

/*$offers_query = "SELECT * FROM offers LIMIT 3";
$offers_result = $conn->query($offers_query);*/

$search_error = "";

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

if (isset($_GET['query'])) {
    $search_query = strtolower(trim($_GET['query']));
    $matched = false;

    // Search through the keyword mappings
    foreach ($keyword_mapping as $service => $keywords) {
        if (strpos($service, $search_query) !== false || in_array($search_query, $keywords)) {
            $matched = true;
            break;
        }
    }

    if ($matched) {
        // Redirect to the services page if a match is found
        header("Location: services.php?query=" . urlencode($search_query));
        exit();
    } else {
        // Set an error message if no match is found
        $search_error = "Sorry, we couldn't find any services or facilities matching your search.";
    }
}

// Fetch gallery images 
$gallery_query = "SELECT * FROM gallery";
$gallery_result = $conn->query($gallery_query);

// Fetch FAQs from the database
$faqs_query = "SELECT f.question, f.answer, u.name AS customer_name, s.role AS staff_role 
               FROM faqs f
               JOIN users u ON f.user_id = u.user_id
               JOIN staff s ON f.staff_id = s.staff_id";
$faqs_result = $conn->query($faqs_query);

// Handle question submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question'])) {
    $user_id = $_SESSION['user_id']; // Assuming user is logged in
    $question = $_POST['question'];

    $stmt = $conn->prepare("INSERT INTO faqs (user_id, question) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $question);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Your question has been submitted!";
    } else {
        $_SESSION['error'] = "Failed to submit your question. Please try again.";
    }
    $stmt->close();
}

// Fetch questions and answers
$faqs_query = "SELECT f.question, f.answer, u.name AS customer_name
               FROM faqs f
               JOIN users u ON f.user_id = u.user_id
               ORDER BY f.faq_id DESC";
$faqs_result = $conn->query($faqs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Restaurant - Home</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <!-- Existing code for header and hero section -->
    <section class="hero">
        <img src="images/index_1.jpg" alt="Restaurant Hero Image">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h2>Welcome to ABC Restaurant</h2>
            <p>Delighting your taste buds across Sri Lanka. Discover our exquisite menu, exceptional services, and unforgettable dining experience.</p>
            <form action="index.php" method="get">
                <input type="text" name="query" placeholder="Search services, facilities..." class="search-input" required>
                <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
            </form>
            <!-- Display the error message if search fails -->
            <?php if (!empty($search_error)): ?>
                <p style="color: #ff4d4d; font-weight: bold; margin-top: 10px;"><?php echo $search_error; ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- About Us Section -->
<section class="about-us">
    <div class="about-content">
        <div class="about-text">
            <h3>About Us</h3>
            <p>
                Established in 2023, ABC Restaurant has been a cornerstone of culinary excellence in Sri Lanka. Our journey began with a simple mission: to bring people together through the love of food. Over the decades, we have grown from a single establishment to a renowned restaurant chain, offering an unforgettable dining experience that blends tradition with innovation.
            </p>
            <p>
                Our restaurant's legacy is built on a foundation of quality, authenticity, and a commitment to our community. From our carefully curated menu to our welcoming atmosphere, we strive to create moments that matter.
            </p>
        </div>
        <div class="about-image">
            <img src="images/about.jpg" alt="Our restaurant's rich history">
        </div>
    </div>
    
    <div class="mission-vision">

    <div class="values">
    <h4>Our Values</h4>
    <div class="values-grid">
        <div class="value-item">
            <div class="value-icon">
                <i class="fas fa-star"></i>
            </div>
            <h5>Excellence</h5>
            <p>We strive for perfection in every dish and every interaction.</p>
        </div>
        <div class="value-item">
            <div class="value-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <h5>Integrity</h5>
            <p>We are committed to honesty, transparency, and ethical practices.</p>
        </div>
        <div class="value-item">
            <div class="value-icon">
                <i class="fas fa-users"></i>
            </div>
            <h5>Community</h5>
            <p>We believe in giving back to the communities we serve.</p>
        </div>
        <div class="value-item">
            <div class="value-icon">
                <i class="fas fa-lightbulb"></i>
            </div>
            <h5>Innovation</h5>
            <p>We embrace creativity and new ideas to continuously improve.</p>
        </div>
    </div>
</div>

</section>


<!-- Gallery Section -->
<section class="gallery">
    <h3>Gallery</h3>
    <div class="gallery-grid">
        <!-- 1st Row - 4 Columns -->
        <div class="gallery-row">
            <div class="gallery-item">
                <img src="images/1gal.jpg" alt="Elegant dining area with ambient lighting">
            </div>
            <div class="gallery-item">
                <img src="images/2gal.jpg" alt="Cozy seating arrangement with modern decor" style="width: 100%; height: auto;">
            </div>
            <div class="gallery-item">
                <img src="images/3gal.jpg" alt="A private event held at our restaurant" style="width: 100%; height: 70vh;">
            </div>
            <div class="gallery-item">
                <img src="images/4gal.jpg" alt="Signature dish beautifully plated">
            </div>
        </div>

        <!-- 2nd Row - 2 Columns -->
        <div class="gallery-row">
            <div class="gallery-item">
                <img src="images/5gal.jpg" alt="Delicious dessert served with a smile">
            </div>
            <div class="gallery-item">
                <img src="images/6gal.jpg" alt="Live music event enhancing the dining experience">
            </div>
        </div>

        <!-- 3rd Row - 4 Columns -->
        <div class="gallery-row">
            <div class="gallery-item">
                <img src="images/7gal.jpg" alt="Modern dining area with stylish decor" style="width: 100%; height: 47vh;">
            </div>
            <div class="gallery-item">
                <img src="images/8gal.png" alt="Gourmet dish presented artistically" style="width: 100%; height: 47vh;">
            </div>
            <div class="gallery-item">
                <img src="images/9gal.jpeg" alt="A festive celebration at our venue">
            </div>
        </div>

        <!-- 4th Row - 3 Columns -->
        <div class="gallery-row">
            <div class="gallery-item">
                <img src="images/10gal.jpg" alt="A mouth-watering dessert elegantly served" style="width: 100%; height: 60vh;">
            </div>
            <div class="gallery-item">
                <img src="images/11gal.jpg" alt="Chic and cozy interior for a relaxed dining experience" style="width: 100%; height: 60vh;">
            </div>
        </div>
    </div>
</section>


       <!-- FAQs Section -->
    <section class="faqs">
        <h3>Frequently Asked Questions</h3>

        <!-- Ask a Question Form -->
        <div class="ask-question">
            <h4>Have a question? Ask us!</h4>
            <?php if (isset($_SESSION['message'])): ?>
                <p class="success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
            <form action="index.php" method="post">
                <div class="input-group">
                    <textarea name="question" placeholder="Type your question here..." required></textarea>
                </div>
                <button type="submit" class="ask-btn"><i class="fas fa-paper-plane"></i> Submit Question</button>
            </form>
        </div>

        <!-- Display Questions and Answers -->
        <div class="faqs-list">
            <?php while($faq = $faqs_result->fetch_assoc()): ?>
                <div class="faq-item">
                    <h4><i class="fas fa-question-circle"></i> <?php echo $faq['question']; ?></h4>
                    <p><i class="fas fa-user"></i> Asked by: <?php echo $faq['customer_name']; ?></p>
                    <?php if (!empty($faq['answer'])): ?>
                        <div class="answer">
                            <p><?php echo $faq['answer']; ?></p>
                            <p class="answered-by"><i class="fas fa-user-tag"></i> Answered by: Staff</p>
                        </div>
                    <?php else: ?>
                        <div class="no-answer">
                            <p><i class="fas fa-info-circle"></i> This question has not been answered yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 ABC Restaurant. All rights reserved.</p>
            <div class="footer-links">
                <a href="privacy.php">Privacy Policy</a> | 
                <a href="terms.php">Terms of Service</a>
            </div>
        </div>
    </footer>
</body>
</html>
