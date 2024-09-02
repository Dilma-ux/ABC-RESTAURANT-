<?php
session_start();
require 'db.php';
require_once('tcpdf/tcpdf.php');
$db = Database::getInstance();
$conn = $db->getConnection();

// Ensure only admins can access this page
if (!isset($_SESSION['position']) || strtolower($_SESSION['position']) !== 'admin') {
    header("Location: index.php");
    exit();
}

$report_type = $_GET['report_type'];

// Fetch data based on report type
switch ($report_type) {
    case 'reservation':
        $query = "SELECT * FROM reservations";
        break;

    case 'payment':
        $query = "SELECT * FROM payments";
        break;

    case 'query':
        $query = "SELECT * FROM faqs";
        break;

    case 'user_activity':
        $query = "SELECT * FROM user_activity";
        break;

    default:
        echo "Invalid report type.";
        exit();
}

$result = $conn->query($query);

// Handle download as PDF
if (isset($_POST['download_report'])) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('ABC Restaurant');
    $pdf->SetTitle(ucfirst($report_type) . ' Report');
    $pdf->SetSubject('Generated Report');
    $pdf->SetKeywords('TCPDF, PDF, report');

    // Set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

    // Set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Add a page
    $pdf->AddPage();

    // Set content for the PDF
    $html = '<h1>' . ucfirst($report_type) . ' Report</h1>';
    $html .= '<table border="1" cellpadding="4" cellspacing="0">';
    $html .= '<thead><tr>';

    if ($report_type == 'reservation') {
        $html .= '<th>Reservation ID</th><th>Customer ID</th><th>Service Type</th><th>Location</th><th>Date</th><th>Status</th>';
    } elseif ($report_type == 'payment') {
        $html .= '<th>Payment ID</th><th>Customer Name</th><th>Total Price</th><th>Status</th>';
    } elseif ($report_type == 'query') {
        $html .= '<th>Query ID</th><th>Customer Name</th><th>Question</th><th>Status</th>';
    } elseif ($report_type == 'user_activity') {
        $html .= '<th>User ID</th><th>User Name</th><th>Activity</th><th>Activity Date</th>';
    }

    $html .= '</tr></thead><tbody>';

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        if ($report_type == 'reservation') {
            $html .= '<td>' . $row['reservation_id'] . '</td>';
            $html .= '<td>' . $row['user_id'] . '</td>';
            $html .= '<td>' . $row['service_type'] . '</td>';
            $html .= '<td>' . $row['location'] . '</td>';
            $html .= '<td>' . $row['reservation_date'] . '</td>';
            $html .= '<td>' . $row['status'] . '</td>';
        } elseif ($report_type == 'payment') {
            $html .= '<td>' . $row['payment_id'] . '</td>';
            $html .= '<td>' . $row['customer_name'] . '</td>';
            $html .= '<td>' . $row['total_price'] . '</td>';
            $html .= '<td>' . $row['status'] . '</td>';
        } elseif ($report_type == 'query') {
            $html .= '<td>' . $row['faq_id'] . '</td>';
            $html .= '<td>' . $row['name'] . '</td>';
            $html .= '<td>' . $row['question'] . '</td>';
            $html .= '<td>' . $row['status'] . '</td>';
        } elseif ($report_type == 'user_activity') {
            $html .= '<td>' . $row['id'] . '</td>';
            $html .= '<td>' . $row['user_id'] . '</td>';
            $html .= '<td>' . $row['activity'] . '</td>';
            $html .= '<td>' . $row['activity_date'] . '</td>';
        }
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    // Print text using writeHTMLCell()
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output(ucfirst($report_type) . '_report.pdf', 'D');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Report - <?php echo ucfirst($report_type); ?></title>
    <link rel="stylesheet" href="report.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <section class="admin-dashboard">
        <div class="admin-section">
            <h3><?php echo ucfirst($report_type); ?> Report</h3>

            <div class="report-actions">
                <form method="post" style="display:inline;">
                    <button type="submit" name="download_report" class="download-btn">Download Report</button>
                </form>
                <a href="admin_dashboard.php" class="back-btn">Back</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <?php if ($report_type == 'reservation'): ?>
                            <th>Reservation ID</th>
                            <th>Customer ID</th>
                            <th>Service Type</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Status</th>
                        <?php elseif ($report_type == 'payment'): ?>
                            <th>Payment ID</th>
                            <th>Customer Name</th>
                            <th>Item Name</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        <?php elseif ($report_type == 'query'): ?>
                            <th>Query ID</th>
                            <th>Customer ID</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Status</th>
                        <?php elseif ($report_type == 'user_activity'): ?>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Activity</th>
                            <th>Activity Date</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <?php if ($report_type == 'reservation'): ?>
                                <td><?php echo $row['reservation_id']; ?></td>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['service_type']; ?></td>
                                <td><?php echo $row['location']; ?></td>
                                <td><?php echo $row['reservation_date']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                            <?php elseif ($report_type == 'payment'): ?>
                                <td><?php echo $row['payment_id']; ?></td>
                                <td><?php echo $row['customer_name']; ?></td>
                                <td><?php echo $row['items']; ?></td>
                                <td><?php echo $row['total_price']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                            <?php elseif ($report_type == 'query'): ?>
                                <td><?php echo $row['faq_id']; ?></td>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['question']; ?></td>
                                <td><?php echo $row['answer']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                            <?php elseif ($report_type == 'user_activity'): ?>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['activity']; ?></td>
                                <td><?php echo $row['activity_date']; ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
