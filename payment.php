<?php
session_start();
require 'db.php';
$db = Database::getInstance();
$conn = $db->getConnection();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$customer_name = $_SESSION['name']; // Assuming the username is stored in the session
$items = isset($_GET['items']) ? json_decode($_GET['items'], true) : [];
$total_price = isset($_GET['total']) ? $_GET['total'] : 0;

$delivery_location = ''; // Assuming you want the user to input the delivery location here
$phone_number = ''; // Assuming you want the user to input their phone number here

$item_names = [];
if (!empty($items)) {
    foreach ($items as $item) {
        $item_names[] = $item['name'];
    }
    $item_names_string = implode(', ', $item_names);
}

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $delivery_location = $_POST['delivery_location'];
    $phone_number = $_POST['phone_number'];
    $items_json = json_encode($items);
    $item_names_string = $_POST['item_names']; // Retrieve item names from hidden field
    $total_price = $_POST['total_price']; // Retrieve total price from hidden field
    // Insert payment details into the payments table
    $insert_query = "INSERT INTO payments (user_id, customer_name, items, total_price, delivery_location, phone_number, status) VALUES (?, ?, ?, ?, ?, ?, 'paid')";
    $stmt = $conn->prepare($insert_query);
     // Convert items array to JSON for storage
    $stmt->bind_param('isssss', $user_id, $customer_name,  $item_names_string, $total_price, $delivery_location, $phone_number);
    
    if ($stmt->execute()) {
        $payment_successful = true;
    } else {
        $_SESSION['payment_error'] = "There was an issue processing your payment. Please try again.";
    }    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Restaurant - Payment</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        .payment-form {
            width: 100%;
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .payment-form h2 {
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: #333;
        }

        .payment-form .input-group {
            margin-bottom: 15px;
        }

        .payment-form .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .payment-form .input-group input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .payment-form .total-price {
            margin-top: 15px;
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .payment-form .payment-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .payment-form .payment-actions button {
            padding: 12px 25px;
            border: none;
            background-color: #ff8c00;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .payment-form .payment-actions .cancel-btn {
            background-color: #f44336;
        }

        .payment-form .payment-actions button:hover {
            opacity: 0.9;
        }

        .success-message {
            color: #155724; /* Dark green text color */
            background-color: #d4edda; /* Light green background */
            border: 1px solid #c3e6cb; /* Green border */
            padding: 10px 20px; /* Padding for space inside the message */
            margin: 20px 0; /* Margin to give some space around the message */
            border-radius: 5px; /* Slightly rounded corners */
            font-weight: bold; /* Bold text */
            font-size: 18px; /* Slightly larger text */
            text-align: center; /* Center the text */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow to make it pop */
        }

        .go-home-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .go-home-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <section class="payment-form">
        <h2>Confirm Your Payment</h2>

        <?php if (isset($payment_successful) && $payment_successful): ?>
            <p class="success-message">Payment Successful! Thank you for your order.</p>
            <a href="index.php" class="go-home-btn">Go Home</a>
        <?php else: ?>
            <form method="POST" action="payment.php">
                <div class="input-group">
                    <label for="customer_name">Customer Name:</label>
                    <input type="text" id="customer_name" name="customer_name" value="<?php echo $customer_name; ?>" readonly>
                </div>
                <div class="input-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>
                </div>
                <div class="input-group">
                    <label for="delivery_location">Delivery Location:</label>
                    <input type="text" id="delivery_location" name="delivery_location" placeholder="Enter delivery location" required>
                </div>
                <div class="input-group">
                    <label>Selected Items:</label>
                    <ul>
                        <?php foreach ($items as $item): ?>
                            <li><?php echo $item['name']; ?> - <?php echo $item['price']; ?> LKR</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="total-price">
                    Total: <?php echo $total_price; ?> LKR
                </div>
                 <!-- Hidden fields to pass data to the POST request -->
                 <input type="hidden" name="item_names" value="<?php echo $item_names_string; ?>">
                <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

                <div class="payment-actions">
                    <button type="submit" name="confirm_payment">Confirm Payment</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='menu.php'">Cancel</button>
                </div>
            </form>
        <?php endif; ?>
    </section>
</body>
</html>
