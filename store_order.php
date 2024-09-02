<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON received from the Ajax request
    $selected_items = json_decode(file_get_contents('php://input'), true);
    $_SESSION['selected_items'] = $selected_items;

    // Optionally, you could also calculate and store the total price in the session
    $total_price = 0;
    foreach ($selected_items as $item) {
        $total_price += $item['price'];
    }
    $_SESSION['total_price'] = $total_price;

    echo json_encode(['status' => 'success']);
}
?>
