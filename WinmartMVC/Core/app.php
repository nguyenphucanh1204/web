<?php
class App {
    protected $controller = "Home";
    protected $action = "Index"; // [ĐÃ SỬA] Đổi từ "SayHi" thành "Index"
    protected $params = [];

    function __construct(){
        $arr = $this->UrlProcess();
        
        // Xử lý Controller
        if(file_exists("./Controllers/".$arr[0].".php")){
            $this->controller = $arr[0];
            unset($arr[0]);
        }
        require_once "./Controllers/". $this->controller .".php";
        $this->controller = new $this->controller;

        // Xử lý Action
        if(isset($arr[1])){
            if(method_exists($this->controller, $arr[1])){
                $this->action = $arr[1];
            }
            unset($arr[1]);
        }

        // Xử lý Params
        $this->params = $arr?array_values($arr):[];
        
        // Gọi hàm
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    function UrlProcess(){
        if(isset($_GET["url"])){
            return explode("/", filter_var(trim($_GET["url"], "/")));
        }
        // Mặc định gọi Controller Home và Action Index
        return ["Home", "Index"]; 
    }
}
?>