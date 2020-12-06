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
                    <h1>Shopper - Add Picture To Shopping List Item</h1>
                    <hr>

                    <!-- Create Shopping List Item Form -->
                    <?php
                    if ($_SESSION['userid'] != 0) {

                        include 'dbHandler.php';

                        $itemId = filter_input(INPUT_GET, 'itemId');

                        $link = connect();

                        $status = select_item_name($link, $itemId);

                        echo '<h3>Add Picture To Item ' . $status . '</h3>';

                        close($link);
                    }
                    ?>      
                    <div class="well">
                        <form name="AddPicture" id="AddPicture" enctype="multipart/form-data" 
                              method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                            <div class="form-group">
                                <label for="userfile">Picture</label>
                                <input name="userfile" type="file" class="form-control" 
                                       id="userfile" required="" value="">
                            </div>                                                                             
                            <button name="submit" type="submit" class="btn btn-primary">Submit</button>                            
                        </form>
                        <?php
                        //check to see if the update button was pushed
                        if ((isset($_POST['submit']))) {
               
                            // get pertinent information about the file
                            // being uploaded
                            $fileName = $_FILES['userfile']['name'];
                            $fileType = $_FILES['userfile']['type'];
                            $fileTempName = $_FILES['userfile']['tmp_name'];
                            
                            // get item id of shopping list item
                            $itemId = filter_input(INPUT_GET, 'itemId');
                            
                            // check file type to make sure it's an image
                            if (($fileType == "image/gif") ||
                                    ($fileType == "image/jpeg") ||
                                    ($fileType == "image/jpg") ||
                                    ($fileType == "image/png")){
                                
                                // set up target path at which the file will exist
                                // after it's uploaded
                                $target_path = "C:/wamp/www/uploads/" . $fileName;
                                
                                // move file
                                if (move_uploaded_file($fileTempName, $target_path)){
                                    
                                    // update the name of the file in the picture
                                    // column on the item table
                                    $link = connect();
                                    
                                    $status = update_item_picture($link, $itemId, $fileName);
                                    
                                    echo '<p class="help-block">' . $status . '</p>';
                                    
                                    close($link);
                                } else {
                                    // display error message if upload fails
                                    $fileError = $_FILES['userfile']['error'];
                                    $status = 'Error uploading file : ' . $fileError;
                                    echo '<p class="help-block">' . $status . '</p>';
                                }
                            } else {
                                echo '<p class="help-block">Invalid File Type!</p>';
                            }
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

