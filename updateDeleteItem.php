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
                    <h1>Shopper - Update/Delete Shopping List Item</h1>
                    <hr>

                    <!-- Create Shopping List Item Form -->
                    <?php
                    if ($_SESSION['userid'] != 0) {

                        include 'dbHandler.php';

                        $listItemId = filter_input(INPUT_GET, 'listItemId');

                        $link = connect();

                        $row = select_list_item_name_and_quantity($link, $listItemId);

                        echo '<h3>Update/Delete Item ' . $row['name'] . '</h3>';

                        close($link);
                    }
                    ?>      
                    <div class="well">
                        <form name="updateDeleteItem" id="updateDeleteItem" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">                           
                            <div class="form-group">
                                <label for="itemName">Name</label>
                                <input name="itemName" type="text" class="form-control" readonly="readonly" disabled="disabled" id="itemName" required="" maxlength="100"
                                <?php
                                //check if the user session variable is not
                                //equal to zero - this means we have a valid
                                //user logged into Shopper
                                if ($_SESSION['userid'] != 0) {

                                    $listItemId = filter_input(INPUT_GET, 'listItemId');

                                    $link = connect();

                                    $row = select_list_item_name_and_quantity($link, $listItemId);

                                    echo ' value="' . $row['name'] . '"';

                                    close($link);
                                }
                                ?>
                                       >
                            </div>
                            <div class="form-group">
                                <label for="itemQuantity">Quantity</label>
                                <input name="itemQuantity" type="number" step="1" min="1" class="form-control" id="itemPrice" required=""
                                <?php
                                //check if the user session variable is not
                                //equal to zero - this means we have a valid
                                //user logged into Shopper
                                if ($_SESSION['userid'] != 0) {

                                    $listItemId = filter_input(INPUT_GET, 'listItemId');

                                    $link = connect();

                                    $row = select_list_item_name_and_quantity($link, $listItemId);

                                    echo ' value="' . $row['quantity'] . '"';

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
                            
                            $itemQuantity = filter_input(INPUT_POST, 'itemQuantity');

                            $listItemId = filter_input(INPUT_GET, 'listItemId');

                            //connecting to MySQL and selecting shopper database
                            $link = connect();

                            //calling our create_list function
                            $status = update_item_quantity($link, $listItemId, $itemQuantity);

                            //output an html paragraph that contains the return
                            //from our create_list function
                            echo '<p class="help-block">' . $status . '</p>';

                            //close connection to MySQL
                            close($link);
                        } elseif ((isset($_POST['delete']))) {
                            $listItemId = filter_input(INPUT_GET, 'listItemId');

                            //connecting to MySQL and selecting shopper database
                            $link = connect();

                            //calling our create_list function
                            $status = delete_item_from_list($link, $listItemId);

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

