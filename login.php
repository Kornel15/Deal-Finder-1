<?php include('header.php'); ?>
<?php require('database.php'); ?>

<?php session_start(); ?>

<?php
    $msg = '';
    $msgClass = '';

    if(isset($_POST['loginbtn'])) {
        $email = $_POST['emailfield'];
        $password = $_POST['pwdfield'];
        //$pass = md5($password);

        if(empty($email) || empty($password)) {
            $msg = "Your credential(s) are empty";
            $msgClass = "alert-danger";
        } else {
            $sql = "SELECT email, password FROM users";
            $result = mysqli_query($conn, $sql);
            $resultCheck = mysqli_num_rows($result);

            if($resultCheck > 0) {
                $credentials = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $loggedin = false;
                $emailfound = false;

                foreach ($credentials as $value) {
                    if(strcmp($email, $value['email']) == 0) {
                        $emailfound = true;
                        $pass = md5($password);

                        if(strcmp($pass, $value['pass'])) {
                            
                            $loggedin = true;
                            break;
                        } else {
                            $msg = "Password mismatch!";
                            $msgClass = "alert-danger";
                            break;
                        }
                    } else if(strcmp($email, $value['email']) != 0) {
                        $emailfound = false;
                    }
                }

                if(!$emailfound) {
                    $msg = "Email not registered!";
                    $msgClass = "alert-danger"; 
                }

         
                if($loggedin) {
                    $sql = "SELECT first_name, user_id FROM users WHERE email = ". '"'. $email. '"';
                    $result = mysqli_query($conn, $sql);
                    $resultName = mysqli_fetch_assoc($result);

                    
                    $_SESSION['username'] = $resultName['first_name'];
                    $_SESSION['user_id'] = $resultName['user_id'];
                    $_SESSION['errors'] = '';

                    header("Location: home.php");
                }
            }
            mysqli_free_result($result);
        }
        mysqli_close($conn);
    }
?>

<title> Login </title>
<div class="container">
<link rel="stylesheet" type="text/css" href="css/signup.css">


    <?php if(strlen($msg) != 0) : ?>
        <div class ="text-center alert <?php echo $msgClass; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

        <div class="jumbotron jumbotron-fluid text-center">
            <div class="h2"> Login </div>
            <form class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            	<div class="form-group">
            		<input type="Email" class="form-control textbox" name="emailfield" placeholder="Email" value="<?php echo isset($_POST["emailfield"]) ? $_POST["emailfield"] : ''; ?>">
            		<input type="password" class="form-control textbox" name="pwdfield" placeholder="Password" value="<?php echo isset($_POST["pwdfield"]) ? $_POST["pwdfield"] : ''; ?>">
            	</div>
            	<div class="form-group">
                    <input type="submit" class="btn btn-danger btn-lg" name="loginbtn" id="submit" value="Login">
                </div>
            </form>
        </div>
    </div>

<?php include 'footer.php'; ?>
