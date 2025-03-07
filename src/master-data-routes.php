<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

//include "Models/db.php";
return function (App $app) {
    $container = $app->getContainer();
    $app->get('/uploads/haji-documents/{data}', function($request, $response, $args) {    
      $data = $args['data'];
     // echo "data:",$data;
      $image = @file_get_contents("../uploads/haji-documents/".$data);
     if($image === FALSE) {
         $handler = $this->notFoundHandler;
         return $handler($request, $response);    
      }
  
      $response->write($image);
      return $response->withHeader('Content-Type', FILEINFO_MIME_TYPE);
  });
    $app->get('/master-data/bank', function (Request $request, Response $response, array $args) {
        include "Models/db.php";
        $data = $request->getParsedBody();
      

    
    try{
        $sql = "SELECT bank_id as bankId, bank_name as bankName 
         FROM  tbl_bank_list 
         WHERE is_active = 1 ORDER BY bank_name
        ";
        $result = $conn->query($sql);
        $resultData = $result->fetchAll(PDO::FETCH_OBJ);
        $total_list = count($resultData);
        $responseMessage = array(
        "success" => true,
        "total" =>$total_list,
        "data" => $resultData,
        "message" => 'Haji list'

          
        );
        $response->getBody()->write(json_encode($responseMessage));
        } catch (PDOException $e) {
        $error = array(
          "success" => false,
          "total" => 0,
          "data" =>[],
          "message" => $e->getMessage(),
        );

        $response->getBody()->write(json_encode($error));
        return $response
          ->withHeader('content-type', 'application/json')
          ->withStatus(500);
        }
        
        
        });

        $app->get('/master-data/sms_template', function (Request $request, Response $response, array $args) {
          include "Models/db.php";
          $data = $request->getParsedBody();
        
  
      
      try{
          $sql = "SELECT id as templateId, template_name as templateName,
                  template_description as templateDescrption
           FROM  tbl_sms_template 
           WHERE is_active = 1 ORDER BY template_name
          ";
          $result = $conn->query($sql);
          $resultData = $result->fetchAll(PDO::FETCH_OBJ);
          $total_list = count($resultData);
          $responseMessage = array(
          "success" => true,
          "total" =>$total_list,
          "data" => $resultData,
          "message" => 'sms template list'
  
            
          );
          $response->getBody()->write(json_encode($responseMessage));
          } catch (PDOException $e) {
          $error = array(
            "success" => false,
            "total" => 0,
            "data" =>[],
            "message" => $e->getMessage(),
          );
  
          $response->getBody()->write(json_encode($error));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
          }
          
          
          });
            
      

};