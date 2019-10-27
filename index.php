<?php
function processMessage($update) {
    //el action getcontent que hemos indicado en el intent (action and parameters)
    if($update["queryResult"]["action"] == "getcontent"){
        //parametros creados en el intent. En nuestro caso vendrá el nombre de la ciudad
        $params = $update["queryResult"]["parameters"];
        //obtenemos el nombre de la ciudad
        $city = $params["geo-city"];
        
        //obtenemos la temperatura
        $temperatura = getTemperatura($city);
        
        //creamos el mensaje a mostrar al usuario
        sendMessage(array(
            "fulfillmentText" => "La temperatura para hoy en ".$city." es de ".$temperatura." grados c",
            "source"=> "javamovil.info"
        ));
    }else{
        //mensaje de error
        sendMessage(array(
            "fulfillmentText"=> "Se ha producido un error",
            "source"=> "example.com"
        ));
    }
}

//obtenemos la temperatura por medio de la api de openweathermap.org
function getTemperatura($city){
    $json_file = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q='.$city.'&APPID=a9784faedcd1ec2ade480136f46a6d4a');
    $vars = json_decode($json_file);
    $cond = $vars->main;
    return $temp_c = (int)($cond->temp - 273.15);
}
 
function sendMessage($parameters) {
    echo json_encode($parameters);
}

//obtenemos el post desde dialogflow
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);
if(isset($update["queryResult"]["action"])) {
    processMessage($update);
}
?>