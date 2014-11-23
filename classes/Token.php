<?php


class Token {
    
     public static function generate(){
        return Session::put(Config::get('session/token_name'), md5(uniqid()));
    }
    
    public static function check($token){
        $tokenname = Config::get('session/token_name');
        
        if(Session::exists($tokenname)){
            Session::delete($tokenname);
            return true;
        }
        
        return false;
    }
}
