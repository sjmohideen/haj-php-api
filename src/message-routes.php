<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class sendMessageApi {
  function getListAdmin() {
      $admins = array("admin1", "admin2", "admin3"); //Retrieve your magic data
      return $admins;
  }
  function generateNumericOTP($n) { 
   // Take a generator string which consist of 
    // all numeric digits 
    $generator = "1357902468"; 
  
   
    $result = ""; 
  
    for ($i = 1; $i <= $n; $i++) { 
        $result .= substr($generator, (rand()%(strlen($generator))), 1); 
    } 
  
    // Return result 
    return $result; 
} 
  
// Main program 

  function sendCurlMessage($message,$phone){
    
    $username="Mohideen282";

    //Enter your login password 
    $password="Mohideen282";

    //Enter your text message 
    $message=$message;

    //Enter your Sender ID
    $sender="TESTKK";

    //Enter your receiver mobile number
    $mobile_number="+91".$phone;

    //Don't change below code use as it is
    echo $url="https://www.bulksmsgateway.in/sendmessage.php?user=".urlencode($username)."&password=".urlencode($password)."&
    mobile=".urlencode($mobile_number)."&message=".urlencode($message)."&sender=".urlencode($sender)."&type=".urlencode('3');

    $ch = curl_init($url);
    print_r($ch);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $curl_scraped_page = curl_exec($ch);
   

    curl_close($ch);
}

function flassMessage($message,$phone){
  $curl = curl_init();

  //listOfPhoneStr =  implode(',', $listOfPhone);

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2?authorization=BIpKcHztAY5mn07ihUP9lsO4TZVEebrGFX3oa62wQJfjCqvMSyL71goGKRCwaMzXlBDrsZHepAk5yJmd&message=".urlencode($message)."&language=english&route=q&numbers=".urlencode($phone),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  //echo "cURL Error #:" . $err;
} else {
 // echo $response;
}

}

  
}

//include "Models/db.php";
return function (App $app) {
    $container = $app->getContainer();
    
    $app->post('/send-message/simple', function (Request $request, Response $response, array $args) {
        include "Models/db.php";
        $api_sms = new sendMessageApi();
        $data = $request->getParsedBody();
        $sendData = $data["coverNumber"];
        $message = $data["message"];
        //$sendData =array("TNF-1395-3-0", "TNF-1397-1-0");
        $coverNumberListInStr =  '"' . implode('","', $sendData) . '"';
        
        $sql = "select id,phone from haji_info 
        WHERE cover_number in  (".$coverNumberListInStr.")  
        AND is_active = 1 
        AND is_cover_head = 1 ";
    
        try {
            include "Models/db.php";
         
          $stmt = $conn->prepare($sql);
        
          $result = $conn->query($sql);
         $haji_user_list = $result->fetchAll();
       
         //$message="got received";
         $phone_arr =[];
         if($haji_user_list ){
            foreach($haji_user_list as $eachRow){
               
                 if($eachRow){
                   
                     
                    $sql_sendMessage = "INSERT INTO  message_sent_info  (haji_id,  message) VALUES (:haji_id, :message)";
                    $stmt = $conn->prepare($sql_sendMessage);
                    $stmt->bindParam(':haji_id', $eachRow['id']);
                    $stmt->bindParam(':message', $message);
                   
                    $result = $stmt->execute();
                    $phone_arr[] = $eachRow['phone'];
                    $api_sms->flassMessage($message,$eachRow['phone']);
                    //
                 
                 }
                
               }
         }
        //  $result = $stmt->execute();
          $responseMessage = array(
            "message" => 'send Message to haji  Info',
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
            
       });

       $app->get('/sent-message/all', function (Request $request, Response $response) {
        include "Models/db.php";
          try{
          $sql = "SELECT a.id,a.cover_number as coverNumber,
          a.phone,a.name,a.district_name as districtName ,
          b.created_at as messageDateTime,
          b.message
          FROM haji_info a 
          JOIN message_sent_info b
          ON a.id = b.haji_id
          WHERE a.is_active = 1
          ORDER BY b.created_at DESC  ";
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


         
          
          $app->post('/sent-message/otp', function (Request $request, Response $response) {
            include "Models/db.php";
              try{
                $smsClass = new sendMessageApi();
                $generateOtp = $smsClass->generateNumericOTP(4);
               

                $sth = $conn->prepare("DELETE FROM tpl_otp WHERE mobile_number=:mobile_number");
                $sth->bindParam("mobile_number", $mobileNumber);
                $sth->execute();
                $sql_sendMessage = "INSERT INTO   tpl_otp  (haji_id,otp,mobile_number ) VALUES (:haji_id, :otp,:mobile_number)";
                $stmt = $conn->prepare($sql_sendMessage);
                $stmt->bindParam(':haji_id', $eachRow['id']);
                $stmt->bindParam(':otp', $otp);
                $stmt->bindParam(':mobile_number', $mobile_number);
               
                $result = $stmt->execute();
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


              $app->post('/verify/phoneNumber', function (Request $request, Response $response) {
                include "Models/db.php";
                  try{
                    $smsClass = new sendMessageApi();
                    $generateOtp = $smsClass->generateNumericOTP(4);
                    $sql_sendMessage = "SELECT   tpl_otp  (haji_id,otp,mobile_number ) VALUES (:haji_id, :otp,:mobile_number)";
                    $stmt = $conn->prepare($sql_sendMessage);
                    $stmt->bindParam(':haji_id', $eachRow['id']);
                    $stmt->bindParam(':otp', $otp);
                    $stmt->bindParam(':mobile_number', $mobile_number);
                   
                    $result = $stmt->execute();
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

};