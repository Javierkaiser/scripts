<?php
require 'db.php';

$db = db_connect();

//function modify transaction dates, setting time in ascending order per day
//date is in datetime format
//no paramerters

function modify_transaction_dates($db){
    $transactions = get_transactions($db);
    $start_time = '09:00:00';
    $end_time = '18:00:00';
    $start_date = '2021-01-01';
    $end_date = '2021-11-28';
    $current_date = '';
    $current_time = '';
    $limit = 44;
    $counter = 0;

    foreach ($transactions as $index => $transaction) {
        if ($current_date == '') {
            $current_date = $start_date;
            $current_time = $start_time;
        } elseif ($current_time > $end_time && $current_date < $end_date) {
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
            $current_time = $start_time;
        }
        if ($counter == $limit) {
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
            $current_time = $start_time;
            $counter = 0;
        }
        if (date('d', strtotime($current_date)) > '5'&& $current_date < $end_date) {
            $current_date = date('Y-m', strtotime($current_date . ' +1 month')) . '-01';
        }
        $counter++;

        $created_time = date('Y-m-d H:i:s', strtotime($current_date . ' ' . $current_time));
        $updated_date = date('Y-m-d', strtotime($current_date . ' ' . $current_time));
        $current_time = date('H:i:s', strtotime($current_time . ' +5 minutes'));

        $transactions[$index]['created'] = $created_time;
        $transactions[$index]['transaction_date'] = $updated_date;
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
        $sql = "UPDATE tbl_transaction SET created = '{$transaction['created']}', transaction_date = '{$transaction['transaction_date']}'  WHERE transaction_id = ".$transaction['transaction_id'];
        $results[] = mysqli_query($db, $sql);
    }
    return $results;
}

modify_transaction_dates($db);
?>