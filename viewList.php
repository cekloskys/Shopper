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
                        echo '<li><a href="emailList.php?listId=' . $listId . '">Email List</a></li>';
                        ?>                         
                    </ol>

                    <!-- Page Heading -->
                    <h1>Shopper - Shopping Lists</h1>
                    <hr>                                                           

                    <!-- Shopping List Table -->
                    <?php
                    /* if ($_SESSION['userid'] != 0) {

                        include 'dbHandler.php';

                        $listid = filter_input(INPUT_GET, 'listId');

                        $link = connect();

                        $status = select_list_name($link, $listid);

                        echo '<h3>Here are the items on ' . $status . '</h3>';

                        close($link);
                    } */
                    ?>
                    <h3>Here are the items on your shopping list ...</h3>
                    <div class="well">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Price</th> 
                                    <th>Quantity</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //check if the user session variable is not
                                //equal to zero - this means we have a valid
                                //user logged into Shopper
                                if ($_SESSION['userid'] != 0) {

                                    include 'dbHandler.php';
                                    $listId = filter_input(INPUT_GET, 'listId');
                                    $link = connect();

                                    $status = select_list_items($link, $listId, 0);

                                    echo $status;

                                    $status = select_list_total_cost($link, $listId);

                                    echo '<tr>';
                                    echo '<td> </td>';
                                    echo '<td> </td>';
                                    echo '<th scope="row">' . $status . '</th>';
                                    echo '<td> </td>';
                                    echo '</tr>';

                                    close($link);
                                }
                                ?>
                            </tbody>
                        </table>
                        <form name="deleteList" id="deleteList" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                            <div class="form-group">                                                                         
                                <button name="delete" type="submit" class="btn btn-danger">Delete</button>
                                <?php
                                if (isset($_POST['delete'])) {

                                    $listid = filter_input(INPUT_GET, 'listId');

                                    $link = connect();

                                    $status = delete_list($link, $listid);
                                    
                                    echo '<p class="help-block">' . $status . '</p>';
                                    
                                    close($link);
                                }
                                ?>
                            </div>
                        </form>
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

