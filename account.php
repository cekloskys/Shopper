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
                    <h1>Shopper - Create Account</h1>
                    <hr>

                    <!-- Create Account Form -->
                    <h3>Create Your Account</h3>     
                    <div class="well">
                        <form name="createAccount" id="createAccount" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                            <div class="form-group">
                                <label for="userName">User Name</label>
                                <input name="userName" type="text" class="form-control" id="userName" placeholder="Enter your user name" required="" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input name="password" type="password" class="form-control" id="password" placeholder="Enter a password" required="" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label for="emailAddress">Email Address</label>
                                <input name="emailAddress" type="email" class="form-control" id="emailAddress" placeholder="Enter email address" required="" maxlength="100">
                                <p class="help-block">Make sure you use a valid email address</p>
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
                            $emailAddress = $_POST['emailAddress'];

                            //connecting to MySQL and selecting shopper database
                            $link = connect();

                            $userNameExists = verify_user($link, $userName);

                            if ($userNameExists == 0) {
                                //inserting a record into the user table
                                $status = create_user($link, $userName, $password, $emailAddress);

                                //output an html paragraph that contains the return
                                //from our create_user function
                                echo '<p class="help-block">' . $status . '</p>';
                            } else {
                                echo '<p class="help-block">Username already exists!</p>';
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

