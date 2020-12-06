<?php

function connect() {

    //set up variables used to connect to MySQL
    /* $dbhost = 'localhost:3306';
    $dbuser = 'root';
    $dbpass = ''; */
    
    $dbhost = 'us-cdbr-east-02.cleardb.com';
    $dbuser = 'b335958df4a91d';
    $dbpass = '1e1e9f7e';

    //connect to MySQL
    $link = mysqli_connect($dbhost, $dbuser, $dbpass);
    if (!$link) {
        die('Could not connect to MySQL:' . mysqli_errno($link));
    }

    //select shopper database
    $retval = mysqli_select_db($link, 'heroku_fea41581d968989');
    if (!$retval) {
        die('Could not select database:' . mysqli_errno($link));
    }

    return $link;
}

function close($link) {

    //close connection to MySQL
    $retval = mysqli_close($link);
    if (!$retval) {
        die('Could not close connection:' . mysqli_errno($link));
    }
}

function create_user($link, $userName, $password, $emailAddress) {

    //encrypt password
    //$password = password_hash($password, PASSWORD_DEFAULT);

    //create INSERT INTO statement to insert data into the user table
    $sql = "INSERT INTO user (username, password, email) "
            . "VALUES ('$userName', '$password', '$emailAddress')";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute insert statement:' . mysqli_errno($link));
    } else {
        return 'Account Created!';
    }
}

function verify_user($link, $userName) {

    //create SELECT statement to retrieve data from the user table
    $sql = "SELECT username "
            . "FROM user "
            . "WHERE username = '$userName'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        if (mysqli_num_rows($retval) == 0) {
            return 0;
        } else {
            return 1;
        }
    }
}

function select_user($link, $userName, $password) {

    $userid = 0;

    //create SELECT statement to retrieve data from the user table
    $sql = "SELECT id, username, password "
            . "FROM user "
            . "WHERE username = '$userName'";


    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {

            /* if ((strcmp($row['username'], $userName) == 0) &&
                    (strcmp($row['password'], $password) == 0)) {*/
                $userid = $row['id'];
                return $userid;
            /* } else {
                return $userid;
            } */
        }
    }
}

function create_list($link, $listName, $listStore, $listDate, $userid) {

    //escape special characters from name and store
    $listName = mysqli_real_escape_string($link, $listName);
    $listStore = mysqli_real_escape_string($link, $listStore);

    //create INSERT INTO statement to insert data into the list table
    $sql = "INSERT INTO list (name, store, date, userid) "
            . "VALUES ('$listName', '$listStore', '$listDate', '$userid')";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute insert statement:' . mysqli_errno($link));
    } else {
        return 'Shopping List Created!';
    }
}

function select_lists($link, $userid) {

    $i = 1;
    $status = '';

    //create SELECT statement to retrieve data from the list table
    $sql = "SELECT id, name, store, date "
            . "FROM list "
            . "WHERE userid = '$userid'";


    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        //processing rows returned from the SELECT statement
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {

            //formatting table rows and returning them
            $status .= '<tr>';
            $status .= '<td scope="row">' . $i . '</td>';
            
            $status .= '<td><a href="viewList.php?listId=' 
                    . $row['id'] . '">' . $row['name'] . '</a></td>';
            
            $status .= '<td>' . $row['store'] . '</td>';
            $status .= '<td>' . $row['date'] . '</td>';
            $status .= '</tr>';

            $i++;
        }

        return $status;
    }
}

function create_item($link, $itemName, $itemPrice, $userid) {

    //escape special characters from name
    $itemName = mysqli_real_escape_string($link, $itemName);

    //create INSERT INTO statement to insert data into the item table
    $sql = "INSERT INTO item (name, price, userid) "
            . "VALUES ('$itemName', '$itemPrice', '$userid')";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute insert statement:' . mysqli_errno($link));
    } else {
        return 'Shopping List Item Created!';
    }
}

/* function select_items($link, $userid) {

  $i = 1;
  $status = '';

  //create SELECT statement to retrieve data from the item table
  $sql = "SELECT id, name, price "
  . "FROM item "
  . "WHERE userid = '$userid'";


  //execute query
  $retval = mysqli_query($link, $sql);
  if (!$retval) {
  die('Could not execute select statement:' . mysqli_errno($link));
  } else {
  //processing rows returned from the SELECT statement
  while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {

  $price = sprintf("%.2f", $row['price']);

  //formatting table rows and returning them
  $status .= '<tr>';
  $status .= '<td scope="row">' . $i . '</td>';
  $status .= '<td><a href="updateDeleteShoppingListItem.php?itemId=' . $row['id'] . '">' . $row['name'] . '</a></td>';
  $status .= '<td>' . $price . '</td>';
  $status .= '</tr>';

  $i++;
  }

  return $status;
  }
  } */

