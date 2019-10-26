<?php
echo 'hola';

$method=$_SERVER['REQUEST_METHOD'];

if($method=="POST"){

$requestBody=file_get_contents('php://input');
$json=json_decode($requestBody);
$text=$json->queryText->parameters->user;

switch ($text) {
    case 'stobon@stobon.com':
        $speech='como estas stobon@';
        break;

     case 'bye':
        $speech='gracias por visitar bye';
        break;
    
    case 'ayuda':
        $speech='si te puedo ayudar';
        break;

    default:
    $speech='fue un placer';
        break;
}

$response=new \stdClass();
$response->speech="";
$response->displayTex="";
$response->source="webhook";
echo json_encode($response);




}
else{

echo"metodo no accesado";
}
?>