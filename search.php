<?php
include "inc/functions.php";
header( "Content-Type: application/json" );

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $results = null;

    if(isset($_POST["year"])){
        $year = $_POST["year"];
        if(!validate($year)){
            echo json_encode(array("error" => "Некорректный ввод, введите число."));
            exit;
        }
        $url = "https://www.kinopoisk.ru/lists/m_act[year]/".$_POST["year"]."/";

        if(checkYearInDB($year)){
            $results = getMoviesFromDB($year);
        }

        if($results == null){
            $results = getMoviesFromHtml($url);
            if(empty($results)){
                echo json_encode(array("error" => "Ничего не найдено."));
                exit;
            }
            saveMoviesToDB($results, $year );
        }
        $output = json_encode($results, JSON_UNESCAPED_UNICODE);

        echo $output;
        exit;
    }
}