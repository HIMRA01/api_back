<?php
function dbConnect()
{
    $conn = null;
    try {
        $conn = new PDO("mysql:host=localhost;dbname=api_back", "root", "");

    } catch (PDOException $e) {
        $conn = $e->getMessage();
    }
    return $conn;


}

// function pour enregistrer un utilisateur
function register($firstName, $lastName, $pseudo, $password)
{
    // crypter le mot de passe
    $passwordCrypt = password_hash($password, PASSWORD_DEFAULT);
    // connexion a la bd
    $db = dbConnect();
    // prepare la request
    $request = $db->prepare("INSERT INTO user (pseudo,firstname, lastname, password) VALUES (?,?,?,?)");
    //execuet
    try {
        $request->execute(array($pseudo, $firstName, $lastName, $passwordCrypt));
        echo json_encode([
            "status" => 201,
            "message" => "everything good",
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "status" => 500,
            "message" => "internal server error",
        ]);
    }
}

// fonction pour se connecter 
function login($pseudo, $password)
{
    //se connecter a la bd
    $db = dbConnect();
    //requete pour se connecter 
    $request = $db->prepare("SELECT * FROM user WHERE pseudo=?");
    // executer la requete
    try {
        $request->execute(array($pseudo));
        // recuperer la reponse de la requete
        $user = $request->fetch(PDO::FETCH_ASSOC);
        // verifier si l'utilisateur existe
        if (empty($user)) {
            echo json_encode([
                "status" => 404,
                "message" => "user not found"
            ]);
        } else {
            // verifier si le password est correct
            if (password_verify($password, $user['password'])) {
                echo json_encode([
                    "status" => 200,
                    "message" => "felicitation...",
                    "data" => $user
                ]);
            }else{
                echo json_encode([
                    "status" => 400,
                    "message" => "mot de passe incorrect",
                ]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode([
            "status" => 500,
            "message" => $e->getMessage()
        ]);
    }
}

// function pour envoyer un mesage
function sendMessage($expeditor, $receiver, $message){


    $db = dbConnect();

    $request = $db->prepare("INSERT INTO message(message, expeditor_id, receiver_id) VALUES (?,?,?)");

    try{
        $request->execute(array($message,$expeditor,$receiver));
        echo json_encode([
            "status" => 201,
            "message" => "your message is safely sent..."

        ]);
    }catch(PDOException $e){
        echo json_encode([
            "status" => 500,
            "message" => $e->getMessage()


        ]);


    }
}


// function pour recupere
function getListUser(){

    $db = dbConnect();

    $request = $db->prepare("SELECT* FROM user");
    try{
        $request->execute();

        $listUser = $request->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            "status" => 200,
            "message" => "your message is safely sent...",
            "data" => $listUser

        ]);

    } catch (PDOException $e) {
        echo json_encode([
            "status" => 500,
            "message" => $e->getMessage()


        ]);


    }

}