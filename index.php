<?php
function processMessage($buscar) {
    //el action getcontent que hemos indicado en el intent (action and parameters)
    if($buscar["queryResult"]["action"] == "getcontent"){
        //parametros creados en el intent. En nuestro caso vendrá el nombre de la ciudad
        $params = $buscar["queryResult"]["parameters"];
        //obtenemos el nombre de la ciudad
        $tipoConsulta=$params["tipoConsulta"];

            if($tipoConsulta ==='1'){
            
                    $nroViaje= $params["nroviaje"];
                    
                    //obtenemos la temperatura
                // $temperatura = getTemperatura($city);

                        $link = mysqli_connect("bots.cpsguuecnyoz.us-east-2.rds.amazonaws.com", "stobon7120", "7120Stobon");

                            mysqli_select_db($link, "bot");
                            $tildes = $link->query("SET NAMES 'utf8'"); //Para que se muestren las tildes
                            $result = mysqli_query($link, "SELECT * FROM viajes where nroViaje=$nroViaje");
                            mysqli_data_seek ($result, 0);
                            $dataResult= mysqli_fetch_array($result);

                            $nroViaje= $dataResult['nroViaje'];
                            $estado= $dataResult['estado'];
                            $valorPago= $dataResult['valorPago'];
                            mysqli_free_result($result);
                            mysqli_close($link);

                            if($nroViaje===""){
                                sendMessage(array(
                                    "fulfillmentText" => "El numero de viaje ".$nroViaje."  Se cuentra en estado ".$estado." por un valor de $".$valorPago. " Si desea consultar otro numero lo puedes ingresar ",
                                    "source"=> "stobon"
                                ));
                            }else{
                                    sendMessage(array(
                        "fulfillmentText" => "El numero de viaje ".$params['nroviaje']." No se encuentra en el sistema ",
                        "source"=> "stobon"
                    ));
                            }
                    
                    //creamos el mensaje a mostrar al usuario
                /* sendMessage(array(
                        "fulfillmentText" => "En la ciudad de  ".$city."  la temperatura es de ".$temperatura." grados c".$name,
                        "source"=> "stobon"
                    ));*/

                    

        }else{
            //mensaje de error
            sendMessage(array(
                "fulfillmentText"=> "Esa informacion no la tengo",
                "source"=> "example.com"
            ));  
        }

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
$json = file_get_contents("php://input");

$arrayFulfillment = json_decode($json, true);
if(isset($arrayFulfillment["queryResult"]["action"])) {
    processMessage($arrayFulfillment);
}


?>