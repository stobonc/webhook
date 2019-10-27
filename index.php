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

        // Conectando, seleccionando la base de datos
            $link = mysql_connect('development.cu3olghkjz6o.us-east-1.rds.amazonaws.com',
             'dirdesarrollo', 'Asd789***')
                or die('No se pudo conectar: ' . mysql_error());
                echo 'Connected successfully';
            mysql_select_db('seway_development') or die('No se pudo seleccionar la base de datos');

            // Realizar una consulta MySQL
            $query = 'SELECT * FROM user where id="6"';
            $result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());
        
        //creamos el mensaje a mostrar al usuario
        sendMessage(array(
            "fulfillmentText" => "En la ciudad de  ".$city."  la temperatura es de ".$temperatura." grados c".$result[0],
            "source"=> "stobon"
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
$json = file_get_contents("php://input");

$update = json_decode($json, true);
if(isset($update["queryResult"]["action"])) {
    processMessage($update);
}
?>