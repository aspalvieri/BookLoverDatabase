<!-- Author: Alex Spalvieri
     ID: 200403578 -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Book Lovers</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E=" crossorigin="anonymous"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    <!-- My custom CSS -->
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <div class="container panel-default">
        <header class="page-header panel panel-heading" style="margin: 10px auto">
            <h1><a href="index.php">Book Lovers</a><?php if (isset($_SESSION['userid'])) : ?>&nbsp;<p id="namedisplay">Logged in as: <?= $_SESSION['userid'] ?></p><?php endif; ?></h1>
            <a href="submitBook.php" class="btn btn-default">Submit Book</a> &nbsp;
            <?php if (!isset($_SESSION['userid'])) : ?>
                <a href="viewBook.php" class="btn btn-default">View Books</a> &nbsp;
                <a href="login.php" class="btn btn-default">Login</a> &nbsp;
                <a href="register.php" class="btn btn-default">Register</a> &nbsp;
            <?php elseif (isset($_SESSION['userid'])) : ?>
                <a href="modifyBook.php" class="btn btn-default">View Books</a> &nbsp;
                <a href="logout.php" class="btn btn-default">Logout</a>
            <?php endif; ?>
        </header>