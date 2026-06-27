<?php
class DB {
    public $con;
    protected $servername;
    protected $username = "root";
    protected $password = "";
    protected $dbname = "Baitaplon";

    function __construct(){
        $this->servername = getenv('DB_HOST') ?: 'localhost';
        $this->con = mysqli_connect($this->servername, $this->username, $this->password);
        mysqli_select_db($this->con, $this->dbname);
        mysqli_query($this->con, "SET NAMES 'utf8'");
    }
}
?>