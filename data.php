<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'conf/sqlinfo.inc.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$cname = $_POST['cname'];
$phone = $_POST['phone'];
$unumber = $_POST['unumber'];
$snumber = $_POST['snumber'];
$stname = $_POST['stname'];
$sbname = $_POST['sbname'];
$dsbname = $_POST['dsbname'];
$date = $_POST['date'];
$time = $_POST['time'];

// Generate booking reference number
$sql = "SELECT MAX(id) AS max_booking_id FROM requests";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$max_booking_id = $row['max_booking_id'];
$new_booking_id = $max_booking_id + 1;
$booking_reference_number = 'BRN' . str_pad($new_booking_id, 5, '0', STR_PAD_LEFT);

// Prepare and execute the INSERT statement
$stmt = $conn->prepare("INSERT INTO requests (booking_ref_number, customer_name, phone_number, unit_number, street_number, street_name, suburb, destination_suburb, pick_up_date, pick_up_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$status = "unassigned";
$stmt->bind_param("sssssssssss", $booking_reference_number, $cname, $phone, $unumber, $snumber, $stname, $sbname, $dsbname, $date, $time, $status);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Format the pickup time as 24-hour format (HH:MM)
    $formatted_time = date('H:i', strtotime($time));

    // Format the pickup date as DD/MM/YYYY
    $formatted_date = date('d/m/Y', strtotime($date));

    // Construct the confirmation message
    $confirmation_message = "Booking request submitted successfully. Booking Reference Number: $booking_reference_number, Pickup Time: $formatted_time, Pickup Date: $formatted_date";

    echo $confirmation_message;
} else {
    echo "Error submitting booking request.";
}

$stmt->close();
mysqli_close($conn);
?>
