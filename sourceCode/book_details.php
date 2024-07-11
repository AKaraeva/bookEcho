<?php
    include_once "header.php";
  ?> 
    <main>
            <?php
                   
            if (isset($_GET["id"]))
            {
                $book_id = $_GET["id"];

                if (!isset($_SESSION["book_id"]))
                {
                    $_SESSION["book_id"] = $book_id;
                    $book_id = $_SESSION["book_id"];                   
                }
                $book_id = $_SESSION["book_id"];
            }          
            else
            {
                $book_id = $_SESSION["book_id"];
            }
                
            //echo $discussion_id;
            include_once "config/dbaccess.php";
            // Create a database connection
            $conn = new mysqli($host, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to fetch discussion details
           // $sql = "SELECT * FROM forum_discussions WHERE discussion_id = $dis_id";
           // $result = $conn->query($sql);

            $query = $conn->prepare("SELECT  ROUND(AVG(rating)) AS average_rating, COUNT(user_id) AS user_count FROM ratings WHERE book_id = ?");           
            $query->bind_param("i", $book_id);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                
                while($result_row = $result->fetch_assoc()){

                    $avg_rating = $result_row['average_rating']; 
                    $user_count = $result_row['user_count'];
                }             
            }

            $stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $row = $stmt->get_result();

            if ($row->num_rows > 0) {

                while($book_row = $row->fetch_assoc())
                {
                    $book_title = $book_row['title'];
                    $book_author = $book_row['author'];
                // Displays book details 
                echo '<div class ="product">';
                echo "<strong>Title:</strong> " . $book_title . "<br>";
                echo "<strong>Author:</strong> " . $book_author . "<br>";
                echo "<strong>Description:</strong> " . $book_row['book_description'] . "<br>";
                echo "<strong>Pages:</strong> " . $book_row['no_of_pages'] . "<br>";
                echo "<strong>Awards:</strong> " . $book_row['awards'] . "<br>";
                echo "<strong>First Published:</strong> " . $book_row['first_published'];
                echo '</div>';
                echo '<img class="book-image" src="' . $book_row['file_path'] . '" onclick="anzeigeBild(\'' . $book_row['file_path'] . '\')" style="margin-right: 10px; width: 25%; height: 20%;">';               
                }
                echo'<br>';

                if ($user_count > 0) {
                    echo "<strong>Average rating:</strong> " . $avg_rating . " out of 5 based on " . $user_count . " rating(s)";
                } else {
                    echo "<strong>Average rating:</strong> 0 out of 5 based on 0 ratings";
                }
                echo '</div><br>';       
            }

            echo "<a href='write_review.php?id=$book_id&title=$book_title&author=$book_author'><strong>Write review</strong></a><br>";

            $stmt = $conn->prepare("SELECT * FROM ratings WHERE book_id = ?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $comments = $stmt->get_result();

            if ($comments->num_rows > 0) {
                echo "<br>";
                echo "<h5><strong><span style = 'color: green;'>Comments:</span></strong></h5>";

                while ($comment_row = $comments->fetch_assoc()) {
                    $comment_content = $comment_row['comment'];
                    $commenter_id = $comment_row['user_id'];
                    $created_at = $comment_row['date'];
                    $comment_id = $comment_row["rating_id"];

                

                    $comment_sql = "SELECT * FROM user_profiles WHERE id = $commenter_id";
                    $user_data = $conn->query($comment_sql);

                    if ($user_data->num_rows > 0)
                    {
                        $user_row = $user_data->fetch_assoc();
                        $commenter_name = $user_row['first_name']. " ".$user_row['last_name'];
                    }

                    if ($comment_id % 2 == 1)
                    {
                        $bc = "background-color: grey";
                    }
                    else
                    {
                        $bc = "background-color: lightgrey";
                    }

                    // Display each comment
                    echo "<div class='comment'>";  
                    echo "<p>by: <span style='color: blue;'>$commenter_name</span> - $created_at</p>";              
                    echo "<p>$comment_content</p>";               
                    echo "</div>";
                }                                       
                }               
                else 
                {
                    echo '<p style="color:green;"><br>Be the first to share your thoughts on this book—no comments yet!</p>';
                }

                  // Close the database connection
                  $conn->close();
                ?>                   
    </main>
    <?php
    include_once "footer.php";
    ?>
