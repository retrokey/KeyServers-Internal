<?php

class Rcon{
    private $ip;
    private $port;
    private $rconPass;

    public function __construct($i, $p, $r){
        $this->ip = $i;
        $this->port = $p;
        $this->rconPass = $r;
    }

    public function openSocket(){
        $tcpsocket = stream_socket_client("tcp://$this->ip:$this->port", $errno, $errorMessage);
        if ($tcpsocket === false) return null;
        else return $tcpsocket;
    }

    public function send($tcpsocket, $command){
        fwrite($tcpsocket, $command);
        $response = fread($tcpsocket, 8889);
        fclose($tcpsocket);
        return $response;
    }

    public function mutePlayer($username, $time){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "MUTE|$username|$time|$this->rconPass") == "Error") return false;
        else return true;
    }

    public function sendGlobalAlert($alert){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "GLOBALALERT|$alert|$this->rconPass") == "Error") return false;
        else return true;
    }

    public function sendAlert($username, $alert){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "ALERT|$username|$alert|$this->rconPass") == "Error") return false;
        else return true;
    }

    public function giveBadge($username, $badge){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "GIVEBADGE|$username|$badge|$this->rconPass") == "Error") return false;
        else return true;
    }

    public function giveCoin($username, $coin, $quantity){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "GIVECOIN|$username|$coin|$quantity|$this->rconPass") == "Error") return false;
        else return true;
    }

    public function refresh($type){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "REFRESH|$type|$this->rconPass") == "Error") return false;
        else return true;
    }

    public function goToRoom($username, $roomid){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "GOTOROOM|$username|$roomid|$this->rconPass") == "Error") return false;
        else return true;
    }

    public function needHelp($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "NEEDHELP|$username|$this->rconPass") == "Error") return false;
        else return true;
    }

    public function buyBadge($badge, $username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "BUYBADGE|$username|$badge|$this->rconPass");
    }

    public function openFloor($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "OPENFLOOR|$username|$this->rconPass");
    }

    public function reloadRoom($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "RELOADROOM|$username|$this->rconPass");
    }

    public function autoFloor($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "AUTOFLOOR|$username|$this->rconPass");
    }

    public function maxFloor($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "MAXFLOOR|$username|$this->rconPass");
    }

    public function alturaFloor($username, $value){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "ALTURAFLOOR|$username|$value|$this->rconPass");
    }

    public function rotacionFloor($username, $value){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "ROTACIONFLOOR|$username|$value|$this->rconPass");
    }

    public function estadoFloor($username, $value){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "ESTADOFLOOR|$username|$value|$this->rconPass");
    }

    public function openBuilderTool($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "OPENBUILDERTOOL|$username|$this->rconPass");
    }

    public function buyGalaxyPass($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "BUYGALAXYPASS|$username|$this->rconPass");
    }

    public function openGalaxyPassEmu($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "OPENGALAXYPASS|$username|$this->rconPass");
    }

    public function getInfoOpenGalaxyPassEmu($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "GETINFOOPENGALAXYPASS|$username|$this->rconPass");
    }

    public function betDadosCasino($username, $quantity, $coin, $number){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "BETDADOSCASINO|$username|$quantity|$coin|$number|$this->rconPass");
    }

    public function betRuletaSuelo($username, $quantity, $coin, $number){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "BETRULETANORMAL|$username|$quantity|$coin|$number|$this->rconPass");
    }

    public function betRuletaPared($username, $quantity, $coin){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "BETRULETACOLORES|$username|$quantity|$coin|$this->rconPass");
    }
}

?>