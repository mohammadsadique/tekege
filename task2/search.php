<?php
    require('db.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['data'])) {
            $filenameToSearch = $_POST['data'];

            $sql = "SELECT * FROM files WHERE file_name LIKE '%" . $filenameToSearch . "%' OR content LIKE '%" . $filenameToSearch . "%'";
            $result = $conn->query($sql);
            if($result->num_rows > 0) {
                $table = '
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                        while ($row = $result->fetch_assoc()) {
                            $table .= "
                                <tr>
                                    <td>" . $row['file_name'] . "</td>
                                    <td><a href='uploads/" . $row['file_name'] . "' download>Download</a></td>
                                </tr>                        
                            ";
                        }
                        $table .= 
                        "</tbody>
                    </table>";
                $response = array('status' => 1, 'message' => 'Data received and processed successfully' , 'data' => $table);
                echo json_encode($response);
            } else {
                $response = array('status' => 0, 'message' => 'No files found with the specified filename or content.');
                echo json_encode($response);
            }
        } else {
            $response = array('status' => 0, 'message' => 'No data received');
            echo json_encode($response);
        }
    } else {
        $response = array('status' => 0, 'message' => 'Invalid request method');
        echo json_encode($response);
    }