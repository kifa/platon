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

        if ($row->Password == self::calculateHash($password, $row->Password)) {
            throw new NS\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
        }

        unset($row->Password);

        return new NS\Identity($row->Login, NULL, $row->toArray());
    }

    /**
     * Computes salted password hash.
     * @param  string
     * @return string
     */
    public static function calculateHash($password, $salt = null) {

        if ($salt === null) {
            $salt = '$2a$07$' . Nette\Utils\Strings::random(32) . '$';
        }

        return crypt($password, $salt);
    }

}
