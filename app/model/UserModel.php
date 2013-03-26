<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class UserModel extends Repository{
    public function findByName($username)
    {
        return $this->findBy(array('login' => $username))->fetch(); 
    }
}