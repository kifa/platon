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
                                            'Login' => $username,
                                            'Password' => Authenticator::calculateHash($password),                                            
                                            'FirstName' => $name));
    } 
    
     public function setPassword($username, $password) {
       $this->getTable('users')->where('Login', $username)->update(array
            ('Password' => Authenticator::calculateHash($password)));
    }

}