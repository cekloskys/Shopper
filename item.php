<?php
//start a new or resume an existing session
session_start();

//if the userid session variable isn't set
//or if it's equal to zero
if (!isset($_SESSION['userid']) || ($_SESSION['userid'] == 0)) {
    //redirect the user to the Login page
    header('Location: login.php');
}
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
        include 'navBarAfterLogin.html';
        ?>

        <!-- Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">

                    <!-- Page breadcrumb -->
                    <ol class="breadcrumb">
                        <li><a href="createItem.php">Create Item</a></li>                        
                    </ol>

                    <!-- Page Heading -->
                    <h1>Shopper - Shopping List Items</h1>
                    <hr>                                                           

                    <!-- Shopping List Table -->
                    <h3>Here are your Shopping List Items</h3>  
                    <div class="well">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Price</th> 
                                    <th>Picture</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //check if the user session variable is not
                                //equal to zero - this means we have a valid
                                //user logged into Shopper
                                if ($_SESSION['userid'] != 0) {

                                    include 'dbHandler.php';

                                    $userid = $_SESSION['userid'];
                                    
                                    $target_path = "../uploads/";

                                    $link = connect();

                                    $status = select_items($link, $userid, $target_path);

                                    echo $status;

                                    close($link);
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
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

