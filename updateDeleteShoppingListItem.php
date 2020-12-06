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
                        <?php
                        $itemId = filter_input(INPUT_GET, 'itemId');
                        echo '<li><a href="addPicture.php?itemId=' . $itemId . '">Add Picture</a></li>';
                        ?>
                    </ol>

                    <!-- Page Heading -->
                    <h1>Shopper - Update/Delete Shopping List Item</h1>
                    <hr>

                    <!-- Create Shopping List Item Form -->
                    <?php
                    if ($_SESSION['userid'] != 0) {

                        include 'dbHandler.php';

                        $itemId = filter_input(INPUT_GET, 'itemId');

                        $link = connect();

                        $status = select_item_name($link, $itemId);

                        echo '<h3>Update/Delete Item ' . $status . '</h3>';

                        close($link);
                    }
                    ?>      
                    <div class="well">
                        <form name="updateDeleteItem" id="updateDeleteItem" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                            <div class="form-group">
                                <label for="itemName">Name</label>
                                <input name="itemName" type="text" class="form-control" id="itemName" required="" maxlength="100"
                                <?php
                                //check if the user session variable is not
                                //equal to zero - this means we have a valid
                                //user logged into Shopper
                                if ($_SESSION['userid'] != 0) {

                                    $itemId = filter_input(INPUT_GET, 'itemId');

                                    $link = connect();

                                    $status = select_item_name($link, $itemId);

                                    echo ' value="' . $status . '"';

                                    close($link);
                                }
                                ?>
                                       >
                            </div>
                            <div class="form-group">
                                <label for="itemPrice">Price</label>
                                <input name="itemPrice" type="number" step=".01" min="0.00" class="form-control" id="itemPrice" required=""
                                <?php
                                //check if the user session variable is not
                                //equal to zero - this means we have a valid
                                //user logged into Shopper
                                if ($_SESSION['userid'] != 0) {

                                    $itemId = filter_input(INPUT_GET, 'itemId');

                                    $link = connect();

                                    $status = select_item_price($link, $itemId);

                                    echo ' value="' . $status . '"';

                                    close($link);
                                }
                                ?>
                                       >
                            </div>                                                   
                            <button name="update" type="submit" class="btn btn-success">Update</button>
                            <button name="delete" type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <?php
                        //check to see if the update button was pushed
                        if ((isset($_POST['update']))) {
               
                            //getting data input into the form and assigning
                            //it to variables
                            $itemName = filter_input(INPUT_POST, 'itemName');
                            $itemPrice = filter_input(INPUT_POST, 'itemPrice');

                            $itemId = filter_input(INPUT_GET, 'itemId');

                            //connecting to MySQL and selecting shopper database
                            $link = connect();

                            //calling our create_list function
                            $status = update_item($link, $itemId, $itemName, $itemPrice);

                            //output an html paragraph that contains the return
                            //from our create_list function
                            echo '<p class="help-block">' . $status . '</p>';

                            //close connection to MySQL
                            close($link);
                        } elseif ((isset($_POST['delete']))) {
                            $itemId = filter_input(INPUT_GET, 'itemId');

                            //connecting to MySQL and selecting shopper database
                            $link = connect();

                            //calling our create_list function
                            $status = delete_item($link, $itemId);

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

