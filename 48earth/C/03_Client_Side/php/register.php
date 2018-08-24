<?php
    session_start();
    error_reporting(E_ALL &~ E_NOTICE);
    date_default_timezone_set("Asia/Taipei");

    $mysqli = new mysqli('localhost', 'admin', '1234', '48_c_02');
    $mysqli->query('set names `utf8`');

    $name = $_POST['name'] ?? null;
    $score = $_POST['score'] ?? null;
    $time = $_POST['time'] ?? null;

    $mysqli->query("INSERT INTO `score` (`name`, `score`, `time`) VALUES ('$name', '$score', '$time')");
    
    $data = $mysqli->query("SELECT * FROM `score` ORDER BY `score` DESC");
    $data = mysqli_fetch_all($data);
    echo json_encode($data);