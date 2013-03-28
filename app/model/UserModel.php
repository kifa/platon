<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class UserModel extends Repository{
    
    
    public function findByName($username)
    {
       // return $this->findBy('users', array('Login' => $username))->fetch(); 
 
        return $this->getTable('users')->where('login', $username)->fetch();
    }
    
     
    public function userAdd($name, $username, $password) {
        // $userId = $this->userPocet();

        $this->getTable('users')->insert(array(
                                            'Email' => $username,
                                            'Password' => Authenticator::calculateHash($password),                                            
                                            'Name' => $name));
    } 
    
     public function setPassword($username, $password) {
       $this->getTable('users')->where('Login', $username)->update(array
            ('Password' => Authenticator::calculateHash($password)));
    }
    
    public function insertUser($email,$name,$phone,$address){
        $insert = array(
                'Email' => $email,
                'Name' => $name,
                'PhoneNumber' => $phone,
                'AddressID' => $address
                );
        
        return $this->getTable('user')->insert($insert);
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
}