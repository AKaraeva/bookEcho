<?php
    include_once "header.php";
?>
<?php

// Check if the login form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Validate the entered credentials
    $errors = [];
    $loginError = "";

    if (empty($_POST["username"])) {
        $errors[] = "Please enter your username or email address";
    } 
    else{
        $uname = $_POST["username"];
        // check if e-mail address is well-formed
        //if (!filter_var($uname, FILTER_VALIDATE_EMAIL)) {
           // $errors[] = "Ungültige E-Mail Adresse";
        //}          
    }

    if (empty($_POST["pwd"])) {
        $errors[] = "Please enter your password";
    } else {
        $pwd = $_POST["pwd"];
    }

    if (empty($errors))
    {
        // connect to db
        require_once("config/dbaccess.php");
        $db_obj = new mysqli($host, $username, $password, $database); //connect to database

        if(!$db_obj){   
        // check connection to db

            die('Connection error: ' . mysqli_connect_error());
        }  
            
        // check user data with data in database
        $query = "SELECT * FROM user_profiles WHERE username ='$uname' OR email = '$uname'";

        $result = $db_obj->query($query);

        if ($result)
        {
            if ($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                if(password_verify($pwd, $row["password"])){
                    $_SESSION["user"] = $row['username'];
                    $_SESSION["firstname"] = $row['first_name'];
                    $_SESSION["lastname"] = $row['last_name'];
                    $_SESSION["user_id"] = $row['id'];
                    $_SESSION["email"] = $row['email'];
                    // Set a persistent session cookie with an extended expiration time
                    //setcookie("persistent_cookie", "true", time() + (7 * 24 * 60 * 60), "/");

                    // Redirect to the home page
                    header("Location: index.php");
                    exit();
                }
                else{
                    $loginError = "Wrong password";
                }
            }                      
            else{
                $loginError = "Wrong user name";
            } 
        }
    // disconnect from db
    $result -> free_result();
    $db_obj->close();
    }
}
?>
    <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <div class="form-signin">
            <label for="inputEmail" class="sr-only">E-Mail-Adresse</label>
            <input type="text" name="username" class="form-control" placeholder="Username / Email Address" required>
            <label for="inputPassword" class="sr-only">Passwort</label>
            <input type="password" name="pwd" class="form-control" placeholder="Password" required>
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" name="safeit" value="remember-me">Anmeldeinformationen speichern
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Login</button>
        </div>
    </form>
<?php
   include_once "footer.php";
?>