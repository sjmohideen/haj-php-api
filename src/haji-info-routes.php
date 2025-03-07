<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use DI\ContainerBuilder;

  //include "Models/db.php";
  return function (App $app) {
      $container = $app->getContainer();
      // echo "welcome Mohi";
      // echo  phpinfo();
      $app->get('/haji-info/all-active', function (Request $request, Response $response) {
        include "Models/db.php";
          try{
            $sql_overall = "SELECT COUNT(*) AS overAllCount 
            FROM  haji_info WHERE is_active = 1";
            $resultOverAll = $conn->query($sql_overall);
            $resultOverAllCount = $resultOverAll->fetchColumn();
            
          $sql = "SELECT id,cover_number as coverNumber,phone,name,
          district_name as districtName,
          account_name as accountName,
          account_number as accountNumber,
          bank_name as bankName,ifc_code as ifcCode, 
          branch_name as branchName
          FROM haji_info 
          WHERE is_active = 1 limit 0,5000";
          $result = $conn->query($sql);
          $resultData = $result->fetchAll(PDO::FETCH_OBJ);
          $total_list = count($resultData);
         $responseMessage = array(
          "success" => true,
          "overAllCount" => $resultOverAllCount,
          "total" =>$total_list,
          "data" => $resultData,
          "message" => 'Haji list'

            
          );
          $response->getBody()->write(json_encode($responseMessage));
          } catch (PDOException $e) {
          $error = array(
            "success" => false,
            "total" => 0,
            "overAllCount" => 0,
            "data" =>[],
            "message" => $e->getMessage(),
          );

          $response->getBody()->write(json_encode($error));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
          }
          
          
          });
          $app->get('/haji-info/all-active-pagination/{start}/{limit}', function (Request $request, Response $response) {
            include "Models/db.php";
            $startLimt = $request->getAttribute('start');
            $maxLimt = $request->getAttribute('limit') ? $request->getAttribute('limit') : 1000;
              try{
                $sql_overall = "SELECT COUNT(*) AS overAllCount 
                FROM  haji_info WHERE is_active = 1";
                $resultOverAll = $conn->query($sql_overall);
                $resultOverAllCount = $resultOverAll->fetchColumn();
                
              $sql = "SELECT id,cover_number as coverNumber,phone,name,
              district_name as districtName,
              account_name as accountName,
              account_number as accountNumber,
              bank_name as bankName,ifc_code as ifcCode, 
              branch_name as branchName
              FROM haji_info 
              WHERE is_active = 1 limit $startLimt,$maxLimt";
              $result = $conn->query($sql);
              $resultData = $result->fetchAll(PDO::FETCH_OBJ);
              $total_list = count($resultData);
             $responseMessage = array(
              "success" => true,
              "overAllCount" => $resultOverAllCount,
              "total" =>$total_list,
              "data" => $resultData,
              "message" => 'Haji list'
    
                
              );
              $response->getBody()->write(json_encode($responseMessage));
              } catch (PDOException $e) {
              $error = array(
                "success" => false,
                "total" => 0,
                "overAllCount" => 0,
                "data" =>[],
                "message" => $e->getMessage(),
              );
    
              $response->getBody()->write(json_encode($error));
              return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(500);
              }
              
              
              });

          $app->get('/haji-info/district-wise-haji-list', function (Request $request, Response $response) {
            include "Models/db.php";
              try{
              $sql = "SELECT district_name as districtName, COUNT(*) as haji_count
                      FROM   haji_info  WHERE is_active = 1 
                      GROUP BY district_id 
                      order by haji_count DESC";
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

          $app->get('/haji-info/cover-head-info/{coverNumber}', function (Request $request, Response $response) {
            $coverNumber = $request->getAttribute('coverNumber');
              include "Models/db.php";
                try{
                $sql = "SELECT id,cover_number as coverNumber,phone,name,
                district_name as districtName,district_id as districtId
                FROM haji_info 
                WHERE is_active = 1 
                AND is_cover_head = 1 
                AND cover_number='".$coverNumber."'  ";
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
          $app->get('/haji-info/by-cover/{coverNumber}', function (Request $request, Response $response) {
          $coverNumber = $request->getAttribute('coverNumber');
            include "Models/db.php";
              try{
              $sql = "SELECT id,cover_number as coverNumber,phone,name,
              district_name as districtName, district_id as districtId,
             account_name as accountName,
             account_number as accountNumber,
             bank_id as bankId,
             bank_name as bankName,
             ifc_code as ifcCode,
             document_file as documentFile,
             branch_name AS branchName,
             is_document_updated as isDocumentUpdated

              FROM haji_info 
              WHERE is_active = 1 
              AND cover_number='".$coverNumber."' ";
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

              $app->post(
                '/haji-info/update/{id}/{accountName}/{accountNumber}/{bankId}/{bankName}/{ifcCode}/{branchName}/{newBankName}',
                function (Request $request, Response $response, array $args) 
              {
              $id = $request->getAttribute('id');
              $accountName = $request->getAttribute('accountName');
              $accountNumber = $request->getAttribute('accountNumber');
              $bankId = $request->getAttribute('bankId');
              $bankName = $request->getAttribute('bankName');
              $ifcCode = $request->getAttribute('ifcCode');
              $branchName = $request->getAttribute('branchName');
              $newBankName = $request->getAttribute('newBankName') ? $request->getAttribute('newBankName'):'';
              
              $files = $request->getUploadedFiles();
     
            //   //File Upload
           
              $fileReName ='';
             
              if (empty($files['newfile'])) {
                $responseMessage = array(
                  "message" => 'Please send a file with newfile param',
                 "success" => false
                );
                
               
                $response->getBody()->write(json_encode($responseMessage));
                return $response
                  ->withHeader('content-type', 'application/json')
                  ->withStatus(200);
            }
           
            $newfile = $files['newfile'];
            $mediaTypes = ["png","jpg","jpeg"];
           

            $fileExt = pathinfo($newfile->getClientFilename(), PATHINFO_EXTENSION);
            $fileExt = strtolower($fileExt);
            
            $is_validFileExt = 0;
            if( ! in_array($fileExt,$mediaTypes)){
              $is_validFileExt = 0;
                $responseMessage = array(
                    "message" => 'Please upload image file type',
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
                    "message" => 'Please upload max 2MB',
                   "success" => false
                  
                    
                  );
                  
                 
                  $response->getBody()->write(json_encode($responseMessage));
                  return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
            }else{
              $is_withLimit = 1;
            }
            if ($newfile->getError() === UPLOAD_ERR_OK && $is_withLimit ==1 &&  $is_validFileExt == 1  ) {
                $uploadFileName = $newfile->getClientFilename();
                $fileNameSplit = explode($fileExt,$uploadFileName);
               
                $fileReName = $id."_".date('d-m-Y_H-i-s').".".$fileExt;
                $fileLocation = dirname(__DIR__)."/uploads/haji-documents/".$fileReName;
                $newfile->moveTo($fileLocation);
           
           

            }

           // add if new bank
           include "Models/db.php";
             if($newBankName && strlen($newBankName) > 0 && $newBankName !='ex' ){
              $newBank = strtoupper($newBankName);
              $sql_check_bank = "select bank_id from tbl_bank_list WHERE bank_name = '".$newBankName."' ";
              $bankId = $conn->query($sql_check_bank)->fetchColumn(); 
             
              if($bankId >0 )
              {

              }else{
              $sql_bank = "INSERT INTO  tbl_bank_list  (bank_name) VALUES (:bank_name)";
              $stmt = $conn->prepare($sql_bank);
              $stmt->bindParam(':bank_name', $newBank);
              $result = $stmt->execute();
              $bankId = $conn->lastInsertId();
             }

            }
              $sql = "UPDATE haji_info SET
                       account_name = :accountName,
                       account_number = :accountNumber,
                       bank_id = :bankId,
                       bank_name = :bankName,
                       ifc_code = :ifcCode,
                       branch_name = :branchName,
                       document_file = :fileReName,
                       is_document_updated = '1'
              WHERE id = $id";
              
              try {
              
               //$db = new Db();
               //$conn = $db->connect();
               //Delete Existing document file

               
                $sql_img = "select document_file from haji_info 
                WHERE id = '".$id."' ";
                $nRows_img = $conn->query($sql_img)->fetchColumn();
                if($nRows_img){
                  $fileLocationExist = dirname(__DIR__)."/uploads/haji-documents/".$nRows_img;
                 
                  if (file_exists($fileLocationExist)) {
                   
                     unlink($fileLocationExist);
                  }
                }

               //End 
               $stmt = $conn->prepare($sql);
               $stmt->bindParam(':accountName', $accountName);
               $stmt->bindParam(':accountNumber', $accountNumber);
               $stmt->bindParam(':bankId', $bankId);
               $stmt->bindParam(':bankName', $bankName);
               $stmt->bindParam(':branchName', $branchName);
               $stmt->bindParam(':ifcCode', $ifcCode);
               $stmt->bindParam(':fileReName', $fileReName);
              
               $result = $stmt->execute();
              
               $db = null;
               //echo "Update successful! ";
               $responseMessage = array(
                "message" => 'Haji data has beens successfully',
               "success" => true,           
              );
              $response->getBody()->write(json_encode($responseMessage));
              return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
              
              } catch (PDOException $e) {
               $error = array(
                 "message" => $e->getMessage(),
                 "success"=> false
               );
              
               $response->getBody()->write(json_encode($error));
               return $response
                 ->withHeader('content-type', 'application/json')
                 ->withStatus(500);
              }
              });

      $app->get('/haji-info/all', function (Request $request, Response $response) {
        include "Models/db.php";
        
      try{
      $sql = "SELECT id,cover_number as coverNumber,phone,name,district_name as districtName from haji_info  ";
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
      "message" => $e->getMessage()
      );

      $response->getBody()->write(json_encode($error));
      return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus(500);
      }


      });
    $app->post('/haji-info/bulk-upload', function (Request $request, Response $response, array $args) use ($container) {
      include "Models/db.php";
      $data = $request->getParsedBody();
     
      $files = $request->getUploadedFiles();
     
      if (empty($files['newfile'])) {
        $responseMessage = array(
          "message" => 'Please send a file with newfile param',
         "success" => false
        );
        
       
        $response->getBody()->write(json_encode($responseMessage));
        return $response
          ->withHeader('content-type', 'application/json')
          ->withStatus(200);
    }
   
    $newfile = $files['newfile'];
    $mediaTypes = ["csv"];
   
    $fileExt = pathinfo($newfile->getClientFilename(), PATHINFO_EXTENSION);
  
    if( ! in_array($fileExt,$mediaTypes)){
        $responseMessage = array(
            "message" => 'Please upload csv file type',
           "success" => false
          
            
          );
          
         
          $response->getBody()->write(json_encode($responseMessage));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
       
    }
    if($newfile->getSize() > 2000000){
        $responseMessage = array(
            "message" => 'Please upload max 2MB',
           "success" => false
          
            
          );
          
         
          $response->getBody()->write(json_encode($responseMessage));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
    if ($newfile->getError() === UPLOAD_ERR_OK && $newfile->getSize() <= 2000000 &&  in_array($fileExt,$mediaTypes) ) {
        $uploadFileName = $newfile->getClientFilename();
        $fileNameSplit = explode(".csv",$uploadFileName);
       
        $fileReName = date('d-m-Y_H_i_s')."_".$fileNameSplit[0].".".$fileExt;
        $fileLocation = dirname(__DIR__)."/uploads/haji-info/".$fileReName;
        $newfile->moveTo($fileLocation);
   
    // do something after file uploaded

        $file = fopen($fileLocation, 'r');
        $index = 0;
        $allData  = fgetcsv($file);
    
      $validFormat = 1;
      if(strtoupper($allData[1]) == 'COVER NO' && strtoupper($allData[2]) == 'NAME' && strtoupper($allData[4]) == 'DISTRICT' && strtoupper($allData[5]) == 'MOBILE NUMBER' ){
        $validFormat = 1;
       
      }else{
       
        $validFormat = 0;
      }
     
        if($validFormat == 0 ){
            $responseMessage = array(
                "message" => 'Invalid Header',
               "success" => false,           
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
            
            $serialNumber = trim($line[0]);
            $coverNumber = trim($line[1]);
            $name = trim($line[2]);
            $selectionNumber = trim($line[3]);
            $districtName = trim($line[4]);
            $mobileNumber = trim($line[5]);

             if($name && $coverNumber && $mobileNumber){

              $sql_coverNumberExist = "select id,cover_number from haji_info WHERE cover_number = '".$coverNumber."' AND is_active=1 ";
              $nRows = $conn->query($sql_coverNumberExist)->fetchColumn(); 
              
              if($nRows == ""){
                $is_coverHead = 1;
              }else{
                $is_coverHead =  0;
              }
              //get District Details

              $sql_check_district = "select id from district_list WHERE district_name = '".$districtName."' ";
              $districtId = $conn->query($sql_check_district)->fetchColumn(); 
             
              if($districtId >0 )
              {

              }else{
                $sql_district = "INSERT INTO  district_list  (district_name) VALUES (:district_name)";
                             $stmt = $conn->prepare($sql_district);
                             $stmt->bindParam(':district_name', $districtName);
                             $result = $stmt->execute();
                 $districtId = $conn->lastInsertId();
                 
                 //echo "InsertedId:",$districtId;
              }
             //Check and Insert or update Haji
             $sql_checkExistQuery = "select id,cover_number from haji_info 
                                     WHERE cover_number = '".$coverNumber."'  
                                     AND name = '".$name."'  
                                     AND phone = '".$mobileNumber."'  
                                     AND is_active=1
                                     ";
                $check_hajiId = $conn->query($sql_checkExistQuery)->fetchColumn(); 
                
                if($check_hajiId <= 0 )
                {
                  $sql_hajiInsert = "INSERT INTO  haji_info  
                  (serial_number,cover_number,  name,phone,is_cover_head,selection_number,
                  district_name,district_id
                  ) 
                  VALUES (:serial_number,:cover_number, :name ,:phone,:is_cover_head,
                  :selection_number,
                  :district_name,:district_id)";
                  $stmt = $conn->prepare($sql_hajiInsert);
                  $stmt->bindParam(':serial_number', $serialNumber);
                  $stmt->bindParam(':cover_number', $coverNumber);
                  $stmt->bindParam(':name', $name);
                  $stmt->bindParam(':phone', $mobileNumber);
                  $stmt->bindParam(':is_cover_head', $is_coverHead);
                  $stmt->bindParam(':selection_number', $selectionNumber);
                  $stmt->bindParam(':district_name', $districtName);
                  $stmt->bindParam(':district_id', $districtId);
                  $result = $stmt->execute();

                }
                        

             }//end If
            
            $index++;
          }//endWhile
          $responseMessage = array(
            "message" => 'Haji has beens successfully loaded',
           "success" => true,           
          );
          $response->getBody()->write(json_encode($responseMessage));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
        }//end else
        
        fclose($file);
        
      }
      
    });
    $app->post(
      '/haji-info/bulk-update-haji-status/{status}',
      function (Request $request, Response $response, array $args) 
    {
    $status = $request->getAttribute('status');
    $sql = "UPDATE haji_info SET
             is_active = :status";
    
    try {
      include "Models/db.php";
     $stmt = $conn->prepare($sql);
     $stmt->bindParam(':status', $status);              
    
     $result = $stmt->execute();
    
     $db = null;
     $responseMessage = array(
      "message" => 'All haji status updated successfully',
     "success" => true,           
    );
    $response->getBody()->write(json_encode($responseMessage));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus(200);
    
    } catch (PDOException $e) {
     $error = array(
       "message" => $e->getMessage(),
       "success"=> false
     );
    
     $response->getBody()->write(json_encode($error));
     return $response
       ->withHeader('content-type', 'application/json')
       ->withStatus(500);
    }
    });

    $app->get('/haji-info/dist-wise-haji-document-updated-status', function (Request $request, Response $response) {
      include "Models/db.php";
        try{
        $sql = "select district_name AS districtName, 
        COUNT(*)as haji_count, 
        COUNT(case when h.is_document_updated='1' then 1 end) as documentUpdatedCount,
        COUNT(case when h.is_document_updated='0' then 1 end) as documentNotUpdatedCount 
        FROM haji_info h 
        GROUP BY district_name ORDER BY haji_count DESC";
        $result = $conn->query($sql);
        $resultData = $result->fetchAll(PDO::FETCH_OBJ);
       
       $responseMessage = array(
        "success" => true,
       
        "data" => $resultData,
        "message" => 'Haji document updated status by district'

          
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
