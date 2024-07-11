<?php
    include_once "header.php";

    //checks if the user is not authenticated (not logged in)
    if (!isset($_SESSION["user"])) {
        //redirects to login page
            header("Location: login.php");
      }
?>
    
    <?php

    require_once "config/dbaccess.php";

    // Connects to database
    $conn = new mysqli($host, $username, $password, $database);

    // Checks the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

   //just logged in users allowed to see user profile
    if (isset($_SESSION["user_id"]))
    {
        $user_id = $_SESSION["user_id"];

         // Fetches user data from the database based on the user's ID
        $sql = "SELECT * FROM user_profiles WHERE id = $user_id";
        $result = $conn->query($sql);

       if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row["username"];
        $email = $row["email"];
        $full_name = $row["first_name"]. " ". $row["last_name"];

       
        echo "<div class = user_profile>";
        echo "<h2><strong>Profile</strong></h2><br>";
        echo "<p>Username: $username</p>";
        echo "<p>Email: $email</p>";
        echo "<p>Full Name: $full_name</p>";
        echo '<button class="btn btn-primary" onclick="window.location.href = \'edit_user_profile.php\'">Edit Profile</button>';

        //echo '<button onclick="window.location.href = \'edit_user_profile.php\'">Edit Profile</button>';
        echo "</div>";
    } 

    }

    else {
        header("Location: login.php");
    }

    // Close the database connection
    $conn->close();
    ?>
</body>
</html>

