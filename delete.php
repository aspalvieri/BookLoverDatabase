<!-- Author: Alex Spalvieri
     ID: 200403578 -->
<?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header("Location: viewBook.php");
        exit();
    }
    if (isset($_GET['book_id'])) {
        require_once("dbconnect.php");
        $id = filter_input(INPUT_GET, "book_id", FILTER_VALIDATE_INT);

        if ($id == FALSE || $id == NULL) {
            header("Location: modifyBook.php");
            return;
        }
        else {
            //Grab the name of the book we're about to delete, as well as delete the image
            $bname = "";
            $cmd = $conn->prepare("SELECT image, bookname FROM books WHERE book_id=:id");
            $cmd->bindParam(":id", $id, PDO::PARAM_INT, 11);
            $cmd->execute();
            $result = $cmd->fetch();
            if (is_array($result)) {
                unlink("images/".$result['image']);
                $bname = $result['bookname'];
            }

            //Delete book from database
            $cmd = $conn->prepare("DELETE FROM books WHERE book_id=:id");
            $cmd->bindParam(":id", $id, PDO::PARAM_INT, 11);
            $cmd->execute();

            $alert="Successfully deleted book: \"".$bname."\", from the book database!";
            require("modifyBook.php");
        }
    }
?>