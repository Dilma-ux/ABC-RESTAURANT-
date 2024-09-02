<?php
session_start();
// Database connection
require 'db.php'; 

$db = Database::getInstance();
$conn = $db->getConnection();

$success_message = "";
$error_message = ""; // Variable to hold the error message

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reservation'])) {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $guests = $_POST['guests'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $table_number = $_POST['table_number'];
    $special_requests = $_POST['special_requests'];

    // Check if the table is already reserved for the same date, time, and location
    $check_query = "SELECT * FROM reservations WHERE reservation_date = ? AND reservation_time = ? AND table_number = ? AND location = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('ssis', $date, $time, $table_number, $location);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Table is already reserved
        $error_message = "The selected table is not available for the specified date and time.";
    } else {
        // Insert the reservation if the table is available
        $query = "INSERT INTO reservations (user_id, service_type, location, reservation_date, reservation_time, table_number, status)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $user_id = $_SESSION['user_id']; // Example user_id from session
        $service_type = 'Dine-In'; // Example service type
        $status = 'pending'; // Default status

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('issssis', $user_id, $service_type, $location, $date, $time, $table_number, $status);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Reservation successful!";
                header("Location: dinein_reservation.php");
                exit();
            } else {
                $error_message = "Failed to insert reservation.";
            }
            $stmt->close();
        } else {
            $error_message = "Failed to prepare the SQL statement.";
        }
    }
}

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve a Table | ABC Restaurant</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reservation-form-container {
            background: #fff;
            margin-top: 20%;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .reservation-form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
            position: relative;
            font-weight: 600;
        }

        .reservation-form-container h2::after {
            content: '';
            width: 60px;
            height: 3px;
            background-color: #ff8c00;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
            color: #000;
        }
        .input-group input,
        .input-group select,
        .input-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f9f9f9;
            color: #000; /* Ensure the text is black */
        }

        .input-group select option {
            color: #000; /* Ensure all options are black */
        }


        .form-actions {
            margin-top: 20px;
        }

        .form-actions button {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        .form-actions .submit-btn {
            background-color: #28a745;
            color: #fff;
            margin-right: 10px;
        }

        .form-actions .submit-btn:hover {
            background-color: #218838;
        }

        .form-actions .cancel-btn {
            background-color: #dc3545;
            color: #fff;
        }

        .form-actions .cancel-btn:hover {
            background-color: #c82333;
        }

        .success-message {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }

        .go-home-btn {
            display: inline-block;
            margin-top: 28px;
            padding: 12px 25px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border-radius: 50px; /* Rounded corners */
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 50%;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2); /* Add shadow for 3D effect */
        }

        .go-home-btn:hover {
            background-color: #0056b3;
            transform: translateY(-3px); /* Slight lift on hover */
        }
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }
    </style>

    <script>
        function clearForm() {
            // Manually reset all form fields
            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('phone').value = '';
            document.getElementById('guests').value = '';
            document.getElementById('date').value = '';
            document.getElementById('time').value = '';
            document.getElementById('location').value = '';
            document.getElementById('table_number').selectedIndex = 0;
            document.getElementById('special_requests').value = '';
        }
    </script>
</head>
<body>
<div id="reservation-form" class="reservation-form-container">
    <h2>Reserve a Table</h2><br>

    <?php if (!empty($success_message)): ?>
        <div class="success-message"><?php echo $success_message; ?></div>
        <a href="index.php" class="go-home-btn">Go Home</a>
    <?php elseif (!empty($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
        <a href="index.php" class="go-home-btn">Go Home</a>
    <?php else: ?>
        <form action="dinein_reservation.php" method="POST" id="reservation-form">
            <div class="input-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="input-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" required>
            </div>
            <div class="input-group">
                <label for="guests">Number of Guests</label>
                <input type="number" name="guests" id="guests" required>
            </div>
            <div class="input-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" required>
            </div>
            <div class="input-group">
                <label for="time">Time</label>
                <input type="time" name="time" id="time" required>
            </div>
            <div class="input-group">
                <label for="location">Location of the Restaurant</label>
                <input type="text" name="location" id="location" required>
            </div>
            <div class="input-group">
                <label for="table_number">Table Number</label>
                <select name="table_number" id="table_number" required>
                    <option value="">Select Table Number</option>
                    <?php for ($i = 1; $i <= 15; $i++): ?>
                        <option value="<?php echo $i; ?>">Table <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="input-group">
                <label for="special_requests">Any Special Requirements</label>
                <textarea name="special_requests" id="special_requests" rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" name="submit_reservation" class="submit-btn">Submit</button>
                <button type="button" class="cancel-btn" onclick="clearForm();">Cancel</button>
            </div>
        </form>
    <?php endif; ?>
</div>


</body>
</html>