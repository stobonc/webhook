<?php
//echo'hola';
   
//$buscar='12345';
  // processMessage($buscar);
function processMessage($buscar) {
    //el action getcontent que hemos indicado en el intent (action and parameters)
    if($buscar["queryResult"]["action"] == "getcontent"){
        //parametros creados en el intent. En nuestro caso vendrá el nombre de la ciudad
        $params = $buscar["queryResult"]["parameters"];
        //obtenemos el nombre de la ciudad
        $tipoConsulta = $params["tipoConsulta"];
            //if($tipoConsulta ==='1'){
                $tipoConsulta='1';
                switch ($tipoConsulta) {
                    case 1:
                        buscarviaje($buscar);
                        break;
                    case 2:

                    break;
                    default:
                        # code...
                        break;
                }
                    
                    //obtenemos la temperatura
        // }        // $temperatura = getTemperatura($city);
    }else{
        //mensaje de error
        sendMessage(array(
            "fulfillmentText"=> "Se ha producido un error",
            "source"=> "example.com"
        ));
    }
}
function buscarviaje($nroViaje){

    $mysqli = new mysqli("bots.cpsguuecnyoz.us-east-2.rds.amazonaws.com", "stobon7120", "7120Stobon","bot");
    if ($mysqli->connect_errno) {
        sendMessage(array(
            "fulfillmentText"=> "Se ha producido un error en la consulta favor reportarlo al tel 1234567",
            "source"=> "example.com"
        ));
        exit;
    }

        $nroViaje= $nroViaje;
        $sql = "SELECT * from viajes where nroViaje=$nroViaje";
               
        sendMessage(array(
                        "fulfillmentText"=>"ESTO ES" .$nroViaje. " este en numero que se envio",
                        "source"=>'SERGIO TOBON'
                    ));

        if (!$resultado = $mysqli->query($sql)) {
            // ¡Oh, no! La consulta falló. 
            // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
            // cómo obtener información del error
           /* echo "Error: La ejecución de la consulta falló debido a: \n";
            echo "Query: " . $sql . "\n";
            echo "Errno: " . $mysqli->errno . "\n";
            echo "Error: " . $mysqli->error . "\n";*/
            sendMessage(array(
                "fulfillmentText"=> "Se ha producido un error en la consulta favor reportarlo", "Query " .$sql. " errno" .$mysqli->errno. " ERROR" .$mysqli->error,
                "source"=> "example.com"
            ));
            exit;
        }

        if ($resultado->num_rows === 0) {
            
            sendMessage(array(
                "fulfillmentText"=> "El numero de viaje " .$nroViaje. " No existe, intenta con otro número!",
                "source"=> "example.com"
            ));
            exit;
        }else{
            $actor = $resultado->fetch_assoc();
            sendMessage(array(
                "fulfillmentText"=> "El numero de viaje " .$actor['nroViaje']. " se encuentra en estado ".$actor['estado']. 
                " por un valor de ".$actor['valorPago']. " Desea consultar otro viaje ingrese el número",
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
$json = file_get_contents("php://input");

$arrayFulfillment = json_decode($json, true);
if(isset($arrayFulfillment["queryResult"]["action"])) {
    processMessage($arrayFulfillment);
}


?>