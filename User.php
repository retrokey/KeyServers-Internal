<?php

class Sticker{
    public $name;
    public $url;

    public function __construct($name, $url){
        $this->name = $name;
        $this->url = $url;
    }
}

class MarketplaceItem{
    public $name;
    public $furniName;
    public $price;

    public function __construct($name, $furniName, $price){
        $this->name = $name;
        $this->furniName = $furniName;
        $this->price = $price;
    }
}

class Queries extends Database {
    public $sso;

    public function __construct($sso){
        $this->sso = $sso;
    }

    public function getStickers(){
        $stickers = [];

        $pdo = new Database();
        $query = $pdo->prepare("SELECT * FROM kubbo_stickers");
        $query->execute();
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                array_push($stickers, new Sticker($row["stickers"], $row["stickers_url"]));
            }
        }

        return $stickers;
    }

    public function getUsername(){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT username FROM players WHERE auth_ticket = :sso");
        $query->execute(array(":sso" => $this->sso));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                return $row["username"];
            }
        }
        else return null;
    }

    public function getId(){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT id FROM players WHERE auth_ticket = :sso");
        $query->execute(array(":sso" => $this->sso));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                return $row["id"];
            }
        }
        else return null;
    }

    public function updatePassword($password){
        $pdo = new Database();
        $query = $pdo->prepare("UPDATE players SET password = :password WHERE auth_ticket = :sso");
        $query->execute(array(":password" => password_hash($password, PASSWORD_BCRYPT), ":sso" => $this->sso));
    }

    public function getMarketplaceOffers(){
        $offers = [];
        $count = 0;

        $pdo = new Database();
        $query = $pdo->prepare("SELECT item_id, price FROM marketplace_items WHERE user_id = :id AND state = :state");
        $query->execute(array(":id" => $this->sso, ":state" => 1));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                if($count > 5) continue;
                $itemId = $this->getItemBaseId($row["item_id"]);

                if($itemId != null){
                    $item = $this->getItemData($itemId);
                    $item->price = $row["price"];
                    array_push($offers, $item);
                    $count++;
                }
            }
        }

        return $offers;
    }

    public function getItemBaseId($furniId){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT item_id FROM items WHERE id = :id");
        $query->execute(array(":id" => $furniId));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                return $row["item_id"];
            }
        }
        else return null;
    }

    public function getItemData($itemId){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT public_name, item_name FROM furniture WHERE id = :id");
        $query->execute(array(":id" => $itemId));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                return new MarketplaceItem($this->getCatalogName($itemId), $row["item_name"], 0);
            }
        }
        else return null;
    }



    public function getCatalogName($itemId){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT catalog_name FROM catalog_items WHERE item_ids = :id");
        $query->execute(array(":id" => $itemId));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                return $row["catalog_name"];
            }
        }
        else return null;
    }

    public function getBanners(){
        $id = $this->getId();
        $banners["banners"] = [];

        $pdo = new Database();
        $query = $pdo->prepare("SELECT banner_id FROM users_banners WHERE user_id = :id");
        $query->execute(array(":id" => $id));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                array_push($banners["banners"], $row["banner_id"]);
            }
        }
        
        if(count($banners["banners"]) == 0) return null;
        else return $banners["banners"];
    }

    public function updateBanner($bannerId){
        $pdo = new Database();
        $query = $pdo->prepare("UPDATE users SET banner_id = :bannerId WHERE auth_ticket = :sso");
        $query->execute(array(":bannerId" => $bannerId, ":sso" => $this->sso));
    }

    public function getVipTime(){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT users_subscriptions.subscription_type, users_subscriptions.active, users_subscriptions.duration, users_subscriptions.timestamp_start FROM users_subscriptions INNER JOIN users ON users_subscriptions.user_id = users.id WHERE users.auth_ticket = :sso");
        $query->execute(array(":sso" => $this->sso));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                if($row["active"] == 1 && $row["subscription_type"] == "HABBO_VIP"){
                    return intval(intval((($row["timestamp_start"] + $row["duration"]) - time())) / (3600*24));
                }
            }
        }
        else return null;
    }

    public function getColors(){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT color_primary, color_text, color_secondary FROM users WHERE auth_ticket = :sso");
        $query->execute(array(":sso" => $this->sso));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $colors = [];
                $colors["color_primary"] = $row["color_primary"];
                $colors["color_text"] = $row["color_text"];
                $colors["color_secondary"] = $row["color_secondary"];
                return $colors;
            }
        }
        else return null;   
    }

    public function updateColors($colorPrimary, $colorText, $colorSecondary){
        $pdo = new Database();
        $query = $pdo->prepare("UPDATE users SET color_primary = :colorPrimary, color_text = :colorText, color_secondary = :colorSecondary WHERE auth_ticket = :sso");
        $query->execute(array(":colorPrimary" => $colorPrimary, ":colorText" => $colorText, ":colorSecondary" => $colorSecondary, ":sso" => $this->sso));
    }

    public function getBannerByUsername($username){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT banner_id FROM players WHERE username = :username");
        $query->execute(array(":username" => $username));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                return $row["banner_id"];
            }
        }
        else return 0;
    }

    public function updateBannerBySso($bannerId, $sso){
        $pdo = new Database();
        $query = $pdo->prepare("UPDATE players SET banner_id = :bannerId WHERE auth_ticket = :sso");
        $query->execute(array(":bannerId" => $bannerId, ":sso" => $sso));
    }

    public function getColorsBySso($sso){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT color_primary, color_text, color_secondary FROM players WHERE auth_ticket = :sso");
        $query->execute(array(":sso" => $sso));
        if ($query->rowCount() != 0){
            while ($row = $query->fetch(PDO::FETCH_ASSOC)){
                $colorsInfo = [];
                $colorsInfo["color_primary"] = $row["color_primary"];
                $colorsInfo["color_text"] = $row["color_text"];
                $colorsInfo["color_secondary"] = $row["color_secondary"];
                return $colorsInfo;
            }
        }
        else return null;
    }

    public function saveColorsBySso($colorPrimary, $colorText, $colorSecondary, $sso){
        $pdo = new Database();
        $query = $pdo->prepare("UPDATE players SET color_primary = :colorPrimary, color_text = :colorText, color_secondary = :colorSecondary WHERE auth_ticket = :sso");
        $query->execute(array(":colorPrimary" => $colorPrimary, ":colorText" => $colorText, ":colorSecondary" => $colorSecondary, ":sso" => $sso));
    }

}

?>

