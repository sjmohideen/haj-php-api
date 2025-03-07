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
    
    $app->post('/haji/sticker', function (Request $request, Response $response, array $args) {
        include "Models/db.php";
        $data = $request->getParsedBody();
        $cover_number = $data["coverNo"];
        $phone = $data["mobile_1"];
        $name = $data["name"];
        $address_details = $data["area"];
        $district = $data["dist"];
       
       
        try {
            include "Models/db.php";
        
          
          //$conn = null;
          if($cover_number && $phone){
            $sql_bank = "INSERT INTO   tbl_sticker  
                (cover_number,name,phone,address_details,district) VALUES (:cover_number,:name,:phone,:address_details,:district)";
              $stmt = $conn->prepare($sql_bank);
              $stmt->bindParam(':cover_number', $cover_number);
              $stmt->bindParam(':name', $name);
              $stmt->bindParam(':phone', $phone);
              $stmt->bindParam(':address_details', $address_details);
              $stmt->bindParam(':district', $district);
              $result = $stmt->execute();
              $insertedId = $conn->lastInsertId();
           $responseMessage['data'] = ["lastId" => $insertedId];
            $responseMessage['success'] = $result? true : false;
            $responseMessage['message'] = $result? 'Inserted' : 'Failed';
            
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