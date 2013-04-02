<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class UserModel extends Repository{
    
    
    public function findByName($username)
    {
       // return $this->findBy('users', array('UsersID' => $username))->fetch(); 
 
        return $this->getTable('users')->where('UsersID', $username)->fetch();
    }
    
     
    public function userAdd($name, $username, $password) {
        // $userId = $this->userPocet();

        $this->getTable('users')->insert(array(
                                            'UsersID' => $username,
                                            'Password' => Authenticator::calculateHash($password),                                            
                                            'Name' => $name));
    } 
    
     public function setPassword($username, $password) {
       $this->getTable('users')->where('UsersID', $username)->update(array
            ('Password' => Authenticator::calculateHash($password)));
    }
    
    public function insertUser($UsersID,$name,$phone,$address){
        $insert = array(
        //  'UserID' => $id,
                'UsersID' => $UsersID,
                'Name' => $name,
                'PhoneNumber' => $phone,
                'AddressID' => $address
                );
        
        return $this->getTable('users')->insert($insert);
    }
    
    public function insertAddress($id,$street,$city,$zip){
        $insert = array(
            'AddressID' => $id,
            'Street' => $street,
            'ZIPCode' => $zip,
            'City' => $city
        );
        
        return $this->getTable('address')->insert($insert);
    }

    public function countAddress(){
        return $this->getTable('address')->count();
    }
    
    public function isUser($usr){
        $row = $this->getTable('users')->select('users.UserID')->where('UsersID', $usr);
        if(!$row){
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
}