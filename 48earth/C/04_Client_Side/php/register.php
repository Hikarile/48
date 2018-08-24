<?php
    error_reporting(E_ALL &~ E_NOTICE);
    date_default_timezone_set('Asia/Taipei');

    $mysqli = new mysqli('localhost','admin', '1234', '48_c_04');
    $mysqli->query('set names `utf8`');

    $name = $_POST['name'];
    $score = $_POST['score'];
    $time = $_POST['time'];
    
    $mysqli->query("INSERT INTO `score` (`name`, `score`, `time`) VALUES ('$name', '$score', '$time')");

    $data = $mysqli->query("SELECT * FROM `score` ORDER BY `score` DESC");
    $data = mysqli_fetch_all($data);

    echo json_encode($data);