<?php
session_start();
?>

<header>
    <div class="navbar">
        <div class="logo">
            <h1>ABC <span>Restaurant</span></h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="menu.php" class="<?php echo $current_page == 'menu.php' ? 'active' : ''; ?>">Menu</a></li>
                <li><a href="services.php" class="<?php echo $current_page == 'services.php' ? 'active' : ''; ?>">Services</a></li>
                <li><a href="contact.php" class="<?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">Contact Us</a></li>
                <?php if (isset($_SESSION['position']) && (strtolower($_SESSION['position']) === 'staff' || strtolower($_SESSION['position']) === 'admin')): ?>
                    <li><a href="staff_dashboard.php" class="<?php echo ($current_page == 'staff_dashboard.php') ? 'active' : ''; ?>">Staff Dashboard</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['position']) && strtolower($_SESSION['position']) === 'admin'): ?>
                    <li><a href="admin_dashboard.php" class="<?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>">Admin Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="auth-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a> | 
                <span style="color: #ff8c00; font-weight: bold;">Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest'; ?></span>
            <?php else: ?>
                <a href="login.php">Login</a> 
            <?php endif; ?>
        </div>
    </div>
</header>
