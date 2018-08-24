<?php
    session_start();
    error_reporting(E_ALL &~ E_NOTICE);
    date_default_timezone_set("Asia/Taipei");

    $mysqli = new mysqli('localhost', 'admin', '1234', '48_c_01');
    $mysqli->query('set names `utf8`');


    $name = $_POST['name'] ?? null;
    $score = $_POST['score'] ?? null;
    $time = $_POST['time'] ?? null;

    if (empty($name)) {
        echo json_encode(['error' => 'Name field cannot be blank.']);
        return false;
    }
    
    if (empty($time) && $time != '0') {
        echo json_encode(['error' => 'Time field cannot be blank.']);
        return false;
    }
    
    if (empty($score) && $score != '0') {
        echo json_encode(['error' => 'Score field cannot be blank.']);
        return false;
    }

    $mysqli->query("INSERT INTO `score` (`name`, `score`, `time`) VALUES ('$name', '$score', '$time')");
    
    $data = $mysqli->query("SELECT * FROM `score` ORDER BY `score` DESC");
    $data = mysqli_fetch_all($data);
    echo json_encode($data);