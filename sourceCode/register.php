<?php 
  include_once "header.php";
?>
    <script>
        $(function() {
            // Initialize the datepicker widget
            $("#datepicker").datepicker({
                dateFormat: "yy-mm-dd", // Format for the date
                changeMonth: true,
                changeYear: true,
                yearRange: "1900:2023" // Specify the range of selectable years
            });
        });
    </script>

  <?php  
  $errors = [];
  $uname= $firstname = $lastname = $email = $pwd1 = $pwd2 = "";

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if($_SERVER["REQUEST_METHOD"] === "POST") {   
          if (!empty($_POST["uname"])) {
              $uname = test_input($_POST["uname"]);
          } 

          if (!empty($_POST["pwd1"]))
          {
            $pwd1 = test_input($_POST["pwd1"]);   
          }

          if (!empty($_POST["pwd2"])) {
            $pwd2 = test_input($_POST["pwd2"]);   
          }

          if (!empty($_POST["email"])) {
           // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $emailErr = "not valid email";
            }
            $email = test_input($_POST["email"]);
          }
        
          if (!empty($_POST["firstname"])) {
            $firstname = test_input($_POST["firstname"]);         
          }

          if (!empty($_POST["lastname"])) {
            $lastname = test_input($_POST["lastname"]);         
          }  

          if ($pwd1 !== $pwd2)
          {
            $pwdErr = "Passwords do not match";
          }

          //$birthday = $_POST["birthdate"];
          }
    
          if ( $uname !== "" && $firstname !== "" &&  $lastname !== "" && $email !== "" && $pwd1!== "" &&  $pwd2 !=="" && $pwd1 === $pwd2)
          {
            $hashvalue = password_hash($pwd1, PASSWORD_DEFAULT);//

            require_once ('config/dbaccess.php');

            $conn = new mysqli($host, $username, $password, $database); //connect to database

            if($conn->connect_error)
            {
              die('Failed to connect to database: ' . mysqli_connect_error());
            }

            // check if the user already exists
            $query = "SELECT id FROM user_profiles WHERE email = '$email'";
            
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) <= 0)
            {
              $sql = "INSERT INTO user_profiles(username, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)"; //create an SQL statement to insert data
              $stmt = $conn->prepare($sql); //prepare sql connection
              $stmt-> bind_param("sssss", $uname, $firstname, $lastname, $email, $hashvalue); //bind parameters
              $stmt->execute(); //execute sql statements
              $stmt->close();
              $conn->close();
          
              header("Location: login.php");
          }
          else 
          {
              // error message, in case user already exists
      
              echo "You are already registered.";
          } 
        }  
  ?>

    <form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">      
    <div class="form-signin"> 
        <h3><strong>Register</strong></h3>
        <input type="text" name ="firstname" placeholder="Firstname" required><br>
            <input type="text" name ="lastname" placeholder="Lastname"required><br>
            <input type="text" name ="uname" placeholder="Username" required><br>           
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name ="pwd1" placeholder="Password" required><br>
            <input type="password" name ="pwd2" placeholder="Confirm password"required><br>
            <input type="checkbox" id="agb-checkbox" name="agb" value="agb">
            <label id="agb" for="agb">I agree that this data will be used for registration.</label><br>
            <input type="submit" name = "submit" value="Register"><br><br><br>
 <!-- <div class="col-md-4">
    <label for="validationDefault01" name ="firstname" class="form-label">First name</label>
    <input type="text" class="form-control" id="validationDefault01" value="Mark" required>
  </div>
  <div class="col-md-4">
    <label for="validationDefault02" name ="lastname" class="form-label">Last name</label>
    <input type="text" class="form-control" id="validationDefault02" value="Otto" required>
  </div>
  <div class="col-md-4">
    <label for="validationDefaultUsername" name ="uname" class="form-label">Username</label>
    <div class="input-group">
      <span class="input-group-text" id="inputGroupPrepend2">@</span>
      <input type="text" class="form-control" id="validationDefaultUsername"  aria-describedby="inputGroupPrepend2" required>
    </div>
  </div>
  <div class="col-md-6">
    <label for="validationDefault03" class="form-label">Email</label>
    <input type="email" name ="email" class="form-control" id="validationDefault03" required>
  </div>
  <div class="col-md-6">
    <label for="validationDefault04" class="form-label">Password</label>
    <input type="password" name ="pwd1" class="form-control" id="validationDefault04" required>
  </div>
  <div class="col-md-6">
    <label for="validationDefault05" class="form-label">Confirm password</label>
    <input type="password" name ="pwd2" class="form-control" id="validationDefault05" required>
  </div>
  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
      <label class="form-check-label" for="invalidCheck2">
      I agree that this data will be used for registration
      </label>
    </div>
  </div>
  <div class="col-12">
    <button class="btn btn-primary" name ="submit" type="submit">Submit form</button>
  </div>-->
  </div>     
</form>

<?php
include_once "footer.php";
?>
