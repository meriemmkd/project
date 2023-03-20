<?php

session_start(); 
if(!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) { 
  header("Location: ../index.php"); 
  exit; 
} 
 
$user_type = $_SESSION["user_role"]; 
if($user_type != "secretary") { 
  header("Location: ../error.php"); 
  exit; 
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard </title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="Secretarie.css">
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                          
                        </span>
                        <img src="../imgs/UniLogo.png" style="width: 40px; height: 45px; margin-top: 7px; margin-left : 11px ;" />

                        <span class="title">Ghardaia University</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Users</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="chatbubble-outline"></ion-icon>
                        </span>
                        <span class="title">Messages</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="settings-outline"></ion-icon>
                        </span>
                        <span class="title">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

               
                <div class="user">
                    <img src="./imgs/secretarie-avatar.png" alt="">
                </div>
            </div>

            <!-- ======================= Cards ================== -->
         

            <!-- ================ Order Details List ================= -->
            <div class="details">
                <div class="recentRequests">
                    <div class="cardHeader">
                        <h2>Currentt requests</h2>
                    
                    </div>

                    <table>
                        <thead>
                            <tr>

                                <td>Name</td>
                                <td>DocumentType</td>
                                <td></td>
                                <td>.......</td>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                                require '../dbconfig/config.php';

                                $sql = "SELECT users.full_name AS user_name, requests.document_title, requests.user_role, requests.id
                                        FROM requests
                                        INNER JOIN users ON requests.user_id = users.id
                                        WHERE requests.status = 'pending'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $user_name = $row['user_name'];
                                        $document_title = $row['document_title'];
                                        $user_role = $row['user_role'];
                                        $request_id = $row['id'];

                                        // Display the information for each row
                                        echo "<tr>";
                                        echo "<td>$user_name</td>";
                                        echo "<td>$document_title</td>";
                                        echo "<td><button id= 'show-btn' onclick='show()' style = '  font-size: 10px;
                                        padding: 8px; font-weight : 700;'>show</button>";
                                        
                                        echo "</td>";
                                        echo "<td><button id='hide-btn 'onclick='hide()' button   style = '  font-size: 10px;
                                        padding: 8px; font-weight : 700;'>hide</button  >";
                                        echo "</td>";
                                        echo '<script >
                                        const = divContent = document.querySelector(#content);
                                        function show() {
                                            divContent.style.backgroundColor = "red" ;A
                                        }
                                        </script>';
                                        
                                        echo "<td style ='display: none;'>$user_role</td>";
                                        echo "<td>";
                                        echo "<form id='content' action='upload.php' method='post' enctype='multipart/form-data'  style ='width: 400px;
                                        height: 0px;
                                    
                                        color: white;
                                        background-color: #222;
                                        padding: 30px; '>";
                                        echo "<input type='hidden' name='request_id' value='$request_id'>";
                                        echo "<input type='file' name='document' id='document'>";
                                        echo "<input type='submit' name='upload' value='Upload'>";
                                        echo "</form>";
                                        echo "</td>";
                                        
                                        echo '<script src="../javascript/main.js"></script>';
                                        echo "<td>";
                                        echo "<form action='deny.php' method='post'>";
                                        echo "<input type='hidden' name='request_id' value='$request_id'>";
                                        echo "<input type='submit' name='deny' value='Deny'>";
                                        echo "</form>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>

                        </tbody>
                    </table>
                </div>

                
                <div class="recentNotifications">
                    <div class="cardHeader">
                        <h2>Recent requests</h2>
                    </div>
                    <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Document Title</th>
                    <th>User Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Define an array of all valid status types
                $statusTypes = array('delivered', 'denied');

                // Loop through each status type and output the corresponding requests
                foreach ($statusTypes as $status) {
                    // Execute database query to retrieve data from 'requests' and 'users' tables
                    $sql = "SELECT users.full_name, requests.document_title, requests.user_role, requests.status
                            FROM requests
                            INNER JOIN users
                            ON requests.user_id = users.id
                            WHERE requests.status = '$status'";
                    $result = $conn->query($sql);

                    // Check for errors in query execution
                    if ($result->num_rows > 0) {
                        // Output section header for the current status type
                        echo "<tr font-weight: bold; >
                        <td colspan='4' class='statusHeader'>" . ucfirst($status) . " Requests</td>
                        </tr>";
                        
                        // Output data of each row for the current status type
                        while($row = $result->fetch_assoc()) {
                            echo "<tr >
                            <td>" . $row["full_name"] . "</td>
                            <td>" . $row["document_title"] . "</td>
                            <td>" . $row["user_role"] . "</td>
                            <td  >" . ucfirst($status) . "</td>
                            </tr>";
                        }
                    }
                }
                ?>
            </tbody>
        </table>

                   
                </div>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="../javascript/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>