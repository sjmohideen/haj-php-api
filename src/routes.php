<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

//include "Models/db.php";
return function (App $app) {
    $container = $app->getContainer();
    // echo "welcome Mohi";
    // echo  phpinfo();
    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
   

    $app->get('/customers-data/all', function (Request $request, Response $response) {
        include "Models/db.php";
        
       // $db = new Db();
        //$conn = $db->connect();
       
        
try{
$sql = "SELECT * from customers";
$result = $conn->query($sql);
$customers = $result->fetchAll(PDO::FETCH_OBJ);

$response->getBody()->write(json_encode($customers));
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

$app->post('/customers-data/add', function (Request $request, Response $response, array $args) {
  include "Models/db.php";
  $data = $request->getParsedBody();
  $name = $data["name"];
  $email = $data["email"];
  $phone = $data["phone"];
  //$response->getBody()->write("Hello, $name");
  //  return $response;
  $sql = "INSERT INTO customers (name, email, phone) VALUES (:name, :email, :phone)";
 
  try {
    //$db = new Db();
   // $conn = $db->connect();
     //$response->getBody()->write("Hello, $name");
  //  return $response;
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
 
    $result = $stmt->execute();
 
    $conn = null;
    if($result){
      $responseMessage = array(
        "message" => 'good',
        "success"=> $result
      );
    }
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

};
