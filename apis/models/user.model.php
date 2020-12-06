<?php
namespace Models;

use Services\Service;

class UserModel extends BaseModel {
    private string $username;
    private string $password;
    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return UserModel
     */
    public function setUsername(string $username): UserModel
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return UserModel
     */
    public function setPassword(string $password): UserModel
    {
        $this->password = $password;
        return $this;
    }

    public function __construct(){
        parent::__construct();
    }
    public function Create():UserModel{
        return new self();
    }
    public function Build($obj):UserModel{
        try {
            if(!$obj) return new self();
            foreach($obj as $key=>$value){
                $this->$key= $value ;
            }
        }catch (Exception $ex){
            Service::endResponse($ex,$ex->getMessage(),0);

        }
        return $this;
    }
}
