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
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, "password");

        //Validate inputs and print errors
        if ($username == "") {
            $error = "Invalid input for \"Username\"!";
        } elseif (strlen($username) > 16) {
            $error = "Input is too large for \"Username\"!";
        }
        elseif ($email == FALSE || $email == "") {
            $error = "Invalid input for \"Email\"!";
        } elseif (strlen($email) > 40) {
            $error = "Input is too large for \"Email\"!";
        }
        elseif ($password == "") {
            $error = "Invalid input for \"Password\"!";
        } elseif (strlen($password) > 128) {
            $error = "Input is too large for \"Password\"!";
        }

        $cmd = $conn->prepare("SELECT user_id FROM users WHERE username=:username OR email=:email");
        $cmd->bindparam(":username", $username);
        $cmd->bindparam(":email", $email);
        $cmd->execute();
        $result = $cmd->fetch();
        if (isset($result['user_id'])) {
            $error = "Username or email is already in use!";
        }

        //If there are no errors, enter to database
        if (empty($error)) {
            $hpassword = password_hash($password, PASSWORD_DEFAULT);

            $cmd = $conn->prepare("INSERT INTO users(username, email, password) VALUES(:username, :email, :password)");

            $cmd->bindparam(":username", $username);
			$cmd->bindparam(":email", $email);
            $cmd->bindparam(":password", $hpassword);
            
            $cmd->execute();
            
            $_SESSION['userid'] = $username;

            $alert = "Registered successfully!";
            require("index.php");
            exit();
        }
    }
?>
<?php require_once("header.php"); ?>
<main class="panel panel-default">
    <div class="panel-body">
        <form method="POST">
            <h2>Registration Form</h2>
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
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="<?=$email?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="passbox" name="password" class="form-control" value="">
                <label for="cfpassword">Confirm Password:</label>
                <input type="password" id="conpassbox" name="cfpassword" class="form-control" value="">
                <p id="conpass">&nbsp;</p>
            </div>
            <input type="hidden" name="submit" value="1">
            <input type="submit" id="submitBtn" value="Register" class="btn btn-primary">
        </form>
    </div>
</main>
<?php require_once("footer.php"); ?>
<script>
var confirm = document.getElementById("conpassbox");
var pdm = document.getElementById("conpass");
var pass = document.getElementById("passbox");
var submit = document.getElementById("submitBtn");
window.onload = function() {
    confirm.addEventListener("keyup", function () {
        check();
    }, false);
    pass.addEventListener("keyup", function () {
        check();
    }, false);
}

function check() {
    if (confirm.value != pass.value && confirm.value != "" && pass.value != "") {
        pdm.innerHTML = "Passwords don't match!";
        submit.disabled = true;
    }
    else {
        pdm.innerHTML = "&nbsp;";
        submit.disabled = false;
    }
}
</script>