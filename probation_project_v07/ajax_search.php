<?php
session_start();
include __DIR__ . '/functions.php';
set_config_inc();
require(MYSQL);

$userLevel = $_SESSION['user_level'] ?? '';

// Only proceed if there's a search query
if (!empty($_POST['search_query'])) {
    $search_query = mysqli_real_escape_string($dbc, $_POST['search_query']);

    $query = "
        SELECT * FROM clients 
        WHERE first_name LIKE '%$search_query%' 
        OR last_name LIKE '%$search_query%' 
        OR middle_name LIKE '%$search_query%' 
        OR alias LIKE '%$search_query%' 
        OR case_number LIKE '%$search_query%'
    ";

    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo '<table class="table table-hover table-bordered">';
        echo '<thead><tr>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Alias</th>
                <th>Case Number</th>
              </tr></thead><tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['middle_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['alias']) . '</td>';
            echo '<td>' . htmlspecialchars($row['case_number']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p class="text-danger">No results found.</p>';
    }
} else {
    // Optional: don't output anything if the input is empty
    echo ''; 
}
?>
