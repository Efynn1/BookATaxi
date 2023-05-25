<?php
// Database connection code here

$searchValue = $_POST['bsearch'];

// Perform input validation if the search value is not empty
if (!empty($searchValue)) {
    $referenceNumberPattern = "/^BRN\d{5}$/";
    if (!preg_match($referenceNumberPattern, $searchValue)) {
        echo 'Invalid reference number format';
        exit;
    }

    // Perform a query to find a record with the matching reference number
    $stmt = $conn->prepare("SELECT * FROM requests WHERE booking_ref_number = ?");
    $stmt->bind_param("s", $searchValue);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Display the search result in a table
        echo '<div class="content">';
        echo '<table>';
        echo '<tr>';
        echo '<th>Booking reference number</th>';
        echo '<th>Customer name</th>';
        echo '<th>Phone</th>';
        echo '<th>Pickup suburb</th>';
        echo '<th>Destination suburb</th>';
        echo '<th>Pickup date and time</th>';
        echo '<th>Status</th>';
        echo '<th>Assign</th>';
        echo '</tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['booking_ref_number'] . '</td>';
            echo '<td>' . $row['customer_name'] . '</td>';
            echo '<td>' . $row['phone_number'] . '</td>';
            echo '<td>' . $row['pickup_suburb'] . '</td>';
            echo '<td>' . $row['destination_suburb'] . '</td>';
            echo '<td>' . $row['pick_up_date'] . ' ' . $row['pick_up_time'] . '</td>';
            echo '<td>' . $row['status'] . '</td>';
            echo '<td><button onclick="assignBooking(\'' . $row['booking_ref_number'] . '\')">Assign</button></td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';
    } else {
        echo 'No matching booking records found';
    }

    $stmt->close();
} else {
    // Perform a query to find unassigned bookings within 2 hours from the current time
    $currentTime = date("Y-m-d H:i:s");
    $twoHoursAhead = date("Y-m-d H:i:s", strtotime('+2 hours'));

    $stmt = $conn->prepare("SELECT * FROM requests WHERE status = 'unassigned' AND CONCAT(pick_up_date, ' ', pick_up_time) BETWEEN ? AND ?");
    $stmt->bind_param("ss", $currentTime, $twoHoursAhead);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Display the search result in a table
        echo '<div class="content">';
        echo '<table>';
        echo '<tr>';
        echo '<th>Booking reference number</th>';
        echo '<th>Customer name</th>';
        echo '<th>Phone</th>';
        echo '<th>Pickup suburb</th>';
        echo '<th>Destination suburb</th>';
        echo '<th>Pickup date and time</th>';
        echo '<th>Status</th>';
        echo '<th>Assign</th>';
        echo '</tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['booking_ref_number'] . '</td>';
            echo '<td>' . $row['customer_name'] . '</td>';
            echo '<td>' . $row['phone_number'] . '</td>';
            echo '<td>' . $row['pickup_suburb'] . '</td>';
            echo '<td>' . $row['destination_suburb'] . '</td>';
            echo '<td>' . $row['pick_up_date'] . ' ' . $row['pick_up_time'] . '</td>';
            echo '<td>' . $row['status'] . '</td>';
            echo '<td><button onclick="assignBooking(\'' . $row['booking_ref_number'] . '\')">Assign</button></td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';
    } else {
        echo 'No unassigned booking records found within 2 hours from the current time';
    }

    $stmt->close();
}

// Database disconnection code here
?>
