<?php
header("Access-Control-Allow-Origin: *");
require_once "function.php";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $url = $_SERVER['REQUEST_URI'];
    $url = trim($url, "\/");
    $url = explode( "/" , $url);
    $action = $url[1];
    if($action =="getuserlist"){
        getListUser();

    }
    echo json_encode([
        "status" => 200,
        "data" => $url
    ]);
} else {
    // // ce que lutilisateur envoi via un formulaire on recupere

    $data = json_decode(file_get_contents("php://input"), true);


    if ($data["action"] == "login") {
        // appel de la fonction login
        login($data['pseudo'], $data['password']);
    } else if ($data["action"] == "register") {
        // on fait appel a la fonction register pour enregistrer le user
        register($data['firstname'], $data['lastname'], $data['pseudo'], $data['password']);
    } else if ($data["action"] == "send message") {
        // appel de la fonction sendMessage
        sendMessage($data['expeditor'], $data['receiver'], $data['message']);
    } else {
        echo json_encode([
            "satus" => 404,
            "message" => "service not found"
        ]);
    }
}