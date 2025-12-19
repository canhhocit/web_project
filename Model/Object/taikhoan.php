<?php
class taikhoan{
    private  $idtaikhoan;
    private $username;
    private $pass;
    public function __construct($idtaikhoan,$username,$pass){
        $this->idtaikhoan = $idtaikhoan;
        $this->username = $username;
        $this->pass = $pass;
    }

    public function get_idtaikhoan(){
        return $this->idtaikhoan;
    }
    public function set_idtaikhoan($idtaikhoan){
        $this->idtaikhoan=$idtaikhoan;
    }
    public function get_username(){
        return $this->username;
    }
    public function set_username($username){
        $this->username=$username;
    }
    public function get_pass(){
        return $this->pass;
    }
    public function set_pass($pass){
        $this->pass=$pass;
    }
    
} 
?>