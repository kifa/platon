<?php

use Nette\Utils\Strings;
use Nette\Security as NS;

/*
  CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(50) NOT NULL,
  password char(60) NOT NULL,
  role varchar(20) NOT NULL,
  PRIMARY KEY (id)
  );
 */

/**
 * Users authenticator.
 */
class Authenticator extends Nette\Object implements NS\IAuthenticator {

    private $users;

    public function __construct(UserModel $users) {
        $this->users = $users;
    }
    
    /**
     * Performs an authentication.
     * @return Nette\Security\Identity
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials) {
        list($username, $password) = $credentials;
        $row = $this->users->findByName($username);
        
        if (!$row) {
            throw new NS\AuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
        }

        if ($row->password !== self::calculateHash($password, $row->password)) {
            throw new NS\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
        }

        unset($row->password);
        return new NS\Identity($row->id, NULL, $row->toArray());
    }
    
    public function setPassword($id, $password) {
    //$this->getTable('users')->where(array('Login' => $id))->update(array(
      //       'Password' => $this->calculateHash($password)        
        $this->users->findBy(array('login' => $id))->update(array
            ('password' => $this->calculateHash($password)));
    }
    /**
     * Computes salted password hash.
     * @param  string
     * @return string
     */
    public static function calculateHash($password, $salt = null) {
        //if ($salt === null) {
          //  $salt = '$2a$07$' . Nette\Utils\Strings::random(32) . '$';
        //}
        //return crypt($password, $salt);
        
        if($password === Strings::upper($password)){
            $password = Strings::lower($password);
        }
        return crypt($password, $salt ?: '$2a$07$' . Strings::random(22));
    }
    
    public function findByName($username) {
        return $this->getTable('users')->where('Login', $username)->fetch();
    }
    
    public function userPocet() {
        $pocet = $this->findAll()->count();
        return $pocet;
    }

    public function userAdd($name, $username, $password) {
        // $userId = $this->userPocet();

        $this->getTable('users')->insert(array(
                                            'Login' => $username,
                                            'Password' => $this->calculateHash($password),                                            
                                            'FirstName' => $name));
    }      
}
