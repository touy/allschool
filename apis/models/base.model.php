<?php
namespace Models;
use Cassandra\Date;
use Exception;
use Services\Service;

class BaseModel  {
    private ?int $id;
    private ?string $uuid;
    private ?Date $createdAt;
    private ?Date $updatedAt;
    public function __construct(){
        $this->generateCreatedAt();
        $this->generateUpdateAt();
        $this->generateUUID();
    }
    public function generateUUID(){
        $this->uuid=Service::guidv4();
    }
    public function generateCreatedAt():Date{
        $this->createdAt=new Date();
        return $this->createdAt;
    }
    public function generateUpdateAt():Date{
        $this->updatedAt =new Date();
        return $this->updatedAt;
    }
    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        if(!$this->uuid) $this->generateUUID();
        return $this->uuid;
    }

    /**
     * @param string|null $uuid
     * @return BaseModel
     */
    public function setUuid(?string $uuid): BaseModel
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param float|int $id
     * @return BaseModel
     */
    public function setId(int $id): BaseModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Date
     */
    public function getCreatedAt(): Date{
        if(!$this->createdAt) $this->createdAt=new Date();
        return $this->createdAt;
    }

    /**
     * @param Date $createdAt
     * @return BaseModel
     */
    public function setCreatedAt(Date $createdAt): BaseModel
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Date
     */
    public function getUpdatedAt(): Date{
        if(!$this->updatedAt) $this->updatedAt=new Date();
        return $this->updatedAt;
    }

    /**
     * @param Date $updatedAt
     * @return BaseModel
     */
    public function setUpdatedAt(Date $updatedAt): BaseModel
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function validateId(){
        if(is_null($this->id)||is_nan($this->id)){
            Service::endResponse(data:[],message: 'invalid id',status:0);
        }
    }

    public function getJSON():String {
        return json_encode(get_object_vars($this));
    }
}