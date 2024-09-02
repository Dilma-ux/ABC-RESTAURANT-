<?php
session_start();
require 'db.php';
$db = Database::getInstance();
$conn = $db->getConnection();

// Ensure only admins can access this page
$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['position']) || strtolower($_SESSION['position']) !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch data from the database
$services = $conn->query("SELECT * FROM services");
$facilities = $conn->query("SELECT * FROM facilities");
$menu_items = $conn->query("SELECT * FROM menu");
$users = $conn->query("SELECT * FROM users");
$gallery = $conn->query("SELECT * FROM gallery");

// Handle actions (edit, update, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle delete service
    if (isset($_POST['delete_service'])) {
        $id = $_POST['service_id'];
        $conn->query("DELETE FROM services WHERE id = $id");
        header("Location: admin_dashboard.php");
    }

    // Handle delete facility
    if (isset($_POST['delete_facility'])) {
        $id = $_POST['facility_id'];
        $conn->query("DELETE FROM facilities WHERE id = $id");
        header("Location: admin_dashboard.php");
    }

    // Handle delete menu item
    if (isset($_POST['delete_menu_item'])) {
        $id = $_POST['menu_item_id'];
        $conn->query("DELETE FROM menu WHERE menu_id = $id");
        header("Location: admin_dashboard.php");
    }

    // Handle delete user
    if (isset($_POST['delete_user'])) {
        $id = $_POST['user_id'];
        $conn->query("DELETE FROM users WHERE user_id = $id");
        header("Location: admin_dashboard.php");
    }

    // Handle delete gallery item
    if (isset($_POST['delete_gallery_item'])) {
        $id = $_POST['gallery_id'];
        $conn->query("DELETE FROM gallery WHERE id = $id");
        header("Location: admin_dashboard.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

   <style>

body {
    font-family: 'Poppins', sans-serif;
    background-color: #f5f7fa;
    margin: 0;
    padding: 0;
    color: #333;
}

.admin-dashboard {
    display: flex;
    min-height: 100vh;
    margin-left: -22%;
}

.sidebar {
    width: 240px;
    background-color: #ffffff;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: fixed;
    height: 100%;
    border-radius: 10px;
    margin-top: 100px; /* Increased margin-top */
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
    margin-top: 28%;
}

.sidebar ul li {
    margin-bottom: 20px;
}

.sidebar ul li a {
    text-decoration: none;
    font-size: 1rem;
    color: #333;
    display: flex;
    align-items: center;
    padding: 10px 15px;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

.sidebar ul li a i {
    margin-right: 10px;
}

.sidebar ul li a:hover,
.sidebar ul li a.active {
    background-color: #ff5630;
    color: #fff;
}

.main-content {
    flex-grow: 1;
    margin-left: 260px; /* Keeps the content aligned after decreasing sidebar margin-left */
    padding: 20px;
    margin-top: 100px; /* Increased margin-top */
    background-color: #f5f7fa;
}

.table-header {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-bottom: 20px;
}

.admin-section {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 25px;
    margin-bottom: 30px;
}

.admin-section h3 {
    margin-bottom: 20px;
    color: #333;
    font-weight: 600;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
    font-size: 1.5rem;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1rem;
}

table thead th {
    text-align: left;
    background-color: #f0f0f0;
    padding: 15px;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #e6e6e6;
}

table tbody td {
    padding: 15px;
    border-bottom: 1px solid #e6e6e6;
    text-align: left;
}

table tbody tr:hover {
    background-color: #f9f9f9;
}

table tbody td img {
    width: 80px;
    height: auto;
    border-radius: 8px;
    object-fit: cover;
}

.actions {
    display: flex;
    gap: 10px;
}

.actions a,
.actions button {
    background-color: #ff5630;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.actions a:hover,
.actions button:hover {
    background-color: #e04a26;
}

.actions .delete-btn {
    background-color: #dc3545;
}

.actions .delete-btn:hover {
    background-color: #c82333;
}

form {
    display: inline;
}

.hidden {
    display: none;
}
.update-btn {
    background-color: #28a745;
    color: #fff;
    padding: 8px 12px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 15px;        
}

.update-btn:hover {
    background-color: #218838;
}

.actions {
    display: flex;
    gap: 10px;
}


/* Report Section Styles */

.report-options {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 40px;
    width: 80%; /* Adjusted width to use more space */
    max-width: 800px; /* Limit max-width for large screens */
    margin: 0 auto;
    height: auto; /* Adjusted height to fit content */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}


.report-options h3 {
    font-size: 32px;
    margin-bottom: 30px;
    text-align: center;
    color: #333;
}

.report-options label {
    display: block;
    font-size: 16px;
    margin-bottom: 10px;
    color: #666;
    text-align: left;
}

.report-options select {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
}

.report-options .generate-btn {
    width: 100%;
    padding: 15px;
    background-color: #ff5630;
    color: #fff;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

.report-options .generate-btn:hover {
    background-color: #e04a26;
}

/* Adjust for small screens */
@media (max-width: 600px) {
    .report-options {
        width: 90%;
        padding: 20px;
        box-shadow: none;
        margin: 20px auto;
    }
}



</style>

</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <section class="admin-dashboard">
        <h2>Welcome to the Admin Dashboard</h2>
        <div class="admindashboard">
            <!-- Sidebar -->
            <aside class="sidebar">
                <ul>
                    <li><a href="#" class="nav-link" data-section="services-section"><i class="fas fa-concierge-bell"></i> Manage Services</a></li>
                    <li><a href="#" class="nav-link" data-section="menu-section"><i class="fas fa-utensils"></i> Manage Menu</a></li>
                    <li><a href="#" class="nav-link" data-section="facilities-section"><i class="fas fa-building"></i> Manage Facilities</a></li>
                    <li><a href="#" class="nav-link" data-section="gallery-section"><i class="fas fa-image"></i> Manage Gallery</a></li>
                    <li><a href="#" class="nav-link" data-section="users-section"><i class="fas fa-users"></i> Manage Users</a></li>
                    <li><a href="#" class="nav-link" data-section="report-section"><i class="fas fa-file-alt"></i> Generate Report</a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <section class="main-content">

                <!-- Admin-Section: Manage Services -->
                <div id="services-section" class="admin-section hidden">
                    <h3>Manage Services</h3>
                    <a href="add_service.php" class="update-btn">Add New Service</a>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service Name</th>
                                <th>Description</th>
                                <th>Icon</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($service = $services->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $service['service_id']; ?></td>
                                    <td><?php echo $service['name']; ?></td>
                                    <td><?php echo $service['description']; ?></td>
                                    <td><?php echo $service['icon']; ?></td>
                                    <td class="actions">
                                        <a href="edit_service.php?id=<?php echo $service['service_id']; ?>">Edit</a> |
                                        <form action="admin_dashboard.php" method="post">
                                            <input type="hidden" name="service_id" value="<?php echo $service['service_id']; ?>">
                                            <button type="submit" name="delete_service" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Admin-Section: Manage Menu -->
                <div id="menu-section" class="admin-section hidden">
                    <h3>Manage Menu</h3>
                    <a href="add_menu.php" class="update-btn">Add New Menu Item</a>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Menu Item Name</th>
                                <th>Image Path</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Special Offers</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($menu_item = $menu_items->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $menu_item['menu_id']; ?></td>
                                    <td><?php echo $menu_item['name']; ?></td>
                                    <td><img src="<?php echo htmlspecialchars($menu_item['image_path']); ?>"></td>
                                    <td><?php echo $menu_item['description']; ?></td>
                                    <td><?php echo $menu_item['price']; ?></td>
                                    <td><?php echo $menu_item['category']; ?></td>
                                    <td><?php echo $menu_item['special_offer']; ?></td>
                                    <td class="actions">
                                        <a href="edit_menu.php?id=<?php echo $menu_item['menu_id']; ?>">Edit</a> |
                                        <form action="admin_dashboard.php" method="post">
                                            <input type="hidden" name="menu_item_id" value="<?php echo $menu_item['menu_id']; ?>">
                                            <button type="submit" name="delete_menu_item" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Admin-Section: Manage Facilities -->
                <div id="facilities-section" class="admin-section hidden">
                    <h3>Manage Facilities</h3>
                    <a href="add_facility.php" class="update-btn">Add New Facility</a>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Facility Name</th>
                                <th>Description</th>
                                <th>Icon</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($facility = $facilities->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $facility['facility_id']; ?></td>
                                    <td><?php echo $facility['name']; ?></td>
                                    <td><?php echo $facility['description']; ?></td>
                                    <td><?php echo $facility['icon']; ?></td>
                                    <td class="actions">
                                        <a href="edit_facility.php?id=<?php echo $facility['facility_id']; ?>">Edit</a> |
                                        <form action="admin_dashboard.php" method="post">
                                            <input type="hidden" name="facility_id" value="<?php echo $facility['facility_id']; ?>">
                                            <button type="submit" name="delete_facility" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Admin-Section: Manage Users -->
                <div id="users-section" class="admin-section hidden">
                    <h3>Manage Users</h3>
                    <a href="add_user.php" class="update-btn">Add New User</a>
                    <table>
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $user['user_id']; ?></td>
                                    <td><?php echo $user['name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['position']; ?></td>
                                    <td class="actions">
                                        <a href="edit_user.php?id=<?php echo $user['user_id']; ?>">Edit</a> |
                                        <form action="admin_dashboard.php" method="post">
                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                            <button type="submit" name="delete_user" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Admin-Section: Manage Gallery -->
                <div id="gallery-section" class="admin-section hidden">
                    <h3>Manage Gallery</h3>
                    <a href="add_gallery_item.php" class="update-btn">Add New Gallery Item</a>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image Path</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($gallery_item = $gallery->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $gallery_item['id']; ?></td>
                                    <td><img src="<?php echo htmlspecialchars($gallery_item['image_path']); ?>"></td>
                                    <td><?php echo $gallery_item['name']; ?></td>
                                    <td class="actions">
                                        <a href="edit_gallery_item.php?id=<?php echo $gallery_item['id']; ?>">Edit</a> |
                                        <form action="admin_dashboard.php" method="post">
                                            <input type="hidden" name="gallery_id" value="<?php echo $gallery_item['id']; ?>">
                                            <button type="submit" name="delete_gallery_item" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Admin-Section: Generate Reports -->
                <div id="report-section" class="admin-section hidden">
                    <h3>Generate Reports</h3>
                    <div class="report-options">
                        <form action="generate_report.php" method="get">
                            <div class="report-type">
                                <label for="report_type">Select Report Type:</label>
                                <select name="report_type" id="report_type" required>
                                    <option value="reservation">Reservation Report</option>
                                    <option value="payment">Payment Report</option>
                                    <option value="query">Query Report</option>
                                    <option value="user_activity">User Activity Report</option>
                                </select>
                            </div>
                            <div class="report-period">
                                <label for="report_period">Select Period:</label>
                                <select name="report_period" id="report_period" required>
                                    <option value="daily">Daily</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                            <button type="submit" class="generate-btn">Generate Report</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('.admin-section');

            navLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();

                    const targetSection = document.getElementById(this.dataset.section);

                    sections.forEach(section => {
                        section.classList.add('hidden');
                    });

                    targetSection.classList.remove('hidden');

                    navLinks.forEach(link => link.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Display the first section by default
            document.querySelector('.nav-link').click();
        });
    </script>

</body>
</html>
