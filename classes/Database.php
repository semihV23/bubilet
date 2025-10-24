<?php

class Database {
    public $db;

    public function __construct()
    {
        $this->db = new SQLite3(__DIR__ . '/../database/database.db');
    }
}

?>