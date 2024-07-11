<?php 
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

<div class = "images-container">
  <?php

    include_once ("config/dbaccess.php");

    $conn = new mysqli($host, $username, $password, $database);

    //checks connection to db
    if ($conn->connect_error) {
        die("Connection to database failed: " . $conn->connect_error);
    }

    //sql request to get data from database
    $sql = "SELECT * FROM books WHERE genre = 'travel'" ;
    $result = $conn->query($sql);

    //checks if data found
    if ($result->num_rows > 0) {
       echo "<ul>";
        while ($row = $result->fetch_assoc()) {

            $book_id = $row['book_id'];
            $title = $row['title'];

            echo '<div class = "product">';
            echo "<strong>Title:</strong> <a href='book_details.php?id=$book_id'>$title</a><br>";
            echo "<strong>Author:</strong> " . $row['author'] . "<br>";
            echo "<strong>Description:</strong> " . $row['book_description'] . "<br>";
            echo "<strong>Pages:</strong> " . $row['no_of_pages'] . "<br>";
            echo "<strong>Awards:</strong> " . $row['awards'] . "<br>";
            echo "<strong>First Published:</strong> " . $row['first_published'] . "<br>";
            echo '<img src="' . $row['file_path'] . '" onclick="anzeigeBild(\'' . $row['file_path'] . '\')" style="margin-right: 10px; width: 25%; height: 25%;">';
            echo '</div>';
        }
        echo "</ul>";

    } else {
        echo "No books yet. Be first upload you favorite book!";
    }?>
    <?php
    // closes connection to db
    $conn->close();
    ?>
</div>

<script src="media_galery.js"></script> 
<?php
    include_once "footer.php";
?>