function select_items($link, $userid, $target_path) {

    $status = '';
    $price = 0.0;
    $i = 1;

    //create SELECT statement to retrieve data from the item table
    $sql = "SELECT id, name, price, picture "
            . "FROM item "
            . "WHERE userid = '$userid'";


    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        //processing rows returned from the SELECT statement
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {

            $price = sprintf("%.2f", $row['price']);

            //formatting table rows and returning them
            $status .= '<tr>';
            $status .= '<td scope="row">' . $i . '</td>';
            $status .= '<td><a href="updateDeleteShoppingListItem.php?itemId=' . $row['id'] . '">' . $row['name'] . '</a></td>';
            $status .= '<td>' . $price . '</td>';
            // if the shopping list item has a picture associated with it
            // in the database, format and display it; else display an
            // empty table data element
            if (strlen($row['picture']) != 0) {
                $status .= '<td><div class="col-sm-2"><img class="thumbnail img-responsive" src="'
                        . $target_path . $row['picture'] . '"></div></td>';
            } else {
                $status .= '<td> </td>';
            }
            $status .= '</tr>';

            $i++;
        }

        return $status;
    }
}

function select_list_name($link, $listid) {

    $status = '';

    //create SELECT statement to retrieve data from the list table
    $sql = "SELECT name "
            . "FROM list "
            . "WHERE id = '$listid'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        //processing rows returned from the SELECT statement
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
            $status = $row['name'];
        }

        return $status;
    }
}

function select_items_to_add($link, $userid) {

    $status = '';

    //create SELECT statement to retrieve data from the item table
    $sql = "SELECT id, name, price "
            . "FROM item "
            . "WHERE userid = '$userid'";


    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        //processing rows returned from the SELECT statement
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {

            $price = sprintf("%.2f", $row['price']);

            //formatting table rows and returning them
            
            $status .= '<option value="' . $row['id'] . '">'
                    . $row['name'] . ' $' . $price
                    . '</option>';
        }

        return $status;
    }
}

function create_list_item($link, $listId, $itemId, $itemQuantity) {

    //create INSERT INTO statement to insert data into the list_item table
    $sql = "INSERT INTO list_item (list_id, item_id, quantity) "
            . "VALUES ('$listId', '$itemId', '$itemQuantity')";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute insert statement:' . mysqli_errno($link));
    } else {
        return 'Item Added To Shopping List!';
    }
}

function select_list_items($link, $listId, $flag) {

    $status = '';

    //create SELECT statement to retrieve data from the
    //item and list_item tables
    $sql = "SELECT list_item.id, list_id, item_id, quantity, name, price "
            . "FROM list_item "
            . "INNER JOIN item "
            . "ON item_id = item.id "
            . "AND list_id = $listId";

    //execute the SELECT statement
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_error($link));
    } else {
        if ($flag == 0) {
            $status = format_list_items_web($retval, $listId);
            return $status;
        } else {
            $status = format_list_items_email($retval);
            return $status;
        }
    }
}

function format_list_items_web($retval, $listId) {

    $i = 1;
    $status = '';

    //process the rows returned from the SELECT statement
    while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {

        $id = $row['id'];
        $price = sprintf("%.2f", $row['price']);

        //format the table rows for the web
        $status .= '<tr>';
        $status .= '<th scope="row">' . $i . '</th>';
        $status .= '<td>' . '<a href="updateDeleteItem.php?listItemId=' . $id . '&listId=' . $listId . '">' . $row['name'] . '</a>' . '</td>';
        $status .= '<td>' . $price . '</td>';
        $status .= '<td>' . $row['quantity'] . '</td>';
        $status .= '</tr>';

        $i++;
    }

    return $status;
}

function format_list_items_email($retval) {

    $i = 1;
    $status = '';

    //process the rows returned from the SELECT statement
    while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {

        $price = sprintf("%.2f", $row['price']);

        //make the even numbered rows grey and make the odd
        //numbered rows white
        if($i % 2 == 0){
            $status .= '<tr style="background-color: #f2f2f2">';
        } else {
            $status .= '<tr>';
        }

        $status .= '<td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: left">' . $i . '</td>
                    <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: left">' . $row['name'] . '</td>
                    <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: left">' . $price . '</td>
                    <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: left">' . $row['quantity'] . '</td>
                </tr>';
        $i++;
    }

    return $status;
}

