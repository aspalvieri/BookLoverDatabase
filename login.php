<!-- Author: Alex Spalvieri
     ID: 200403578 -->
<?php 
    session_start();
    if (isset($_SESSION['userid'])) {
        header("Location: index.php");
        exit();
    }
    require_once("dbconnect.php");

    if (isset($_POST['submit'])) {
        //Filter post inputs
        $username = filter_input(INPUT_POST, "username");
        $password = filter_input(INPUT_POST, "password");

        //Validate inputs and print errors
        if ($username == "") {
            $error = "Invalid input for \"Username\"!";
        } elseif (strlen($username) > 40) {
            $error = "Input is too large for \"Username\"!";
        }
        elseif ($password == "") {
            $error = "Invalid input for \"Password\"!";
        } elseif (strlen($password) > 128) {
            $error = "Input is too large for \"Password\"!";
        }

        //If there are no errors, enter to database
        if (empty($error)) {
            $cmd = $conn->prepare("SELECT * FROM users WHERE username=:username OR email=:username");

            $cmd->bindparam(":username", $username);
            
            $cmd->execute();

            $results = $cmd->fetchAll();

            foreach ($results as $user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['userid'] = $user['username'];
                    $alert = "Logged in successfully!";
                    require("index.php");
                    exit();
                }
            }
            
            //If unable to login
            $error = "Invalid username or password!";
        }
    }
?>
<?php require_once("header.php"); ?>
<main class="panel panel-default">
    <div class="panel-body">
        <form method="POST">
            <h2>Login Form</h2>
            <?php if (!empty($error)) : ?>
            <div class="alert alert-danger alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Error!</strong> <?=$error?>
            </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" value="<?=$username?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" value="<?=$password?>">
            </div>
            <input type="hidden" name="submit" value="1">
            <input type="submit" value="Login" class="btn btn-primary">
        </form>
    </div>
</main>
<?php require_once("footer.php"); ?>