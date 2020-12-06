<?php
namespace Models;
use DateTime;
use Services\Service;

class BaseModel  {
    private ?int $id;
    private ?string $uuid;
    private ?int $isActive;

    /**
     * @return int|null
     */
    public function getIsActive(): ?int
    {
        return $this->isActive;
    }

    /**
     * @param int|null $isActive
     * @return BaseModel
     */
    public function setIsActive(?int $isActive): BaseModel
    {
        $this->isActive = $isActive;
        return $this;
    }
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;
    public function __construct(){
        $this->generateCreatedAt();
        $this->generateUpdateAt();
        $this->generateUUID();
    }
    public function generateUUID(){
        $this->uuid=Service::guidv4();
    }
    public function generateCreatedAt():DateTime{
        $this->createdAt=new DateTime();
        return $this->createdAt;
    }
    public function generateUpdateAt():DateTime{
        $this->updatedAt =new DateTime();
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
     * @param int $id
     * @return BaseModel
     */
    public function setId(int $id): BaseModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime{
        if(!$this->createdAt) $this->createdAt=new DateTime();
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return BaseModel
     */
    public function setCreatedAt(DateTime $createdAt): BaseModel
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime{
        if(!$this->updatedAt) $this->updatedAt=new DateTime();
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @return BaseModel
     */
    public function setUpdatedAt(DateTime $updatedAt): BaseModel
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