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
    
    public function loadCategoryList($id='1'){
        return $this->getTable('category')->where('CategoryStatus', $id)->fetchPairs('CategoryID');
    }
    
    public function loadCategory($id){
        return $this->getTable('category')->where('CategoryID', $id)->fetch();
    }
    
    public function loadPosts($id) {
        
        if($id == NULL) {
        return $this->getTable('blog')->fetchPairs('BlogID');
        }
        else {
        return $this->getTable('blog')->where('CategoryID', $id)->fetchPairs('BlogID');    
        }
    }
    
    public function loadPost($id){
        return $this->getTable('blog')->where('BlogID',$id)->fetch();
        
    }

    public function insertPost($name, $content, $categoryID) {

        $insert = array(
          'BlogName' => $name,
          'BlogContent' => $content,
          'BlogCategory' => $categoryID
        );

        return $this->getTable('blog')->insert($insert);
    }
    
    public function updatePost($id, $name, $content, $categoryID) {
        $update = array(
          'BlogName' => $name,
          'BlogContent' => $content,
          'BlogCategory' => $categoryID
        );

        return $this->getTable('blog')->where('BlogID', $id)->update($update);
    }

    public function deletePost($id) {
        return $this->getTable('blog')->where('BlogID', $id)->delete();
    }
    
    
}