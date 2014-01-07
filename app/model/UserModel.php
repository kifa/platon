<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class UserModel extends Repository{
        
    public function findByName($username)
    {       
        /*return $this->getTable('users')
                ->where('UsersID', $username)
                ->fetch();*/
        
        $user = $this->db
                ->SELECT('*')
                ->FROM('users')
                ->WHERE('UsersID LIKE %s', $username)                
                ->FETCH();
        
        return $user;
        
    }
     
    public function userAdd($name, $username, $password) {
        $insert = array(
            'UsersID' => $username,
            'Password' => Authenticator::calculateHash($password),                                            
            'Name' => $name
        );
        
        /*$this->getTable('users')
                ->insert($insert);*/
        $this->db
            ->INSERT('users', $insert)
            ->EXECUTE();

    } 
    
     public function setPassword($username, $password) {
        $update = array(
            'Password' => Authenticator::calculateHash($password)
        );
         
        /*$this->getTable('users')
                ->where('UsersID', $username)
                ->update($update);*/
        $this->db
            ->UPDATE('users')
            ->WHERE('UsersID = $s', $username)
            ->EXECUTE();              
    }
    
    public function insertUser($UsersID,$name,$phone){
        $insert = array(
                'UsersID' => $UsersID,
                'Name' => $name,
                'PhoneNumber' => $phone,
                );
        
        /*return $this->getTable('users')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('users', $insert)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateUser($UsersID,$name,$phone){
        $update = array(
                'Name' => $name,
                'PhoneNumber' => $phone,
                );
        
        /*return $this->getTable('users')
                ->where('UsersID',$UsersID)
                ->update($update);*/
        $row = $this->db
                ->UPDATE("users", $update)
                ->WHERE("UsersID = %i", $UsersID)
                ->EXECUTE();
        
        return $row;
    }
    
    
    public function insertAddress($usersID,$street,$city,$zip){
        $insert = array(
            'AddressID' => NULL,
            'UsersID' => $usersID,
            'Street' => $street,
            'ZIPCode' => $zip,
            'City' => $city
        );
        
        /*return $this->getTable('address')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('address', $insert)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateAddress($usersID,$street,$city,$zip){
        $update = array(
            'UsersID' => $usersID,
            'Street' => $street,
            'ZIPCode' => $zip,
            'City' => $city
        );
        
        /*return $this->getTable('address')
                ->where('UsersID',$usersID)
                ->update($update);*/
        $row = $this->db
                ->UPDATE("address", $update)
                ->WHERE("UsersID = %i", $usersID)
                ->EXECUTE();
        
        return $row;
    }

    public function countAddress(){
        /*return $this->getTable('address')
                ->count();*/
        $row = $this->db
                ->SELECT("COUNT(*)")
                ->FROM('address');
        
        return $row;
    }
    
    public function isUser($usr){
        /*$row = $this->getTable('users')
                ->where('UsersID', $usr)
                ->fetch();*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('users')
                ->WHERE('UsersID = %s', $usr)
                ->FETCH();
        
        if(!$row){
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
}