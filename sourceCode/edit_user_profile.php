<?php

require_once("header.php");

 //checks if the user is not authenticated (not logged in)
 if (!isset($_SESSION["user"])) {
    //redirects to login page
        header("Location: login.php");
    }


require_once ("config/dbaccess.php");

$conn = new mysqli($host, $username, $password, $database);

// check connection
if ($conn->connect_error) {
    die("Connection to database failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM user_profiles WHERE id = $_SESSION[user_id]";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $user_data = $result->fetch_assoc();
}

// update user data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_firstname = $_POST["new_firstname"];
    $new_lastname = $_POST["new_lastname"];
    $new_email = $_POST["new_email"];
    $new_username= $_POST["new_username"];

    $update_sql = "UPDATE user_profiles SET first_name  = '$new_firstname', last_name = '$new_lastname', email ='$new_email',  username = '$new_username' WHERE id = $user_id";

    if ($conn->query($update_sql) === FALSE) {
        
        echo "Update failed:  " . $conn->error;
    }
    else
    {
        header("Location: user_profile.php");
    }
        
}

$conn->close();
?>

<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<h2>Edit profile</h2>
    <div class="form-group">
        <label for="new_firstname">Firstname:</label>
        <input type="text" class="form-control" name="new_firstname" value="<?php echo $_SESSION["firstname"]; ?>" required>
    </div>

    <div class="form-group">
        <label for="new_lastname">Lastname:</label>
        <input type="text" class="form-control" name="new_lastname" value="<?php echo $_SESSION["lastname"]; ?>" required>
    </div>

    <div class="form-group">
        <label for="new_email">Email:</label>
        <input type="text" class="form-control" name="new_email" value="<?php echo $_SESSION["email"]; ?>">
    </div>

    <div class="form-group">
        <label for="new_username">Username:</label>
        <input type="text" class="form-control" name="new_username" value="<?php echo $_SESSION["user"]; ?>">
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top: 10px">Update Profile</button>

</form>

<?php
require_once("footer.php");

