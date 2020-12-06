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
                    <h1>Shopper - Create Shopping List Items</h1>
                    <hr>

                    <!-- Create Shopping List Item Form -->
                    <h3>Create A Shopping List Item</h3>     
                    <div class="well">
                        <form name="createItem" id="createItem" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                            <div class="form-group">
                                <label for="itemName">Name</label>
                                <input name="itemName" type="text" class="form-control" id="itemName" placeholder="Enter a name" required="" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label for="itemPrice">Price</label>
                                <input name="itemPrice" type="number" step=".01" min="0.00" class="form-control" id="itemPrice" placeholder="Enter a price" required="">
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
                            $itemName = filter_input(INPUT_POST, 'itemName');
                            $itemPrice = filter_input(INPUT_POST, 'itemPrice');     
                     
                            //get the logged in user's user id
                            $userid = $_SESSION['userid'];

                            //connecting to MySQL and selecting shopper database
                            $link = connect();

                            //calling our create_list function
                            $status = create_item($link, $itemName, $itemPrice, $userid);

                            //output an html paragraph that contains the return
                            //from our create_list function
                            echo '<p class="help-block">' . $status . '</p>';

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

