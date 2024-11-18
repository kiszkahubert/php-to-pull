<?php
namespace klasy;
use PDO;
use PDOException;
class BazaPDO{
    private $dbh;
    public function __construct($serwer, $user, $pass) {
        try {
            $this->dbh = new PDO($serwer, $user, $pass,
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
        } catch (PDOException $e) {
            print "error: " . $e->getMessage() . "<br/>"; die();
        }
    }
    function __destruct() {
        $this->dbh=null;
    }
    public function select($sql) {
        foreach ($this->dbh->query($sql) as $row) {
            print_r($row);
        }
    }
}
