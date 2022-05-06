<!-- Author: Alex Spalvieri
     ID: 200403578 -->
<?php
    session_start();
    if (isset($_POST['submit'])) {
        //Gather POST variables & validations
        $tval = filter_input(INPUT_POST, "submit", FILTER_SANITIZE_NUMBER_INT);
        $bookname = filter_input(INPUT_POST, "bookname", FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_input(INPUT_POST, "genre", FILTER_SANITIZE_SPECIAL_CHARS);
        $review = filter_input(INPUT_POST, "review", FILTER_SANITIZE_SPECIAL_CHARS);
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $booklink = filter_input(INPUT_POST, "booklink", FILTER_SANITIZE_SPECIAL_CHARS);
        
        //This checks to see if the user is submitting a new image, or if they're editing and not uploading a new image
        $image = ($tval == 1 || !empty($_FILES['image']['name'])) ? $_FILES['image']['name'] : filter_input(INPUT_POST, "imageraw");

        if ($tval == 2) {
            if (!isset($_SESSION['userid'])) {
                header("Location: viewBook.php");
                exit();
            }
            //If transition value is set for editing, grab the primary key and raw image incase of errors
            $id = filter_input(INPUT_POST, "bookid", FILTER_SANITIZE_NUMBER_INT);
            $imgraw = filter_input(INPUT_POST, "imageraw", FILTER_SANITIZE_SPECIAL_CHARS);
        }
        
        //Form variable validation, ensures that only the best variables get into my database!
        if ($bookname == "") {
            $error = "Invalid input for \"Book's Name\"!";
        } elseif (strlen($bookname) > 128) {
            $error = "Input is too large for \"Book's Name\"!";
        }
        elseif ($genre == "") {
            $error = "Invalid input for \"Book's Genre\"!";
        } elseif (strlen($genre) > 128) {
            $error = "Input is too large for \"Book's Genre\"!";
        }
        elseif ($review == "") {
            $error = "Invalid input for \"Short Review of Book\"!";
        } elseif (strlen($review) > 128) {
            $error = "Input is too large for \"Short Review of Book\"!";
        }
        elseif ($username == "") {
            $error = "Invalid input for \"Your Name\"!";
        } elseif (strlen($username) > 128) {
            $error = "Input is too large for \"Your Name\"!";
        }
        elseif ($email == FALSE || $email == "") {
            $error = "Invalid input for \"Your Email\"!";
        } elseif (strlen($email) > 128) {
            $error = "Input is too large for \"Your Email\"!";
        }
        elseif ($booklink == FALSE || $booklink == "") {
            $error = "Invalid input for \"Link to Purchase Book\"!";
        } elseif (strlen($booklink) > 128) {
            $error = "Input is too large for \"Link to Purchase Book\"!";
        }
        elseif ($image == "") {
            $error = "Invalid input for \"Image of Book\"!";
        } elseif (strlen($image) > 128) {
            $error = "Input is too large for \"Image of Book\"!";
        }
        elseif (!empty($_FILES['image']['name'])) { //File validation, make sure we only allow certain types
            $allowedtypes = array("jpg", "jpeg", "png", "gif");
            $fext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            if (!in_array($fext, $allowedtypes)) {
                //If there are errors with files, unset the file and set image to raw
                $error = "File type of: \".".$fext."\", not allowed for \"Image of Book\"!";
                unset($_FILES['image']);
                $image = $imgraw;
            }
        }

        //If there are no errors thus-far, try to upload image
        if (empty($error) && !empty($_FILES['image']['name'])) {
            //If editing, delete previous image
            if ($tval == 2) {
                require_once("dbconnect.php");

                $cmd = $conn->prepare("SELECT image FROM books WHERE book_id=:id");
                $cmd->bindParam(":id", $id, PDO::PARAM_INT, 11);
                $cmd->execute();
                $result = $cmd->fetch();
                if (is_array($result)) {
                    unlink("images/".$result['image']);
                }
            }
            $imgtarget = "images/" . basename($image);

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imgtarget)) {
                //If there are errors with files, unset the file and set image to raw
                $error = "Failed to upload image for \"Image of Book\"!";
                unset($_FILES['image']);
                $image = $imgraw;
            }
        }

        //Re-print submitBook if errors exist, else save to database
        if (!empty($error)) {
            require("submitBook.php");
        }
        else {
            require_once("dbconnect.php");

            try {
                if ($tval == 1) {
                    $cmd = $conn->prepare("INSERT INTO books(bookname, genre, review, username, email, booklink, image) VALUES(:bookname, :genre, :review, :username, :email, :booklink, :image)");
                    $cmd->bindParam(":bookname", $bookname, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":genre", $genre, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":review", $review, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":username", $username, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":email", $email, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":booklink", $booklink, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":image", $image, PDO::PARAM_STR, 128);
                    $cmd->execute();
                    $alert = "Successfully added book: \"" . $bookname . "\", to the book database!";
                }
                elseif ($tval == 2) {
                    $cmd = $conn->prepare("UPDATE books SET bookname=:bookname, genre=:genre, review=:review, username=:username, email=:email, booklink=:booklink, image=:image WHERE book_id=:id");
                    $cmd->bindParam(":id", $id, PDO::PARAM_INT, 11);
                    $cmd->bindParam(":bookname", $bookname, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":genre", $genre, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":review", $review, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":username", $username, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":email", $email, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":booklink", $booklink, PDO::PARAM_STR, 128);
                    $cmd->bindParam(":image", $image, PDO::PARAM_STR, 128);
                    $cmd->execute();
                    $alert = "Successfully edited book: \"" . $bookname . "\"!";
                }
            }
            catch (PDOException $e) {
                echo $e;
            }

            require((isset($_SESSION['userid'])) ? "modifyBook.php" : "viewBook.php");
        }
    }
?>