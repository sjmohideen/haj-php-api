<?php
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

//include "Models/db.php";
return function (App $app) {
    $container = $app->getContainer();
    
    $app->post('/staff/login', function (Request $request, Response $response, array $args) {
        include "Models/db.php";
        $data = $request->getParsedBody();
        $userName = $data["userName"];
        $passCode = $data["passCode"];
       
        // $sql = "select id,user_name as userName,full_name as fullName,role_id as roleId
        //  FROM users 
        //  WHERE user_name= :userName 
        //  AND pass_code = :passCode";
       
        try {
            include "Models/db.php";
        
          
          //$conn = null;
          if($userName && $passCode){
            $stmt = $conn->prepare("SELECT id,user_name as userName,
                                  full_name as fullName,role_id as roleId 
                          FROM users WHERE user_name=:userName 
                          AND pass_code =:passCode ");
                          $stmt->execute(['userName' => $userName,'passCode'=>$passCode]); 
            //$stmt->execute(':userName', $userName);
         // $stmt->bindParam(':password', $password);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $responseMessage['data'] = $user ? $user :[];
            $responseMessage['success'] = $user? true : false;
            $responseMessage['message'] = $user? 'Login success' : 'Invalid username or password';
            
          }
          $response->getBody()->write(json_encode($responseMessage));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withStatus(200);
        } catch (PDOException $e) {
          $error = array(
            "message" => $e->getMessage()
          );
       
          $response->getBody()->write(json_encode($error));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withStatus(500);
        }
       });

};