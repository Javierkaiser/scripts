<?php
require 'db.php';

$db = db_connect();

//function modify transaction dates, setting time in ascending order per day
//date is in datetime format
//no paramerters

function modify_transaction_dates($db){
    $transactions = get_transactions($db);
    $star_time = '09:00:00';
    $current_date = '';
    $current_time = '';

    foreach ($transactions as $index => $transaction) {
        if ($current_date == '') {
            $current_date = $transaction['transaction_date'];
            $current_time = $star_time;
        } elseif ($transaction['transaction_date'] > $current_date) {
            $current_date = $transaction['transaction_date'];
            $current_time = $star_time;
        }
        $created_time = date('Y-m-d H:i:s', strtotime($current_date . ' ' . $current_time));
        $current_time = date('H:i:s', strtotime($current_time . ' +2 minutes'));

        $transactions[$index]['created'] = $created_time;
    }
    $results = update_transaction_dates($db, $transactions);
    print_r($results);
}

function get_transactions($db){
    $sql = "SELECT * FROM tbl_transaction";
    $result = mysqli_query($db, $sql);
    $transactions = array();
    while($row = mysqli_fetch_assoc($result)){
        $transactions[] = $row;
    }
    return $transactions;
}

function update_transaction_dates($db, $transactions){
    $results = [];
    foreach($transactions as $transaction){
        $sql = "UPDATE tbl_transaction SET created = '".$transaction['created']."' WHERE transaction_id = ".$transaction['transaction_id'];
        $results[] = mysqli_query($db, $sql);
    }
    return $results;
}

modify_transaction_dates($db);
?>