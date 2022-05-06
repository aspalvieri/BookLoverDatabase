<!-- Author: Alex Spalvieri
     ID: 200403578 -->
<?php
    session_start();
    if (!isset($id)) {
        //Primary Key for the book in the database
        $id = NULL;
    }
    if (!isset($tval)) {
        //Transition value:
        //1 = submit book
        //2 = edit book
        $tval = 1;
    }

    if (isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if ($id != FALSE && $id != NULL) {
            if (!isset($_SESSION['userid'])) {
                header("Location: submitBook.php");
                exit();
            }
            require_once("dbconnect.php");
            $cmd = $conn->prepare("SELECT * FROM books WHERE book_id=:id");
            $cmd->bindParam(":id", $id, PDO::PARAM_INT, 11);
            $cmd->execute();
            $tval = 2;
            $result = $cmd->fetchAll();
            foreach ($result as $r) {
                $bookname = $r['bookname'];
                $genre = $r['genre'];
                $review = $r['review'];
                $username = $r['username'];
                $email = $r['email'];
                $booklink = $r['booklink'];
                $image = $r['image'];
            }
        }
    }
?>

<?php require_once("header.php"); ?>
<main class="panel panel-default">
    <div class="panel-body">
        <form action="sendBook.php" method="POST" enctype="multipart/form-data">
            <h2>Book Submission Form</h2>
            <?php if (!empty($error)) : ?>
            <div class="alert alert-danger alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Error!</strong> <?=$error?>
            </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="bookname">Book's Name:</label>
                <input type="text" name="bookname" class="form-control" value="<?=$bookname?>">
            </div>
            <div class="form-group">
                <label for="genre">Book's Genre:</label>
                <input type="text" name="genre" class="form-control" value="<?=$genre?>">
            </div>
            <div class="form-group">
                <label for="review">Short Review of Book:</label>
                <input type="text" name="review" class="form-control" value="<?=$review?>">
            </div>
            <div class="form-group">
                <label for="username">Your Name:</label>
                <input type="text" name="username" class="form-control" value="<?=$username?>">
            </div>
            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email" name="email" class="form-control" value="<?=$email?>">
            </div>
            <div class="form-group">
                <label for="booklink">Link to Purchase Book:</label>
                <input type="url" name="booklink" class="form-control" value="<?=$booklink?>">
            </div>
            <div class="form-group">
                <label for="image">Image of Book<?php if ($tval == 2) { echo " (Leave blank to not change)"; } ?>: </label>
                <input type="file" class="btn btn-default btn-file" name="image">
            </div>
            
            <br>
            <input type="hidden" name="submit" value="<?=$tval?>">
            <input type="hidden" name="bookid" value="<?=$id?>"> <!-- Used for passing which primary key to modify in the database -->
            <input type="hidden" name="imageraw" value="<?=$image?>"> <!-- If the user is editing, keep a path to the currently stored image -->
            <input type="submit" value='<?=($tval == 1) ? "Submit Book" : "Save Changes" ?>' class="btn btn-primary">
            <?php if ($tval == 2) : ?>
            &nbsp;<a href="modifyBook.php" class="btn btn-primary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</main>
<?php require_once("footer.php"); ?>