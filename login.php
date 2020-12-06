<?php
session_start();
$_SESSION['userid'] = 0;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">		
        <title>Shopper</title>				

        <!-- Bootstrap Core CSS file -->
        <link rel="stylesheet" href="assets/css/bootstrap.css">

        <!-- Override CSS file - add your own CSS rules -->
        <link rel="stylesheet" href="assets/css/styles.css">

    </head>
    <body>

        <!-- Include Nav Bar HTML -->
        <?php
        include 'navBarBeforeLogin.html';
        ?>

        <!-- Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">

                    <!-- Page breadcrumb -->
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>                       
                    </ol>

                    <!-- Page Heading -->
                    <h1>Shopper - Login</h1>
                    <hr>
                    
                    <!-- Login Form -->
                    <h3>Log Into Shopper</h3>     
                    <div class="well">
                        <form name="login" id="login" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                            <div class="form-group">
                                <label for="userName">User Name</label>
                                <input name="userName" type="text" class="form-control" id="userName" placeholder="Enter your user name" required="" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input name="password" type="password" class="form-control" id="password" placeholder="Enter a password" required="" maxlength="100">
                            </div>                                                
                            <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <?php
                        //check to see if the submit button was pushed
                        if ((isset($_POST['submit']))) {

                            //include database handler file so that we can
                            //call it's functions
                            include 'dbHandler.php';

                            //getting data input into the form and assigning
                            //it to variables
                            $userName = $_POST['userName'];
                            $password = $_POST['password'];                            

                            //connecting to MySQL and selecting shopper database
                            $link = connect();

                            $status = select_user($link, $userName, $password);

                            if ($status == 0) {                               
                                echo '<p class="help-block">You\'re not a valid user of Shopper!</p>';
                            } else {
                                $_SESSION['userid'] = $status;
                                
                                close($link);
                                
                                header("Location: http://localhost/Shopper/list.php");
                                
                                exit();
                            }

                            //close connection to MySQL
                            close($link);
                        }
                        ?>
                    </div>
                    <hr>                    

                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

        <!-- JQuery scripts -->
        <script src="assets/js/jquery-1.11.2.min.js"></script>

        <!-- Bootstrap Core scripts -->
        <script src="assets/js/bootstrap.min.js"></script>
    </body>
</html>

