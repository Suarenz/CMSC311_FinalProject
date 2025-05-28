<?php

include __DIR__ . '/functions.php';

show_header();
display_navbar();
display_sidebar();
set_config_inc();

require(MYSQL);

$userLevel = $_SESSION['user_level'];
?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Dashboard</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=<?php echo $userLevel == 1 ? 'admin_dashboard.php' : 'user_dashboard.php'; ?>>Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Search</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Search Account</h5>

                                <div class="container mt-5">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="search_query" class="font-weight-bold text-dark">Search:</label>
                                                <input type="text" class="form-control" id="search_query" name="search_query" onkeyup="fetchSearchResults()">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Container for the search results -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="search_results"></div>
                                        </div>
                                    </div>

                                    <noscript>
                                        <form action="" method="GET">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-fw">Search</button>
                                            </div>
                                        </form>
                                    </noscript>

                                    <?php
                                if (isset($_GET['search_query'])) {
                                    $search_query = mysqli_real_escape_string($dbc, $_GET['search_query']);

                                    $whereClause = "
                                        first_name LIKE '%$search_query%' 
                                        OR last_name LIKE '%$search_query%' 
                                        OR middle_name LIKE '%$search_query%' 
                                        OR alias LIKE '%$search_query%' 
                                        OR case_number LIKE '%$search_query%'
                                    ";

                                    $query = "SELECT * FROM clients WHERE $whereClause";
                                    $search_result = mysqli_query($dbc, $query);

                                        if (mysqli_num_rows($search_result) > 0) {
                                            echo '<br /><br /><h5 class="text-primary">Search Results:</h5>';
                                            echo '<div class="table-responsive-md">';
                                            echo '<table class="table table-hover table-bordered table-md">';
                                            echo '<thead class="table-dark">';
                                            echo '<tr>';
                                            echo '<th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Suffix</th><th>Alias</th><th>Case Number</th><th>Status</th><th>Action</th>';
                                            echo '</tr>';
                                            echo '</thead><tbody>';

                                            while ($row = mysqli_fetch_assoc($search_result)) {
                                                echo '<tr>';
                                                echo '<td>' . ($userLevel == 0 ? hideName(ucfirst($row['first_name'])) : ucfirst($row['first_name'])) . '</td>';
                                                echo '<td>' . ($userLevel == 0 ? hideName(ucfirst($row['middle_name'])) : ucfirst($row['middle_name'])) . '</td>';
                                                echo '<td>' . ($userLevel == 0 ? hideName(ucfirst($row['last_name'])) : ucfirst($row['last_name'])) . '</td>';
                                                echo '<td>' . ($userLevel == 0 ? hideName(ucfirst($row['suffix'])) : ucfirst($row['suffix'])) . '</td>';
                                                echo '<td>' . ucfirst($row['alias']) . '</td>';
                                                echo '<td class="font-weight-bold">' . ($userLevel == 0 ? hideName(ucfirst($row['case_number'])) : ucfirst($row['case_number'])) . '</td>';

                                                // Status
                                                $statusClass = match($row['status']) {
                                                    'grant' => 'success',
                                                    'pending' => 'warning',
                                                    'denied' => 'danger',
                                                    default => 'secondary',
                                                };
                                                echo '<td><label class="badge badge-' . $statusClass . '">' . $row['status'] . '</label></td>';

                                                // Actions
                                                echo "<td>
                                                    <div class='dropdown'>
                                                        <button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> Action </button>
                                                        <div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuButton1'>
                                                            <h6 class='dropdown-header'>Options</h6>
                                                            <a class='dropdown-item text-dark' href='view_details.php?id={$row['id']}'>View Details</a>
                                                        </div>
                                                    </div>
                                                </td>";
                                                echo '</tr>';
                                            }
                                            echo '</tbody></table></div>';
                                        } else {
                                            echo '<blockquote class="blockquote blockquote-primary"><p class="text-dark">No results found.</p></blockquote>';
                                        }
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->

        <script>
            function fetchSearchResults() {
                const query = document.getElementById('search_query').value;
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'ajax_search.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById('search_results').innerHTML = xhr.responseText;
                    }
                };

                xhr.send('search_query=' + encodeURIComponent(query));
            }
        </script>

<?php show_footer(); ?>
        
