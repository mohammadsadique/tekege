<?php
    require('db.php');
    // Start time
    $startTime = microtime(true);

    $rowsPerPage = 10;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $startRow = ($page - 1) * $rowsPerPage;

    $sql = "SELECT customer_id, order_title, order_date, order_price
            FROM orders
            WHERE order_date > '2020-01-01'
            ORDER BY customer_id
            LIMIT $startRow, $rowsPerPage";
            
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
        $table = '
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                ';
                while ($row = $result->fetch_assoc()) {
                    $table .= "
                        <tr>
                            <td>" . $row['order_title'] . "</td>
                            <td>" . $row['order_price'] . "</td>
                            <td>" . $row['order_date'] . "</td>
                        </tr>                        
                    ";
                }
                $table .= 
                "</tbody>
            </table>";
    }
   

    // Fetch total number of rows for pagination
    $total_pages_sql = "SELECT COUNT(*) FROM orders  WHERE order_date > '2020-01-01' ORDER BY customer_id";
    $res = mysqli_query($conn,$total_pages_sql);
    $total_rows = mysqli_fetch_array($res)[0];
    $totalPages = ceil($total_rows / $rowsPerPage);

    /** Number of pagination links to display */
    $visiblePageLinks = 5;
    $startPage = max(1, $page - floor($visiblePageLinks / 2));
    $endPage = min($totalPages, $startPage + $visiblePageLinks - 1);


    // End time
    $endTime = microtime(true);
    // Calculate time taken
    $timeTaken = ($endTime - $startTime) * 1000; // Convert to milliseconds
?>
<!DOCTYPE html>
<html>
<head>
    <title>PDF Upload</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add these lines to the <head> section of your HTML -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12" id="tbl">
                <h3>
                    <?php
                        echo "Time taken to fetch data: " . $timeTaken . " ms";
                    ?>
                </h3>
               
                <?php
                    echo $table; 

                    // Display pagination links
                    echo '<ul class="pagination">';
                    if ($startPage > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                        if ($startPage > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    for ($i = $startPage; $i <= $endPage; $i++) {
                        echo '<li class="page-item';
                        if ($i === $page) {
                            echo ' active';
                        }
                        echo '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                    }

                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">' . $totalPages . '</a></li>';
                    }
                    echo '</ul>';


                ?>
            </div>
        </div>
    </div>

</body>
</html>
