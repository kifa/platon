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
    
    public function loadCategory($id = NULL){
        if($id == NULL) {
            return $this->getTable('category')->where('CategoryID', 99)->fetch();
        }
        else {
            return $this->getTable('category')->where('CategoryID', $id)->fetch();
        }
    }
    
    public function loadPosts($id = NULL) {
        
        if($id == NULL) {
        return $this->getTable('blog')->fetchPairs('BlogID');
        }
        else {
        return $this->getTable('blog')->where('CategoryID', $id)->fetchPairs('BlogID');    
        }
    }
    
    public function loadPost($postid){
        return $this->getTable('blog')->where('BlogID',$postid)->fetch();
        
    }

    public function insertPost($name,  $categoryID, $content, $desc) {

        $insert = array(
          'BlogName' => $name,
            'BlogDescription' => $desc,
          'BlogContent' => $content,
          'CategoryID' => $categoryID
        );

        $return = $this->getTable('blog')->insert($insert);
        return $return->BlogID;
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