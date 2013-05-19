<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BlogModel
 *
 * @author Lukas
 */
class BlogModel extends Repository {
    
    public function loadCategoryList(){
        return $this->getTable('Category')->where('CategoryStatus', $id)->fetchPairs('CategoryID');
    }
    
    public function loadCategory($id){
        return $this->getTable('Category')->where('CategoryID', $id)->fetch();
    }
    
    public function loadPosts($id) {
        
        if($id == NULL) {
        return $this->getTable('Blog')->fetchPairs('BlogID');
        }
        else {
        return $this->getTable('Blog')->where('CategoryID', $id)->fetchPairs('BlogID');    
        }
    }
    
    public function insertPost($name, $content, $categoryID) {

        $insert = array(
          'BlogName' => $name,
          'BlogContent' => $content,
          'BlogCategory' => $categoryID
        );

        return $this->getTable('Blog')->insert($insert);
    }
    
    public function updatePost($id, $name, $content, $categoryID) {
        $insert = array(
          'BlogName' => $name,
          'BlogContent' => $content,
          'BlogCategory' => $categoryID
        );

        return $this->getTable('Blog')->where('BlogID', $id)->insert($insert);
    }

    public function deletePost($id) {
        return $this->getTable('Blog')->where('BlogID', $id)->delete();
    }
    
    
}