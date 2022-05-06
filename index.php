<!-- Author: Alex Spalvieri
     ID: 200403578 -->

<!-- The way to access this file is from a button in the footer -->
<?php session_start(); ?>
<?php require_once("header.php"); ?>
<main class="panel panel-default">
    <div class="panel-body">
        <?php if (!empty($alert)) : ?>
            <div class="alert alert-success alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> <?=$alert?>
            </div>
        <?php endif; ?>
        <h2>Book Lovers Database</h2>
        <p>This website is a place for people to upload reviews of a book, and other relating information. To submit a book, click the <a href="submitBook.php">Submit Book</a> button.
        To view all the books in the database, use <a href="viewBook.php">View Books</a> button. If you wish to modify or delete a book entry, you'll need to <a href="login.php">Login</a>. If you don't have an
        account, use the <a href="register.php">Registration Form</a>.</p>
    </div>
</main>
<?php require_once("footer.php"); ?>