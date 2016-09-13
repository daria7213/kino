<?php
include "simple_html_dom.php";
include "database.php";

function getMoviesFromHtml($url){
    $results = [];

    $html = file_get_contents($url);
    $html = iconv('windows-1251', 'UTF-8',$html);

    $html = str_get_html($html);
    $items = $html->find('div.item');

    foreach($items as $item){
        $link = $item->find("div.info .name a");
        $name = $link[0]->plaintext;
        $rating = $item->find("div.WidgetStars")[0]->attr["value"];

        $results[] = [
            "name" => $name,
            "link" => "https://www.kinopoisk.ru".$link[0]->href,
            "rating" => $rating  == null ? 0 : $rating
        ];
    }

    return $results;
}

function validate($year){
    $result = is_numeric($year);
    return $result;
}

function saveMoviesToDB($movies, $year)
{
    global $db;
    try{
        foreach ($movies as $movie) {
            $stmt = $db->prepare("INSERT OR REPLACE INTO movies VALUES (NULL,:name,:link,:rating,:year)");
            $stmt->execute(array(
                ":name" => $movie["name"],
                ":link" => $movie["link"],
                ":rating" => $movie["rating"],
                ":year" => $year
            ));
        }
    } catch (Exception $e){
        echo $e->getMessage();
    }
}

function checkYearInDB($year){
    global $db;
    try {
        $stmt = $db->prepare("SELECT year FROM movies WHERE year=:year");
        $stmt->execute(array(":year" => $year));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result == null){
            return false;
        }
        return true;
    } catch (PDOException $e){
        echo $e->getMessage();
    }
}

function getMoviesFromDB($year){
    global $db;

    try {
        $stmt = $db->prepare("SELECT name,link,rating FROM movies WHERE year=:year");
        $stmt->execute(array(":year" => $year));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $output = json_encode($result);
        return $result;
    } catch (PDOException $e){
        return null;
    }
}