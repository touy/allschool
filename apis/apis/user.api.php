<?php
namespace  APIS;
use Controllers\UserController;
use Services\Service;

try {
    $json = (object)json_decode(file_get_contents("php://input",true));
    $userController = new UserController();
    $userController->buildModel($json);
    match (Service::$m) {
        'insert' => $userController->insert(),
        'update' => $userController->update(),
        'get' => $userController->getOne(),
        'list'=>$userController->getMany(),
        'reset'=>$userController->resetPassword(),
        'change'=>$userController->changePassword(),
        default => Service::endResponse(null,'unknown method',0),
    };
}catch (\Exception $ex){
    Service::printJSON($ex,$ex->getMessage(),0);
}

