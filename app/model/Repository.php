<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Repository extends Nette\Object {
     /** @var Nette\Database\Connection */
    protected $db;
    
    public function __construct(\DibiConnection $connection) {
        $this->db = $connection;
    }
    
    /**   
    public function __construct(\DibiConnection $database) {
        $this->database = $database;
    }

    protected function getTable($table) {
        // název tabulky odvodíme z názvu třídy

        return $this->database->table($table);
    }

    /**
     * Vrací všechny řádky z tabulky.
     * @return Nette\Database\Table\Selection
     */
  /**   public function findAll() {
        return $this->getTable();
    }
    
    public function findBy($table, array $by)
    {
        return $this->getTable($table)->where($by);
    }
    
    public function getDB(){
        return $this->database;
    }
*/
}