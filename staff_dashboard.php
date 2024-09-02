<?php
session_start();
require 'db.php';
$db = Database::getInstance();
$conn = $db->getConnection();

$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['position']) || !in_array(strtolower($_SESSION['position']), ['staff', 'admin'])) {
    header("Location: index.php");
    exit();
}

// Fetch reservations
$reservations_query = "SELECT r.*, u.name FROM reservations r JOIN users u ON r.user_id = u.user_id";
$reservations_result = $conn->query($reservations_query);

// Fetch customer queries (FAQs)
$faqs_query = "SELECT f.*, u.name FROM faqs f JOIN users u ON f.user_id = u.user_id";
$faqs_result = $conn->query($faqs_query);

// Fetch payments
$payments_query = "SELECT * FROM payments";
$payments_result = $conn->query($payments_query);

// Update reservation status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_reservation_status'])) {
    $reservation_id = $_POST['reservation_id'];
    $new_status = $_POST['status'];

    // Validate the reservation ID and new status
    if (!empty($reservation_id) && !empty($new_status)) {
        $update_query = "UPDATE reservations SET status = ? WHERE reservation_id = ?";
        $stmt = $conn->prepare($update_query);
        if ($stmt) {
            $stmt->bind_param('si', $new_status, $reservation_id);
            $stmt->execute();
            $stmt->close();
            // Redirect to ensure the form is not resubmitted on page refresh
            header("Location: staff_dashboard.php");
            exit();
        } else {
            echo "Failed to prepare the SQL statement.";
        }
    } else {
        echo "Invalid reservation ID or status.";
    }
}

// Respond to customer queries
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respond_query'])) {
    $faq_id = $_POST['faq_id'] ?? null;
    $response = $_POST['response'] ?? '';

    if ($faq_id) {
        $update_query = "UPDATE faqs SET answer = ?, status = 'answered' WHERE faq_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('si', $response, $faq_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: staff_dashboard.php");
    exit();
}

// Update payment status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment_status'])) {
    $payment_id = $_POST['payment_id'];
    $new_status = $_POST['status'];
    $update_query = "UPDATE payments SET status = ? WHERE payment_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $new_status, $payment_id);
    $stmt->execute();
    $stmt->close();
    header("Location: staff_dashboard.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }
        .dashboard-section {
            margin: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        .dashboard-section h2 {
            margin-bottom: 30px;
            color: #333;
            font-size: 28px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .dashboard-card {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .dashboard-card h3 {
            margin-top: 0;
            color: #272727;
            font-size: 24px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .dashboard-card table {
            width: 100%;
            border-collapse: collapse;
        }
        .dashboard-card table th, .dashboard-card table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }
        .dashboard-card table th {
            background-color: #ff8c00;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
        }
        .dashboard-card table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .dashboard-card table tr:hover {
            background-color: #f1f1f1;
        }
        .dashboard-card form {
            margin-top: 10px;
            display: inline-block;
            width: 100%;
        }
        .dashboard-card form select, .dashboard-card form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }
        .dashboard-card form button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 14px;
            color: #fff;
        }
        .dashboard-card form button.update-btn {
            background-color: #28a745; /* Primary Button */
        }
        .dashboard-card form button.respond-btn {
            background-color: #28a745; /* Success Button */
        }
        .dashboard-card form button.update-btn:hover {
            background-color: #218838; /* Darker shade for hover */
        }
        .dashboard-card form button.respond-btn:hover {
            background-color: #218838; /* Darker shade for hover */
        }
    </style>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <section class="dashboard-section">
        <h2>Staff Dashboard</h2>
        
        <!-- Reservation Details -->
<div class="dashboard-card">
    <h3>Reservation Details</h3>
    <table>
        <thead>
            <tr>
                <th>Reservation ID</th>
                <th>Customer Name</th>
                <th>Service Type</th>
                <th>Location</th>
                <th>Date</th>
                <th>Time</th>
                <th>Table Number</th> 
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($reservation = $reservations_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $reservation['reservation_id']; ?></td>
                    <td><?php echo $reservation['name']; ?></td>
                    <td><?php echo $reservation['service_type']; ?></td>
                    <td><?php echo $reservation['location']; ?></td>
                    <td><?php echo $reservation['reservation_date']; ?></td>
                    <td><?php echo $reservation['reservation_time']; ?></td>
                    <td><?php echo $reservation['table_number']; ?></td>
                    <td><?php echo ucfirst($reservation['status']); ?></td>
                    <td>
                        <?php if ($reservation['status'] === 'canceled'): ?>
                            <span><?php echo ucfirst($reservation['status']); ?></span>
                        <?php else: ?>
                            <form action="staff_dashboard.php" method="post">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                                <select name="status" required>
                                    <option value="confirmed" <?php if ($reservation['status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                                    <option value="canceled" <?php if ($reservation['status'] == 'canceled') echo 'selected'; ?>>Canceled</option>
                                </select>
                                <button type="submit" name="update_reservation_status" class="update-btn">Update</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


        <!-- Customer Queries -->
        <div class="dashboard-card">
            <h3>Customer Queries</h3>
            <table>
                <thead>
                    <tr>
                        <th>Query ID</th>
                        <th>Customer Name</th>
                        <th>Question</th>
                        <th>Response</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while($faq = $faqs_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $faq['faq_id']; ?></td>
                            <td><?php echo $faq['name']; ?></td>
                            <td><?php echo $faq['question']; ?></td>
                            <td>
                                <form action="staff_dashboard.php" method="post">
                                    <input type="hidden" name="faq_id" value="<?php echo $faq['faq_id']; ?>">
                                    <textarea name="response" required><?php echo $faq['answer']; ?></textarea>
                                    <button type="submit" name="respond_query" class="respond-btn">Respond</button>
                                </form>
                            </td>
                            <td><?php echo $faq['status']; ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Payment Details -->
        <div class="dashboard-card">
            <h3>Payment Processing</h3>
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Customer Name</th>
                        <th>Items</th>
                        <th>Total Price</th>
                        <th>Delivery Location</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($payment = $payments_result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $payment['payment_id']; ?></td>
        <td><?php echo $payment['customer_name']; ?></td>
        <td>
            <ul>
                <?php 
                $item_names_string = $payment['items']; // Retrieve the stored item names string from the database
                if (!empty($item_names_string)): 
                    echo "<li>" . htmlspecialchars($item_names_string) . "</li>";
                else: 
                    ?>
                    <li>No items available</li>
                <?php endif; ?>
            </ul>
        </td>
        <td><?php echo $payment['total_price']; ?> LKR</td>
        <td><?php echo $payment['delivery_location']; ?></td>
        <td><?php echo $payment['phone_number']; ?></td>
        <td><?php echo ucfirst($payment['status']); ?></td>
    </tr>
<?php endwhile; ?>

                </tbody>
            </table>
        </div>

    </section>
</body>
</html>
