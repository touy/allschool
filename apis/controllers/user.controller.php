<?php
namespace Controllers;
use Exception;
use Models\UserModel;
use Services\Service;

class UserController extends BaseController {

    public function __construct(public ?UserModel $userModel=null ){
        parent::__construct();
        if(!$userModel){
            $this->createModel();
        }
    }
    public function buildModel(object $json):UserModel{
        $this->userModel=new UserModel();
        return $this->userModel->Build($json);
    }
    public function createModel():UserModel{
        $this->userModel=new UserModel();
        return $this->userModel;
    }
    public function insert():void{
        try{
            $sql = 'insert into `users`  (`uuid`,`isActive`,`username`,`password`,`createdAt`,`updatedAt`) values(?,?,?,?)';
            $stmt = $this->prepare($sql);
            $val=[$this->userModel->getUuid(),
                1,
                $this->userModel->getUsername(),
                $this->userModel->getPassword(),
                $this->userModel->getCreatedAt(),
                $this->userModel->getUpdatedAt()];
            $stmt->bind_param('sissss',$val);
            if(!$stmt->execute()){
                throw new Exception('inserted failed');
            }
            Service::endResponse([$stmt->insert_id],'inserted OK',status:1);
            $stmt->close();
            $this->close();
        }catch (Exception $ex){
            Service::endResponse(data:$ex,message:$ex->getMessage(),status: 0);
        }

    }
    public function delete():void{
        try{
            $this->userModel->validateId();
            $stmt = $this->prepare('delete from `users` where id =?');
            $val=[$this->userModel->getId()];
            $stmt->bind_param('i',$val);
            if(!$stmt->execute()){
                throw new Exception('deleted failed');
            }
            Service::endResponse([$stmt->insert_id],'deleted OK',status:1);
            $stmt->close();
            $this->close();
        }catch (Exception $ex){
            Service::endResponse(data:$ex,message:$ex->getMessage(),status: 0);
        }
    }
    public function update():void{
        try{
            $this->userModel->validateId();
            $this->userModel->generateUpdateAt();
            $stmt = $this->prepare('update `users` set uuid=?,
                   username = ?,
                   password=?,
                    updatedAt=?,
                    insertedAt=?,
                    where id =?');
            $val=[$this->userModel->getUuid(),
                $this->userModel->getUsername(),
                $this->userModel->getPassword(),
                $this->userModel->getUpdatedAt(),
                $this->userModel->getUpdatedAt(),
                $this->userModel->getId()];
            $stmt->bind_param('sssiii',$val);
            if(!$stmt->execute()){
                throw new Exception('updated failed');
            }
            Service::endResponse([$stmt->insert_id],'updated OK',status:1);
            $stmt->close();
            $this->close();
        }catch (Exception $ex){
            Service::endResponse(data:$ex,message:$ex->getMessage(),status: 0);
        }
    }
    public function save():void{
        if(!$this->userModel->getId()){
            $this->insert();
        }else{
            $this->update();
        }
        //....
    }
    public function findOne():?UserModel{
        try{
            $this->userModel->validateId();
            $this->userModel->generateUpdateAt();
            $stmt = $this->prepare('select * from  `users` 
                    where id =?');
            $val=[$this->userModel->getId()];
            $stmt->bind_param('i',$val);
            if(!$stmt->execute()){
                throw new Exception('deleted failed');
            }
            Service::endResponse([$stmt->insert_id],'deleted OK',status:1);
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $this->userModel->Build($data);
        }catch (Exception $ex){
            Service::endResponse(data:$ex,message:$ex->getMessage(),status: 0);
            $this->close();
        }
        return null;
    }

    public function getOne():void{
        try{
            $this->userModel->validateId();
            $this->userModel->generateUpdateAt();
            $stmt = $this->prepare('select * from  `users` 
                    where id =?');
            $val=[$this->userModel->getId()];
            $stmt->bind_param('i',$val);
            if(!$stmt->execute()){
                throw new Exception('deleted failed');
            }
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            Service::endResponse([$data],'deleted OK',status:1);
            $stmt->close();
            $this->close();
        }catch (Exception $ex){
            Service::endResponse(data:$ex,message:$ex->getMessage(),status: 0);
        }
    }
    /**
     * @return array<UserModel>
     */
    public function findMany():array{
        try{
            $this->userModel->validateId();
            $this->userModel->generateUpdateAt();
            $stmt = $this->prepare('select * from  `users` 
                    LIMIT ?,?');
            $val=[Service::$limit,Service::$offset];
            $stmt->bind_param('i',$val);
            if(!$stmt->execute()){
                throw new Exception('find Many failed');
            }
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }catch (Exception $ex){
            Service::endResponse(data:$ex,message:$ex->getMessage(),status: 0);
            $this->close();
        }
        return [];
    }
    public function getMany():void{
        try{
            $this->userModel->validateId();
            $this->userModel->generateUpdateAt();
            $stmt = $this->prepare('select * from  `users` 
                    LIMIT ?,?');
            $val=[Service::$limit,Service::$offset];
            $stmt->bind_param('i',$val);
            if(!$stmt->execute()){
                throw new Exception('deleted failed');
            }
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            Service::endResponse([$data],'deleted OK',status:1);
            $stmt->close();
            $this->close();
        }catch (Exception $ex){
            Service::endResponse(data:$ex,message:$ex->getMessage(),status: 0);
        }
    }
    public function resetPassword():void{

    }
    public function changePassword():void{}
}
