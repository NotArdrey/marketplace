<?php
require "../config/dbconn.php";

$sql = "SELECT * FROM users WHERE user_type = 'user' ORDER BY userID ASC";
            
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo '<table class="orders-table" id="orders-table">';
    echo '<thead>
            <tr id="orders-tr">
                <th>User ID</th>
                <th>User Type</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Creation Date</th>
                <th>Verification Status</th>
                <th></th>
            </tr>
          </thead>';
    echo '<tbody>';
    foreach ($result as $row) {
        $userID = $row['userID'];

        echo '<tr class="orders-tr">';
        echo '<td class="">' . $userID . '</td>'; 
        echo '<td class="">' . $row['user_type'] . '</td>'; 
        echo '<td class="">' . $row['first_name'] . '</td>'; 
        echo '<td class="">' . $row['last_name'] . '</td>'; 
        echo '<td class="">' . $row['email'] . '</td>'; 
        echo '<td class="">' . $row['contact_number'] . '</td>'; 
        echo '<td class="">' . $row['created_at'] . '</td>'; 
        echo '<td class="">' . $row['verify_status'] . '</td>'; 
        echo '<td class=""><i class="fa-solid fa-trash" data-user-id="' . $userID . '"></i></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>No users found.</p>';
}
?>