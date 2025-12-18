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

    public function getIdtaikhoan(){
        return $this->idtaikhoan;
    }
    public function setIdtaikhoan($idtaikhoan){
        $this->idtaikhoan=$idtaikhoan;
    }
    public function getUsername(){
        return $this->username;
    }
    public function setUsername($username){
        $this->username=$username;
    }
    public function getPass(){
        return $this->pass;
    }
    public function setPass($pass){
        $this->pass=$pass;
    }
    
} 
?>