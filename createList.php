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
                        <li><a href="createList.php">Create List</a></li>                       
                    </ol>

                    <!-- Page Heading -->
                    <h1>Shopper - Create Shopping Lists</h1>
                    <hr>

                    <!-- Create Shopping List Form -->
                    <h3>Create A Shopping List</h3>     
                    <div class="well">
                        <form name="createList" id="createList" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                            <div class="form-group">
                                <label for="listName">Name</label>
                                <input name="listName" type="text" class="form-control" id="listName" placeholder="Enter a name" required="" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label for="listStore">Store</label>
                                <input name="listStore" type="text" class="form-control" id="listStore" placeholder="Enter a store" required="" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label for="listMonth">Month</label>
                                <select name="listMonth">
                                    <option>01</option>
                                    <option>02</option>
                                    <option>03</option>
                                    <option>04</option>
                                    <option>05</option>
                                    <option>06</option>
                                    <option>07</option>
                                    <option>08</option>
                                    <option>09</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                </select>
                                <label for="listDay">Day</label>
                                <select name="listDay">
                                    <option>01</option>
                                    <option>02</option>
                                    <option>03</option>
                                    <option>04</option>
                                    <option>05</option>
                                    <option>06</option>
                                    <option>07</option>
                                    <option>08</option>
                                    <option>09</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                    <option>13</option>
                                    <option>14</option>
                                    <option>15</option>
                                    <option>16</option>
                                    <option>17</option>
                                    <option>18</option>
                                    <option>19</option>
                                    <option>20</option>
                                    <option>21</option>
                                    <option>22</option>
                                    <option>23</option>
                                    <option>24</option>
                                    <option>25</option>
                                    <option>26</option>
                                    <option>27</option>
                                    <option>28</option>
                                    <option>29</option>
                                    <option>30</option>
                                    <option>31</option>
                                </select>
                                <label for="listYear">Year</label>
                                <select name="listYear">
                                    <option>2016</option>
                                    <option>2017</option>                                   
                                </select>
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
                            $listName = $_POST['listName'];
                            $listStore = $_POST['listStore'];
                            $listMonth = $_POST['listMonth'];
                            $listDay = $_POST['listDay'];
                            $listYear = $_POST['listYear'];

                            //format the date
                            $listDate = $listYear . "-" . $listMonth . "-" . $listDay;
                            
                            //get the logged in user's user id
                            $userid = $_SESSION['userid'];

                            //connecting to MySQL and selecting shopper database
                            $link = connect();

                            //calling our create_list function
                            $status = create_list($link, $listName, $listStore, $listDate, $userid);

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