function select_list_total_cost($link, $listId) {

    $cost = 0.00;

    //create SELECT statement to retrieve data from the
    //item and list_item tables
    $sql = "SELECT quantity, price "
            . "FROM list_item "
            . "INNER JOIN item "
            . "ON item_id = item.id "
            . "AND list_id = '$listId'";

    //execute the SELECT statement
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_error($link));
    } else {
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
            $cost += $row['quantity'] * $row['price'];
        }

        $cost = sprintf("%.2f", $cost);
        return $cost;
    }
}

function select_item_name($link, $itemId) {

    $name = '';

    //create SELECT statement to retrieve data from the item table
    $sql = "SELECT name "
            . "FROM item "
            . "WHERE id = '$itemId'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        //processing rows returned from the SELECT statement
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
            $name = $row['name'];
        }

        return $name;
    }
}

function select_item_price($link, $itemId) {

    $price = 0.00;

    //create SELECT statement to retrieve data from the item table
    $sql = "SELECT price "
            . "FROM item "
            . "WHERE id = '$itemId'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        //processing rows returned from the SELECT statement
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
            $price = sprintf("%.2f", $row['price']);
        }

        return $price;
    }
}

function update_item($link, $itemId, $itemName, $itemPrice) {

    $itemName = mysqli_real_escape_string($link, $itemName);

    $sql = "UPDATE item "
            . "SET name = '$itemName', "
            . "price = '$itemPrice' "
            . "WHERE id = '$itemId'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute update statement:' . mysqli_errno($link));
    } else {
        return 'Item Updated!';
    }
}

function delete_item($link, $itemId) {

    $sql = "DELETE FROM item "
            . "WHERE id = '$itemId'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute delete item statement:' . mysqli_errno($link));
    }

    $sql = "DELETE FROM list_item "
            . "WHERE item_id = '$itemId'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute delete list_item statement:' . mysqli_errno($link));
    }

    return 'Item Deleted!';
}

function select_list_item_name($link, $listItemId) {

    $name = '';

    //create SELECT statement to retrieve data from the item table
    $sql = "SELECT name "
            . "FROM item "
            . "INNER JOIN list_item "
            . "ON item_id = item.id "
            . "AND list_item.id = '$listItemId'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        //processing rows returned from the SELECT statement
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
            $name = $row['name'];
        }

        return $name;
    }
}

function select_list_item_quantity($link, $listItemId) {

    $quantity = 0;

    //create SELECT statement to retrieve data from the item table
    $sql = "SELECT quantity "
            . "FROM item "
            . "INNER JOIN list_item "
            . "ON item_id = item.id "
            . "AND list_item.id = '$listItemId'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        //processing rows returned from the SELECT statement
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
            $quantity = $row['quantity'];
        }

        return $quantity;
    }
}

function select_list_item_name_and_quantity($link, $listItemId) {

    //create SELECT statement to retrieve data from the item 
    //and list_item tables
    $sql = "SELECT name, quantity "
            . "FROM item "
            . "INNER JOIN list_item "
            . "ON item_id = item.id "
            . "AND list_item.id = '$listItemId'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute select statement:' . mysqli_errno($link));
    } else {
        //processing rows returned from the SELECT statement
        $row = mysqli_fetch_array($retval, MYSQLI_ASSOC);
        return $row;
    }
}

function update_item_quantity($link, $listItemId, $itemQuantity) {



    //create SELECT statement to retrieve data from the item table
    $sql = "UPDATE list_item "
            . "SET quantity = '$itemQuantity' "
            . "WHERE id = '$listItemId'";


    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute update statement:' . mysqli_errno($link));
    } else {

        return 'Item Updated!';
    }
}

function delete_item_from_list($link, $listItemId) {

    // Delete from list_item
    $sql = "DELETE FROM list_item WHERE id = '$listItemId'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute delete list_item statement:' . mysqli_errno($link));
    } else {
        return 'Item Deleted!';
    }
}

function delete_list($link, $id) {

    $sql = "DELETE FROM list WHERE id=$id";

    if (!mysqli_query($link, $sql)) {
        die('Could not execute delete list statement:' . mysqli_errno($link));
    }

    $sql = "DELETE FROM list_item WHERE list_id=$id";

    if (!mysqli_query($link, $sql)) {
        die('Could not execute delete list_item statement:' . mysqli_errno($link));
    } else {
        return 'List Deleted!';
    }
}

function update_item_picture($link, $itemId, $fileName) {

    $sql = "UPDATE item "
            . "SET picture = '$fileName' "
            . "WHERE id = '$itemId'";

    //execute query
    $retval = mysqli_query($link, $sql);
    if (!$retval) {
        die('Could not execute update statement:' . mysqli_errno($link));
    } else {
        return $fileName . ' Added!';
    }
}
?>

