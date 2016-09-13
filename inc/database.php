<?php
try {
    $db = new PDO("sqlite:movies.sqlite3");

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("CREATE TABLE IF NOT EXISTS movies (id INTEGER PRIMARY KEY AUTOINCREMENT ,
                                                  name TEXT NOT NULL UNIQUE,
                                                  link TEXT NOT NULL,
                                                  rating TEXT NOT NULL,
                                                  year INT NOT NULL)");

} catch(PDOException $e){
    echo $e->getMessage();
}
