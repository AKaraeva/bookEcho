
<?php

include_once "header.php";


function saveSciFiBook($conn, $user_id, $title, $author, $genre, $description, $no_of_pages, $awards, $first_published, $file_path) {
    // Prepare an SQL statement for execution
    $stmt = $conn->prepare("INSERT INTO books (user_id, title, author, genre, description, no_of_pages, awards, first_published, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind the parameters to the SQL query
    $stmt->bind_param("issssisis", $user_id, $title, $author, $genre, $description, $no_of_pages, $awards, $first_published, $file_path);

    // Execute the statement
    if ($stmt->execute() === false) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }

    // Close the statement
    $stmt->close();
}

// Checks if the user is not authenticated (not logged in)
if (!isset($_SESSION["user"])) {
    // Redirects to login page
    header("Location: login.php");
    exit; // Ensure no further code is executed after redirect
}

$user_id = $_SESSION["user_id"];

if (isset($_SESSION["book_id"])) {
    unset($_SESSION["book_id"]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize POST data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    $no_of_pages = $_POST['no_of_pages'];
    $awards = $_POST['awards'];
    $first_published = $_POST['published_date'];
    $file_path = '';

    if (isset($_FILES['media_file'])) {
        $file_name = preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['media_file']['name'])); // Sanitize file name
        $file_tmp = $_FILES['media_file']['tmp_name'];
        $file_size = $_FILES['media_file']['size'];
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];

        // Validate file size and type
        if ($file_size > 5000000) { // 5MB limit
            $_SESSION['error'] = "File size exceeds limit.";
        } elseif (!in_array($_FILES['media_file']['type'], $allowed_types)) {
            $_SESSION['error'] = "Invalid file type.";
        } else {
            $file_path = 'images/' . uniqid() . "_" . $file_name;
            if (!move_uploaded_file($file_tmp, $file_path)) {
                $_SESSION['error'] = "Error uploading the file.";
            }
            else {
                // Save the book details to the database
                include_once "config/dbaccess.php";

                $conn = new mysqli($host, $username, $password, $database);

                // Checks connection to db
                if ($conn->connect_error) {
                    die("Connection to database failed: " . $conn->connect_error);
                }

                saveSciFiBook($conn, $_SESSION["user_id"], $title, $author, $genre, $description, $no_of_pages, $awards, $first_published, $file_path);

                $conn->close();
            
            }
        }
    }
}
    include_once "header.php";

    //checks if the user is not authenticated (not logged in)
    if (!isset($_SESSION["user"])) {
    //redirects to login page
        header("Location: login.php");
    }
   
    if(isset($_SESSION["book_id"]))
    {
        unset($_SESSION["book_id"]);
    } 
?>
<div class = "modal"  id="uploadModal">
<!-- Modal content -->
<div class="modal-content">
    <span class="close" id="closeModal">&times;</span>
    <h2>Upload book</h2>
    <form method="POST" enctype="multipart/form-data">

    <form action="upload_foto.php" method="POST" enctype="multipart/form-data">
        <p><label for="title">Title:</label></p>
        <input type="text" id="title" name="title" required>

        <p><label for="author">Author:</label></p>
        <input type="text" id="author" name="author" required>

        <p><label for="description">Description:</label></p>
        <textarea id="description" name="description"></textarea>

        <p><label for="genre">Genre:</label></p>
    <select name="genre" id="genre" required>
        <option value="romance">Romance</option>
        <option value="classical">Classical Literature</option>
        <option value="fantasy">Fantasy</option>
        <option value="science_fiction">Science Fiction</option>
        <option value="biography">Biography/Autobiography</option>
        <option value="self-help">Self-Help</option>
        <option value="travel">Travel</option>
        <option value="mystery/thriller">Mystery/Thriller</option>
        <option value="horror">Horror</option>
        <option value="adventure">Adventure</option>
        <option value="drama">Drama</option>
        <option value="young adult">Young Adult</option>
        <option value="children">Children's Literature</option>
        <option value="poetry">Poetry</option>
    </select>

        <p><label for="no_of_pages">Number of pages:</label></p>
        <input type = "number" id="no_of_pages" name="no_of_pages" required>

        <p><label for="awards">Awards:</label></p>
        <textarea id="awards" name="awards"></textarea>

        <p><label for="first_published">First published:</label></p>
        <input type = "number" id="published_date" name="published_date">

        <p><label for="media_file">Select a File:</label></p>
        <input type="file" id="media_file" name="media_file" accept="image/*" required>

        <button type="submit">Upload</button>
    </form>
</div>
</div>
<button id="openModal">Upload book</button>

<div class = "images-container"></div>

  <?php

    include_once ("config/dbaccess.php");

    $conn = new mysqli($host, $username, $password, $database);

    //checks connection to db
    if ($conn->connect_error) {
        die("Connection to database failed: " . $conn->connect_error);
    }

    //sql request to get data from database
    $sql = "SELECT * FROM books WHERE genre = 'science_fiction'" ;
    $result = $conn->query($sql);

    //checks if data found
    if ($result->num_rows > 0) {
       echo "<ul>";
        while ($row = $result->fetch_assoc()) {

            $book_id = $row['book_id'];
            $title = $row['title'];
            echo '<div class = "product-list">';
            echo '<div class = "product">';
            echo "<strong>Title:</strong> <a href='book_details.php?id=$book_id'>$title</a><br>";
            echo "<strong>Author:</strong> " . $row['author'] . "<br>";
            echo "<strong>Description:</strong> " . $row['description'] . "<br>";
            echo "<strong>Pages:</strong> " . $row['no_of_pages'] . "<br>";
            echo "<strong>Awards:</strong> " . $row['awards'] . "<br>";
            echo "<strong>First Published:</strong> " . $row['first_published'] . "<br>";
            echo '<img src="' . $row['file_path'] . '" onclick="anzeigeBild(\'' . $row['file_path'] . '\')" style="margin-right: 10px; width: 25%; height: 25%;">';
            echo '</div>';
            echo '</div>';
        }
        echo "</ul>";

    } else {
        echo "No books yet. Be the first - upload your favorite book!";
    }?>
    <?php
    // closes connection to db
    $conn->close();
    ?>

<script src="media_galery.js"></script> 
<?php
    include_once "footer.php";
?>
