<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/* Rcon KUBBO API by Compass */
header('Access-Control-Allow-Origin: *');

include_once "Rcon.php";
include_once "Database.php";
include_once "User.php";

session_start();


if(isset($_GET["type"]) && isset($_GET["password"]) && isset($_GET["username"])){
    $rcon = new Rcon("85.215.164.123", 8885, $_GET["password"]);

    if(isset($_SESSION["security_cooldown"])){
        if($_SESSION["security_cooldown"] > time() - 2){
            echo "Security cooldown, try again.";
            exit();
        }
    }

    $_SESSION["security_cooldown"] = time();

    
}
else if(isset($_GET["type"]) && isset($_GET["sso"])){
    if(isset($_SESSION["security_cooldown"])){
        if($_SESSION["security_cooldown"] > time() - 1){
            echo "Security cooldown, try again.";
            exit();
        }
    }

    $_SESSION["security_cooldown"] = time();
    $rcon = new Rcon("85.215.164.123", 8885, "0J34scKaC");
    $user = new Queries($_GET["sso"]);


    $username = $user->getUsername();
    if($username == null){
        echo "ERROR";
        exit();
    }

    if($_GET["type"] == "goToRoom"){
        if(!$rcon->goToRoom($username, isset($_GET["roomId"]))) echo "Error";
        else echo "OK";
        exit();
    }
    
    if(isset($_GET["badgeReclamar"])){
        echo $rcon->buyBadge($_GET["badgeReclamar"], $username);
        exit();
    }
    
    if($_GET["type"] == "reloadRoom"){
        
        echo $rcon->reloadRoom($username);
        exit();
    }
    
    if($_GET["type"] == "autoFloor"){
        
        echo $rcon->autoFloor($username);
        exit();
    }
    
    if($_GET["type"] == "maxFloor"){
        
        echo $rcon->maxFloor($username);
        exit();
    }
    
    if($_GET["type"] == "cambiarAltura"){
        
        echo $rcon->alturaFloor($username, $_GET["altura"]);
        exit();
    }
    
    if($_GET["type"] == "cambiarRotacion"){
        
        echo $rcon->rotacionFloor($username, $_GET["rotacion"]);
        exit();
    }
    
    if($_GET["type"] == "cambiarEstado"){
        
        echo $rcon->estadoFloor($username, $_GET["estado"]);
        exit();
    }
    
    if($_GET["type"] == "builderTool"){
        
        echo $rcon->openBuilderTool($username);
        exit();
    }
    
    if($_GET["type"] == "buyGalaxyPass"){
        
        echo $rcon->buyGalaxyPass($username);
        exit();
    }
    
    if($_GET["type"] == "openGalaxyPassEmu"){
        
        echo $rcon->openGalaxyPassEmu($username);
        exit();
    }
    
    if($_GET["type"] == "getInfoOpenGalaxyPassEmu"){
        
        echo $rcon->getInfoOpenGalaxyPassEmu($username);
        exit();
    }
    
    if($_GET["type"] == "betDadosCasino"){
        
        echo $rcon->betDadosCasino($username, $_GET["quantity"], $_GET["coin"], $_GET["number"]);
        exit();
    }
    
    if($_GET["type"] == "betRuletaSuelo"){
        
        echo $rcon->betRuletaSuelo($username, $_GET["quantity"], $_GET["coin"], $_GET["number"]);
        exit();
    }
    
    if($_GET["type"] == "betRuletaPared"){
        
        echo $rcon->betRuletaPared($username, $_GET["quantity"], $_GET["coin"]);
        exit();
    }

    if($_GET["type"] == "getColors"){
        echo json_encode($user->getColorsBySso($_GET["sso"]));
        exit();
    }
    
    if($_GET["type"] == "setColors"){
        $misc->saveColorsBySso($_GET["colorPrimary"], $_GET["colorText"], $_GET["colorSecondary"], $_GET["sso"]);
        exit();
    }    

    if($_GET["type"] == "changebanner"){
        $user->updateBannerBySso($_GET["banner"], $_GET["sso"]);
        exit();
    }
    
    if($_GET["type"] == "getbanner"){
        echo $user->getBannerByUsername($_GET["username"]);
        exit();
    }

    
}
else echo "Missing parameters.";

?>