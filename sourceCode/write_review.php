<?php
    include_once "header.php";
  ?> 
    <main>
        <section class="book">
            <?php

            if (!isset($_SESSION["user_id"]))
            {
                header("Location: login.php");
            }
                   
            if (isset($_GET['id']) && isset($_GET['title']) && isset($_GET['author']))
            {
                $book_id = $_GET['id'];
                $book_author = $_GET['author'];
                $book_title = $_GET['title'];
                //$image = $_GET['file_path'];

                echo "<strong>Title:</strong> " . $book_title. "<br>";
                echo "<strong>Author:</strong> " . $book_author."<br><br>"; 
                //echo '<img src="' . $image . '"></img>';
            }    
            ?>
           <!-- <div class="container mt-5">-->
                <h5><strong>Rate this Book</strong></h5>
                <div class="rating" data-rating="0">
                    <span class="star" data-value="1">&#9733;</span>
                    <span class="star" data-value="2">&#9733;</span>
                    <span class="star" data-value="3">&#9733;</span>
                    <span class="star" data-value="4">&#9733;</span>
                    <span class="star" data-value="5">&#9733;</span>
                </div>
                <br>
                <p id="selected-rating">Selected Rating: 0</p>
           <!-- </div>-->

            <!-- Bootstrap JS and Popper.js (required for some Bootstrap components) -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

            <script>
            document.addEventListener("DOMContentLoaded", function() {
                const ratingContainer = document.querySelector('.rating');
                const selectedRating = document.getElementById('selected-rating');


                // Attach click event to each star
                ratingContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('star')) {
                        const ratingValue = parseInt(e.target.getAttribute('data-value'));
                        updateRating(ratingValue);
                        document.getElementById('rating').value = ratingValue;
                    }
                });

                // Function to update the selected rating
                function updateRating(value) {
                    ratingContainer.setAttribute('data-rating', value);
                    selectedRating.textContent = 'Selected Rating: ' + value;
                }

                // Function to send rating to the server
                function saveRatingToDatabase(rating) {
                    // Make an AJAX request to the server
                    const xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Handle the response from the server (if needed)
                            console.log(xhr.responseText);
                        }
                    };
                    xhr.open('POST', document.URL, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('rating=' + rating);
                }
                });
                </script>
                <!-- Comment form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <input type="hidden" id="rating" name="rating" value="0">
                <br>
                    <p>Add a written review:</p>
                    <textarea id="comment" name="comment" rows="4"></textarea><br>
                    <button type="submit" class="btn btn-primary">Submit</button>   
                </form>
 
 <?php
                  
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    // Retrieve user input
    $comment = $_POST['comment'];
    $rating = intval($_POST['rating']);

    // validates user input
    $comment = htmlspecialchars(trim($comment));

    require_once ('config/dbaccess.php');

    $conn = new mysqli($host, $username, $password, $database); //connect to database

    if($conn->connect_error)
    {
        die('Failed to connect to database: ' . mysqli_connect_error());
    }

    $sql = "SELECT * FROM ratings WHERE user_id = ? AND book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $_SESSION["user_id"], $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a review already exists, you might want to handle this case accordingly
    if ($result->num_rows > 0) {
        echo "You have already submitted a review for this book.";
        header("location: book_details.php");
    }
    else {
        $sql = "INSERT INTO ratings(user_id, book_id, rating, comment) VALUES (?, ?, ?, ?)"; //create an SQL statement to insert data
        $stmt = $conn->prepare($sql); //prepare sql connection

        $stmt-> bind_param("iiis",  $_SESSION["user_id"], $_SESSION["book_id"], $rating, $comment); //bind parameters
        $stmt->execute(); //execute sql statements
        $stmt->close();   
        $conn->close();

    }
    //header("Location: book_details.php");
     //exit();
    }

    include_once ("footer.php");
?>