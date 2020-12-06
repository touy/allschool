<?php namespace Services;
// use DateTime;
use Firebase\JWT\JWT;
use Models\UserModel;

use Exception;


class Config{
       public static ?int $timezone = 420;
                public static ?int $client_time_zone =  420;
                public static ?string $lang =  '';
                public static ?string $date_fmt =  '';
                public static ?string $time_fmt =  '';
                public static ?bool $sond_alarm =  true;
                public static ?bool $popup_alarm =  true;
                public static ?string $unit_distance =  'KM';
                public static ?string $unit_fuel =  'L';
                public static ?string $unit_temperature =  'C';
                public static ?string $unit_speed = 'KMH';
                public static ?string $okind ='';
}
class Service{
    public static ?string $m='';
    public static ?string $token='';
    public static ?int $limit=10;
    public static ?int $offset =0;
    public static ?UserModel $_userModel;
    public static ?string $key = "uaXN0cmF0b3IiLCJsYXQiOjE3OTY0NjUz";
    public static function initialize(){
        try {
            Service::$m=getallheaders()['m'] ;
            Service::$token=getallheaders()['token'] ;
            Service::$limit=intval( getallheaders()['limit']) ;
            Service::$offset=intval(getallheaders()['page'])*Service::$limit ;
        }catch (Exception $ex){
            Service::endResponse($ex,$ex->getMessage(),0);
        }
    }
    public static function isCurrentUser(int $id){
        if(Service::$_userModel->getId()==$id){
            return true;
        }
        return false;
    }
    public static function printJSON(mixed $data,String $message,int $status){
        try {
            if(is_array($data) and !$data){
                echo json_encode(array("data"=>$data,"message"=>$message,"status"=>$status));
            }else{
                echo json_encode(array("data"=>[$data],"message"=>$message,"status"=>$status));
            }
        }catch (Exception $ex){
            echo json_encode(array("data"=>[$ex],"message"=>$ex->getMessage(),"status"=>$status));
            die();
        }
    }

    /**
     * @param mixed|null $data
     * @param String $message
     * @param int $status
     */
    public static function endResponse(mixed $data, String $message, int $status){
        try {
            if(is_array($data) and !$data){
                echo json_encode(array("data"=>$data,"message"=>$message,"status"=>$status));
            }else{
                echo json_encode(array("data"=>[$data],"message"=>$message,"status"=>$status));
            }
            die();
        }catch (Exception $ex){
            echo json_encode(array("data"=>[$ex],"message"=>$ex->getMessage(),"status"=>$status));
            die();
        }
    }
    public static function guidv4():string
    {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    public static function registerToken($user){
        // var_dump($user);


        $payload = array(
            "iss" => 'laoapps.com',
            "aud" => "jwt.laoapps.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "data" => $user,
            "updatetime"=>  Service::tickTime()
        );
        $jwt = JWT::encode($payload, Service::$key);

        return $jwt;
    }
    public static  function getDetailsToken($jwt):Config|null{
        try {
            $decoded = JWT::decode($jwt,  Service::$key, array('HS256'));
            $decoded_array = (array) $decoded;

            $user = $decoded_array ['data'];
            // print_r($user);
            // check user from database;
            $config = new Config();
            if(isset($user)){
                $config->timezone =  $user->timezone;
                $config->client_time_zone =  $user->client_time_zone;
                $config->lang =  $user->lang;
                $config->date_fmt =  $user->date_fmt;
                $config->time_fmt =  $user->time_fmt;
                $config->sond_alarm =  $user->sond_alarm;
                $config->popup_alarm =  $user->popup_alarm;
                $config->unit_distance =  $user->ud;
                $config->unit_fuel =  $user->uf;
                $config->unit_temperature =  $user->ut;
                $config->unit_speed =  $user->us;
                $config->okind = $user->okind;
                return $config;
            }
        }
        catch (Exception $ex) {
            //die($e);
            return null;
        }
        // finally {
        //     //optional code that always runs
        // }
        return null;
    }
    public static function authorizeToken($jwt){
        try {

            $decoded = JWT::decode($jwt,  Service::$key, array('HS256'));
            $decoded_array = (array) $decoded;

            $user = $decoded_array ['data'];
            // print_r($user);
            // check user from database;
            if(isset($user)){
                return $user->pass;
            }
        }
        catch (Exception $ex) {
            //die($e);
            return null;
        }
        // finally {
        //     //optional code that always runs
        // }

        return null;
    }
    public static  function checkToken($jwt){
        try {
            $decoded = JWT::decode($jwt,  Service::$key, array('HS256'));
            $decoded_array = (array) $decoded;

            $user = $decoded_array ['data'];
            // print_r($user);
            // check user from database;

            if(isset($user)){
                return $user->uid;
            }
        }
        catch (Exception $ex) {
            //die($e);
            return -1;
        }
        return -1;
    }
    public static function allDetailsToken($jwt){
        $decoded = JWT::decode($jwt,  Service::$key, array('HS256'));
        $decoded_array = (array) $decoded;

        $user = $decoded_array ['data'];
        // print_r($user);
        // check user from database;
        if(isset($user)){
            return $user;
        }
        return null;
    }
    public static  function unitSpeedToken($jwt){
        $decoded = JWT::decode($jwt,  Service::$key, array('HS256'));
        $decoded_array = (array) $decoded;

        $user = $decoded_array ['data'];
        // print_r($user);
        // check user from database;
        if(isset($user)){
            return $user->us;
        }
        return 0;
    }
    public static function timeZoneToken($jwt){
        $decoded = JWT::decode($jwt,  Service::$key, array('HS256'));
        $decoded_array = (array) $decoded;

        $user = $decoded_array ['data'];
        // print_r($user);
        // check user from database;
        if(isset($user)){
            return $user->client_time_zone;
        }
        return 0;
    }
    public static function refreshToken($jwt){
        $decoded = JWT::decode($jwt,  Service::$key, array('HS256'));
        $decoded_array = (array) $decoded;
        return Service::registerToken($decoded_array['data']);
    }
    public static function tickTime(): float|int
    {
        $mt = microtime(true);
        $mt =  $mt*1000; //microsecs
        return (string)$mt*10; //100 Nanosecs
    }
}