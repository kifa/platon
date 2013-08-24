<?php

/**
 * Description of BlogModel
 *
 * @author Lukas
 */
class BlogModel extends Repository {
    
    public function loadCategoryList($id='1'){
        return $this->getTable('category')
                ->where('CategoryStatus', $id)
                ->fetchPairs('CategoryID');
    }
    
    public function loadCategory($id = NULL){
        if($id == NULL) {
            return $this->getTable('category')
                    ->where('CategoryID', 99)
                    ->fetch();
        }
        else {
            return $this->getTable('category')
                    ->where('CategoryID', $id)
                    ->fetch();
        }
    }
    
    public function loadPosts($id = NULL) {
        
        if($id == NULL) {
           return $this->getTable('blog')
                   ->order('BlogID DESC')
                   ->fetchPairs('BlogID');                       
        }
        else {
            return $this->getTable('blog')
                    ->where('CategoryID', $id)
                    ->order('BlogID DESC')
                    ->fetchPairs('BlogID');    
        }
    }
    
    public function loadPost($postid){
        return $this->getTable('blog')
                ->where('BlogID', $postid)
                ->fetch();        
    }

    public function insertPost($name, $categoryID, $content, $product = NULL) {

        $insert = array(
            'ProductID' => $product,
            'BlogName' => $name,
            'BlogContent' => $content,
            'CategoryID' => $categoryID
        );
        
        $return = $this->getTable('blog')
                ->insert($insert);
        
        return $return->BlogID;
    }
    
    public function updatePost($id, $where, $content) {
        $update = array(
          $where => $content,
        );        
        
        return $this->getTable('blog')
                ->where('BlogID', $id)
                ->update($update);
    }

    public function deletePost($id) {
        return $this->getTable('blog')
                ->where('BlogID', $id)
                ->delete();
    }
    
    public function loadCoverPhoto($id){        
        $photo = $this->getTable('photo')
                ->select('photo.PhotoURL, photoalbum.BlogID')
                ->where('photoalbum.BlogID',$id)
                ->where('photo.CoverPhoto','1')
                ->fetch();
        
        if($photo["PhotoURL"]==""){
            $photo["PhotoURL"]=1;
        }
        
        return $photo["PhotoURL"];
    }
        
    public function loadPhotoAlbum($id){
        if($id==''){
            return $this->getTable('PhotoAlbum');
        }
        else{
            return $this->getTable('photo')
                    ->select('photo.*,photoalbum.*')
                    ->where('photoalbum.blogID',$id)
                    ->fetchPairs('PhotoID');            
        }                                        
    }
 
    public function loadPhotoAlbumID($postid){
        if($postid==''){
            $photoalbum = $this->getTable('photoalbum');
        }
        else{            
            $photoalbum = $this->getTable('photoalbum')
                    ->where('photoalbum.blogID',$postid)
                    ->fetch();            
        }
        
        if($photoalbum == FALSE){
            $photoalbum = array(
                'PhotoAlbumID' => 0
            );
        }
        
        return $photoalbum;
    }
    
    public function loadPhotoAlbumStatic($postid){
        if($postid==''){
            return $this->getTable('photoalbum');
        }
        else{
            return $this->getTable('photo')
                    ->select('photo.*,photoalbum.*')
                    ->where('photoalbum.StaticTextID', $postid)
                    ->fetchPairs('PhotoID');            
        }
    }
    
    public function search($query) {
        return $this->getTable('blog')
                ->where('BlogName LIKE ?
                    OR BlogContent LIKE ?',
                        '%'.$query.'%',
                        '%'.$query.'%')
                ->fetchPairs('BlogID');            
    }
}