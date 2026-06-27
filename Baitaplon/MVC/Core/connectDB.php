<?php 
    class connectDB{
        public $con;
        function __construct()
        {
            $db_host = getenv('DB_HOST') ?: 'localhost';
            $this->con=mysqli_connect($db_host,'root','','Baitaplon');
            mysqli_query($this->con,"SET NAMES 'utf8'");
        }
    }
?>