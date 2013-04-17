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
    
    public function insertUser($UsersID,$name,$phone){
        $insert = array(
        //  'UserID' => $id,
                'UsersID' => $UsersID,
                'Name' => $name,
                'PhoneNumber' => $phone,
                );
        
        return $this->getTable('users')->insert($insert);
    }
    
    public function updateUser($UsersID,$name,$phone){
        $update = array(
                'Name' => $name,
                'PhoneNumber' => $phone,
                );
        
    return $this->getTable('users')->where('UsersID',$UsersID)->update($update);
    }
    
    
    public function insertAddress($email,$street,$city,$zip){
        $insert = array(
            'AddressID' => NULL,
            'UsersID' => $email,
            'Street' => $street,
            'ZIPCode' => $zip,
            'City' => $city
        );
        
        return $this->getTable('address')->insert($insert);
    }
    
    public function updateAddress($email,$street,$city,$zip){
        $update = array(
            'Street' => $street,
            'ZIPCode' => $zip,
            'City' => $city
        );
        
        return $this->getTable('address')->where('UsersID',$email)->insert($update);
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