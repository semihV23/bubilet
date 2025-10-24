<?php
echo "Merhaba";

foreach(scandir("templates") as $key){
    echo $key . "<br>";
}

new SQLite3(__DIR__ . '/../database/database.db');