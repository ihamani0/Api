<?php


function returnData(array $data , string $message = '' , int $status = 200) {
    return [
        'Message' => $message ,
        'Data' => $data  ,
        'Status' => $status
    ];
}