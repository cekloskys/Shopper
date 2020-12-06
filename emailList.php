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
                    <h1>Shopper - Email Shopping List</h1>
                    <hr>

                    <!-- Add Item To Shopping List Form -->
                    <?php
                    if ($_SESSION['userid'] != 0) {

                        include 'dbHandler.php';

                        $listid = filter_input(INPUT_GET, 'listId');

                        $link = connect();

                        $status = select_list_name($link, $listid);

                        echo '<h3>Email ' . $status . '</h3>';

                        close($link);
                    }
                    ?>     
                    <div class="well">
                        <form name="emailList" id="emailList" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">                                                                                   
                            <div class="form-group">
                                <label for="emailAddress">Email Address</label>
                                <input name="emailAddress" type="email" class="form-control" id="emailAddress" placeholder="Enter an email address" required="">
                                <p class="help-block">Make sure you use a valid email address</p>
                            </div>
                            <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <?php
                        //check to see if the submit button was pushed
                        if ((isset($_POST['submit']))) {

                            if ($_SESSION['userid'] != 0){
                                
                                include 'class.phpmailer.php';
                                
                                $mail = new PHPMailer();
                                
                                //set values in our objects fields
                                //that allow us to send an email using
                                //Gmail's SMTP server
                                $mail->SMTPAuth = true;
                                $mail->SMTPSecure = "ssl";
                                $mail->Host = "smtp.gmail.com";
                                $mail->Port = 465;
                                $mail->Mailer = "smtp";
                                
                                //use your Gmail username and password
                                $mail->Username = "maryannsummer18@gmail.com";
                                $mail->Password = "summeristhebestseason";
                                
                                //set up the From portion of the email
                                $mail->From = "maryannsummer18@gmail.com";
                                $mail->FromName = "Shopper";
                                
                                $listId = filter_input(INPUT_GET, 'listId');
                                $link = connect();
                                
                                //set up the Subject of the email
                                $mail->Subject = "Here are the items on " .
                                select_list_name($link, $listid);
                                
                                //set up the Body of the email
                                $mail->Body = '<table id="shopper" width="100%" height="100%" style="border-spacing: 0; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background-color: #f2f2f2;">
                                            <th style="border-bottom: 2px solid #ddd; padding: 10px; text-align: left">#</th>
                                            <th style="border-bottom: 2px solid #ddd; padding: 10px; text-align: left">Name</th>
                                            <th style="border-bottom: 2px solid #ddd; padding: 10px; text-align: left">Price</th>
                                            <th style="border-bottom: 2px solid #ddd; padding: 10px; text-align: left">Quantity</th>
                                        </tr>
                                    </thead>
                                <tbody>';
                                
                                //this code builds the table rows
                                $mail->Body .= select_list_items($link, $listId, 1);
                                
                                $status = select_list_total_cost($link, $listId);
                                
                                //this code builds the last table row that displays the total cost
                                $mail->Body .= '<tr>
                    <td style="padding: 10px; text-align: left"> </td>
                    <td style="padding: 10px; text-align: left"> </td>
                    <td style="padding: 10px; text-align: left"><strong>' . $status . '</strong></td>
                    <td style="padding: 10px; text-align: left"> </td>
                </tr>
            </tbody>
        </table>';
                                
                                close($link);
                                
                                // specify who email is going to
                                $mail->AddAddress($_POST['emailAddress'], "Shopper Contact");
                                // specify that email is an HTML style email
                                $mail->IsHTML(true);
                                
                                //send email
                                if(!$mail->Send()){
                                    echo '<p class="help-block">Mail Error : ' . $mail->ErrorInfo . '</p>';
                                } else {
                                    echo '<p class="help-block">Email Sent!</p>';
                                }
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

