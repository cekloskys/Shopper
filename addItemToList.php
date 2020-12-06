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
                        <?php
                        $listId = filter_input(INPUT_GET, 'listId');

                        echo '<li><a href="addItemToList.php?listId=' . $listId . '">Add Items</a></li>';
                        ?>
                    </ol>

                    <!-- Page Heading -->
                    <h1>Shopper - Add Item To Shopping List</h1>
                    <hr>

                    <!-- Add Item To Shopping List Form -->
                    <?php
                    if ($_SESSION['userid'] != 0) {

                        include 'dbHandler.php';

                        $listid = filter_input(INPUT_GET, 'listId');

                        $link = connect();

                        $status = select_list_name($link, $listid);

                        echo '<h3>Add Item To ' . $status . '</h3>';

                        close($link);
                    }
                    ?>     
                    <div class="well">
                        <form name="addItem" id="addItem" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">                                                       
                            <div class="form-group">                                
                                <label for="itemId">Name</label>
                                <select name="itemId" id="itemId" class="form-control">
                                    <?php
                                    //check if the user session variable is not
                                    //equal to zero - this means we have a valid
                                    //user logged into Shopper
                                    if ($_SESSION['userid'] != 0) {

                                        $userid = $_SESSION['userid'];

                                        $link = connect();

                                        $status = select_items_to_add($link, $userid);
                                        
                                        echo $status;

                                        close($link);
                                    }
                                    ?>
                                </select>
                            </div>  
                            <div class="form-group">
                                <label for="itemQuantity">Quantity</label>
                                <input name="itemQuantity" type="number" step="1" min="0" class="form-control" id="itemQuantity" placeholder="Enter a quantity" required="">
                            </div>
                            <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <?php
                        //check to see if the submit button was pushed
                        if ((isset($_POST['submit']))) {

                            //getting data input into the form and assigning
                            //it to variables
                            $itemId = filter_input(INPUT_POST, 'itemId');
                            $itemQuantity = filter_input(INPUT_POST, 'itemQuantity');

                            //getting the list id
                            $listId = filter_input(INPUT_GET, 'listId');

                            //connecting to MySQL and selecting shopper database
                            $link = connect();

                            //calling our create_list function
                            $status = create_list_item($link, $listId, $itemId, $itemQuantity);

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

