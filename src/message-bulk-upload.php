<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use DI\ContainerBuilder;
use Slim\Http\UploadedFile;
// $containerBuilder = new ContainerBuilder();
// $container = $containerBuilder->build();
//include "Models/db.php";
return function (App $app) {
    $container = $app->getContainer();
    
    $app->post('/send-message/bulk', function (Request $request, Response $response, array $args) {
        include "Models/db.php";
        $data = $request->getParsedBody();
        $message = $data['message'];
        $files = $request->getUploadedFiles();
        
    
        if (empty($files['newfile'])) {
        throw new Exception('Expected a newfile');
    }

    $newfile = $files['newfile'];
    $mediaTypes = ["csv"];
   
    $fileExt = pathinfo($newfile->getClientFilename(), PATHINFO_EXTENSION);
   
    $is_validFileExt = 0;
    if( ! in_array($fileExt,$mediaTypes)){
      $is_validFileExt = 0;
        $responseMessage = array(
            "message" => 'Please upload csv file type',
           "success" => false

            
          );
          
         
          $response->getBody()->write(json_encode($responseMessage));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
       
    }else{
      $is_validFileExt = 1;
    }
    $is_withLimit = 0;
    if($newfile->getSize() > 2000000){
      $is_withLimit = 0;
        $responseMessage = array(
            "message" => 'Please upload max 2 MB file',
           "success" => false,
           "total_message_sent" =>0 
          
            
          );
          
         
          $response->getBody()->write(json_encode($responseMessage));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }else{
      $is_withLimit = 1;
    }
    
    if ($newfile->getError() === UPLOAD_ERR_OK && $is_withLimit == 1 && $is_validFileExt == 1   ) {
        $uploadFileName = $newfile->getClientFilename();
        $fileNameSplit = explode(".csv",$uploadFileName);
       
        $fileReName = date('d-m-Y_H_i_s')."_".$fileNameSplit[0].".".$fileExt;
        $fileLocation = dirname(__DIR__)."/uploads/bulk/".$fileReName;
        $newfile->moveTo($fileLocation);
   
    // do something after file uploaded

        $file = fopen($fileLocation, 'r');
        $index = 0;
        $allData  = fgetcsv($file);
        $coverNumberList = [];

        
      $validFormat = 1;
     
      if(strtoupper ($allData[0]) == 'COVER NO' ){
        $validFormat = 1;
       
      }else{
       
        $validFormat = 0;
      }

      if($validFormat == 0 ){
        $responseMessage = array(
            "message" => 'Invalid Header, Please upload correct header file',
           "success" => false, 
           "total_message_sent" => 0           
          );
          $response->getBody()->write(json_encode($responseMessage));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
        //Delete the file 
        unlink($fileLocation);
    }else{
      //Do Insertion 
        while (($line = fgetcsv($file)) !== FALSE) {  
                $coverNumber = $line[0];    
                array_push($coverNumberList,$coverNumber);
               
            $index++;
        }
        fclose($file);
        
        $coverNumberListInStr =  '"' . implode('","', $coverNumberList) . '"';
        
         $sql = "SELECT id,phone from haji_info 
                 WHERE cover_number in  (".$coverNumberListInStr.") 
                 AND is_active = 1 
                 AND is_cover_head = 1  ";
         try {
           include "Models/db.php"; 
           $stmt = $conn->prepare($sql);
         
           $result = $conn->query($sql);
          $haji_user_list = $result->fetchAll();
        
         // $message="got received";
          if($haji_user_list ){
             foreach($haji_user_list as $eachRow){
                
                  if($eachRow){
                     
                     $sql_sendMessage = "INSERT INTO  message_sent_info  (haji_id,  message) VALUES (:haji_id, :message)";
                     $stmt = $conn->prepare($sql_sendMessage);
                     $stmt->bindParam(':haji_id', $eachRow['id']);
                     $stmt->bindParam(':message', $message);
                    
                     $result = $stmt->execute();
                  
                  }
                 
                }
          }
         //  $result = $stmt->execute();
           $responseMessage = array(
             "message" => 'Message has been sent successfully.',
            "success" => true,
            "total_message_sent" => sizeof($haji_user_list)
             
           );
           
          
           $response->getBody()->write(json_encode($responseMessage));
           return $response
             ->withHeader('content-type', 'application/json')
             ->withStatus(200);
         } catch (PDOException $e) {
           $error = array(
             "message" => $e->getMessage()
           );
        
           $response->getBody()->write(json_encode($error));
           return $response
             ->withHeader('content-type', 'application/json')
             ->withStatus(500);
         }
        }

      }
       
       });
       

       

};