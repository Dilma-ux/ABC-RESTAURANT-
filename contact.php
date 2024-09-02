<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Restaurant - Contact Us</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        .locations-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .location-item {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            flex: 20%; /* Take up nearly half the width of the container */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .location-item h4 {
            margin-bottom: 10px;
            font-size: 1.2rem;
            color: #333;
        }

        .location-item p {
            margin: 5px 0;
            color: #666;
        }

        .map {
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .location-item {
                flex: 0 0 100%; /* Take up the full width on smaller screens */
            }
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .restaurant-review {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-left: 8px solid #ff8c00;
        }

        .restaurant-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
            margin-bottom: 20px;
        }

        .restaurant-header h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
            display: flex;
            align-items: center;
        }

        .restaurant-header h2 i {
            margin-right: 10px;
            color: #ff8c00;
        }

        .stars {
            font-size: 24px;
            color: #FFD700;
            display: flex;
            align-items: center;
        }

        .review-item {
            background-color: #fafafa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.3s;
        }

        .review-item:hover {
            background-color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .review-item .stars {
            margin-right: 15px;
            font-size: 20px;
            color: #FFD700;
            flex-shrink: 0;
        }

        .review-content {
            flex: 1;
        }

        .review-content strong {
            font-size: 18px;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .review-content p {
            margin: 0;
            font-size: 16px;
            color: #555;
        }

        .review-footer {
            margin-top: 10px;
            font-size: 14px;
            color: #999;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .review-footer i {
            color: #ff8c00;
        }

        .review-footer .date {
            font-style: italic;
        }

        .review-footer .verified {
            color: #4CAF50;
            display: flex;
            align-items: center;
        }

        .review-footer .verified i {
            margin-right: 5px;
        }

    </style>

</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <section class="contact">
        <h2>Contact Us</h2>

        <!-- Locations Section -->
        <div class="locations">
            <h3>Our Locations</h3>
            <div class="locations-grid">
                <!-- Row 1 -->
                <div class="location-item">
                    <h4>Main Branch - Colombo</h4>
                    <p><strong>Address:</strong> 123 Main Street, Colombo, Sri Lanka</p>
                    <p><strong>Phone:</strong> +94 11 2345678</p>
                    <p><strong>Email:</strong> colombo@abc-restaurant.lk</p>
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3914.533958761574!2d79.8612433153319!3d6.927079295002828!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2591b1a2b1e5f%3A0x1f2f97c1e8438b9!2sMain%20Street%2C%20Colombo!5e0!3m2!1sen!2slk!4v1605704667556!5m2!1sen!2slk" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="location-item">
                    <h4>Branch - Kandy</h4>
                    <p><strong>Address:</strong> 45 Hill Street, Kandy, Sri Lanka</p>
                    <p><strong>Phone:</strong> +94 81 2233445</p>
                    <p><strong>Email:</strong> kandy@abc-restaurant.lk</p>
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.7594423112565!2d80.63669671515904!3d7.290571794726492!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae38f2cdbbd0b3f%3A0x92b0a292db6e71aa!2sHill%20Street%2C%20Kandy!5e0!3m2!1sen!2slk!4v1605704781237!5m2!1sen!2slk" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="location-item">
                    <h4>Branch - Galle</h4>
                    <p><strong>Address:</strong> 78 Lighthouse Street, Galle, Sri Lanka</p>
                    <p><strong>Phone:</strong> +94 91 2233445</p>
                    <p><strong>Email:</strong> galle@abc-restaurant.lk</p>
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3923.1786734425183!2d80.2186377152924!3d6.042187995620583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae1732bf5bfb29d%3A0x713ebd3b1ed0b21e!2sLighthouse%20Street%2C%20Galle%2080000%2C%20Sri%20Lanka!5e0!3m2!1sen!2sus!4v1620858123031!5m2!1sen!2sus" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <!-- Row 2 -->
                <div class="location-item">
                    <h4>Branch - Jaffna</h4>
                    <p><strong>Address:</strong> 23 Temple Road, Jaffna, Sri Lanka</p>
                    <p><strong>Phone:</strong> +94 21 2233445</p>
                    <p><strong>Email:</strong> jaffna@abc-restaurant.lk</p>
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3922.3086372752337!2d80.01289781529254!3d9.668045193258048!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3afd555504e34431%3A0x40e9c0d42c92d77a!2sTemple%20Rd%2C%20Jaffna!5e0!3m2!1sen!2sus!4v1620858300341!5m2!1sen!2sus" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="location-item">
                    <h4>Branch - Matara</h4>
                    <p><strong>Address:</strong> 56 Beach Road, Matara, Sri Lanka</p>
                    <p><strong>Phone:</strong> +94 41 2233445</p>
                    <p><strong>Email:</strong> matara@abc-restaurant.lk</p>
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3922.3086372752337!2d80.54703261529254!3d5.948460995632726!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae1b2c260573f75%3A0x715c6f43d5f5a202!2sBeach%20Rd%2C%20Matara!5e0!3m2!1sen!2sus!4v1620858496512!5m2!1sen!2sus" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="location-item">
                    <h4>Branch - Negombo</h4>
                    <p><strong>Address:</strong> 89 Sea Street, Negombo, Sri Lanka</p>
                    <p><strong>Phone:</strong> +94 31 2233445</p>
                    <p><strong>Email:</strong> negombo@abc-restaurant.lk</p>
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3923.7086374426183!2d79.8416678152924!3d7.208460795723493!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2590a2a2c314f%3A0x613acb1c1b3c1b9e!2sSea%20St%2C%20Negombo!5e0!3m2!1sen!2sus!4v1620858601063!5m2!1sen!2sus" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Section -->
        <div class="container">
            <div class="restaurant-review">
                <div class="restaurant-header">
                    <h2><i class="fas fa-pizza-slice"></i> ABC Restaurant</h2>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="review-item">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="review-content">
                        <strong>Review 1</strong>
                        <p>Delicious food! I went there for my birthday. The service was great. I recommend the brick oven pizza!</p>
                        <div class="review-footer">
                            <span class="date"><i class="fas fa-calendar-alt"></i> May 28, 2024</span>
                            
                        </div>
                    </div>
                </div>

                <div class="review-item">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="review-content">
                        <strong>Review 2</strong>
                        <p>Best freaking "thicker" pizza on the west side! Must get the 8corner pizza! The crust is buttery and crispy with burnt cheese on the corners!</p>
                        <div class="review-footer">
                            <span class="date"><i class="fas fa-calendar-alt"></i> July 12, 2024</span>
                            
                        </div>
                    </div>
                </div>

                <div class="review-item">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="review-content">
                        <strong>Review 3</strong>
                        <p>The best Chicago style pizza in town! Being from the east coast, that should say a lot.</p>
                        <div class="review-footer">
                            <span class="date"><i class="fas fa-calendar-alt"></i> July 14, 2024</span>
                            
                        </div>
                    </div>
                </div>

                <div class="review-item">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="review-content">
                        <strong>Review 4</strong>
                        <p>Their pizza needs to be in your mouth. BUTTERY crust. Like, butter buttery. And it's superb reheated at home in the toaster oven.</p>
                        <div class="review-footer">
                            <span class="date"><i class="fas fa-calendar-alt"></i> July 25, 2024</span>
                            
                        </div>
                    </div>
                </div>

                <div class="review-item">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="review-content">
                        <strong>Review 5</strong>
                        <p>5 stars it is! Best pizza in town! The house made mozzarella is the best and the service is always excellent.</p>
                        <div class="review-footer">
                            <span class="date"><i class="fas fa-calendar-alt"></i> Auguest 18, 2024</span>
                            
                        </div>
                    </div>
                </div>
            </div>
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
