<?php

/**
 * Description of BlogModel
 *
 * @author Lukas
 */
class BlogModel extends Repository {
    
    public function loadCategoryList($id='1'){
        /*return $this->getTable('category')
                ->where('CategoryStatus', $id)
                ->fetchPairs('CategoryID');
         */
        $row = $this->db
                ->SELECT('*')
                ->FROM('category')
                ->WHERE('CategoryStatus = %i', $id)
                ->FETCHASSOC('CategoryID');
                
        return $row;
    }
    
    public function loadCategory($id = NULL){
        if($id == NULL) {
            /*return $this->getTable('category')
                    ->where('CategoryID', 99)
                    ->fetch();*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('category')
                    ->WHERE('CategoryID = 99')
                    ->FETCH();
        }
        else {
            /*return $this->getTable('category')
                    ->where('CategoryID', $id)
                    ->fetch();*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('category')
                    ->WHERE('CategoryID = %i', $id)
                    ->FETCH();
        }
        
        return $row;
    }
    
    public function loadPosts($id = NULL) {
        
        if($id == NULL) {
           /*return $this->getTable('blog')
                   ->order('BlogID DESC')
                   ->fetchPairs('BlogID');                       */
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('blog')
                    ->ORDERBY('BlogID DESC')
                    ->FETCHASSOC('BlogID');
        }
        else {
            /*return $this->getTable('blog')
                    ->where('CategoryID', $id)
                    ->order('BlogID DESC')
                    ->fetchPairs('BlogID');    */
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('blog')
                    ->WHERE('CategoryID = %i', $id)
                    ->ORDERBY('BlogID DESC')
                    ->FETCHASSOC('BlogID');
        }
        
        return $row;
    }
    
    public function loadPost($postid){
        /*return $this->getTable('blog')
                ->where('BlogID', $postid)
                ->fetch();        */
        $row = $this->db
                ->SELECT('*')
                ->FROM('blog')
                ->WHERE('BlogID = %i', $postid)
                ->FETCH();
        
        return $row;
    }

    public function insertPost($name, $categoryID, $content, $product = NULL) {

        $insert = array(
            'ProductID' => $product,
            'BlogName' => $name,
            'BlogContent' => $content,
            'CategoryID' => $categoryID
        );
        
        /*$return = $this->getTable('blog')
                ->insert($insert);
        
        return $return->BlogID;*/
        $row = $this->db
                ->INSERT('blog', $insert);
        
        return $row;
    }
    
    public function updatePost($id, $where, $content) {
        $update = array(
          $where => $content,
        );        
        
        /*return $this->getTable('blog')
                ->where('BlogID', $id)
                ->update($update);*/
        
        $row = $this->db
                ->UPDATE('blog', $update)
                ->WHERE('BlogID = %i', $id);
        
        return $row;
    }

    public function deletePost($id) {
        /*return $this->getTable('blog')
                ->where('BlogID', $id)
                ->delete();*/
        $row = $this->db
                ->DELETE('blog')
                ->WHERE('BlogID = %i', $id);
        
        return $row;        
    }
    
    public function loadCoverPhoto($id){        
        /*$photo = $this->getTable('photo')
                ->select('photo.PhotoURL, photoalbum.BlogID')
                ->where('photoalbum.BlogID',$id)
                ->where('photo.CoverPhoto','1')
                ->fetch();*/
        $photo = $this->db
                ->SELECT('photo.PhotoURL, photoalbum.BlogID')
                ->FROM('photoalbum')
                ->JOIN('photo')->ON('photo.PhotoAlbumID = photoalbum.PhotoAlbumID')
                ->WHERE('photoalbum.BlogID = %i '
                        . 'AND photo.CoverPhoto = 1', $id)
                ->FETCH();
        
        if($photo["PhotoURL"]==""){
            $photo["PhotoURL"]=1;
        }
        
        return $photo["PhotoURL"];
    }
        
    public function loadPhotoAlbum($id){
        if($id==''){
            /*return $this->getTable('PhotoAlbum');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('photoalbum');                    
        }
        else{
            /*return $this->getTable('photo')
                    ->select('photo.*,photoalbum.*')
                    ->where('photoalbum.blogID',$id)
                    ->fetchPairs('PhotoID');            */
            $row = $this->db
                    ->SELECT('photo.*, photoalbum.*')
                    ->FROM('photoalbum')
                    ->JOIN('photo')->ON('photo.PhotoAlbumID = photoalbum.PhotoAlbumID')
                    ->WHERE('photoalbum.BlogID = %i', $id)
                    ->FETCHASSOC('photo.PhotoID');
        }           
        return $row;
    }
 
    public function loadPhotoAlbumID($postid){
        if($postid==''){
            /*$photoalbum = $this->getTable('photoalbum');*/
            $photoalbum = $this->db
                    ->SELECT('*')
                    ->FROM('photoalbum');  
        }
        else{            
            /*$photoalbum = $this->getTable('photoalbum')
                    ->where('photoalbum.blogID',$postid)
                    ->fetch();            */
            $photoalbum = $this->db
                    ->SELECT('*')
                    ->FROM('photoalbum')
                    ->WHERE('photoalbum.BlogID = %i', $postid)
                    ->FETCH();
        }
        
        if($photoalbum == FALSE){
            $photoalbum = array(
                'PhotoAlbumID' => 0
            );
        }
        
        return $photoalbum;
    }
    
    public function loadPhotoAlbumStaticID($spostid){
        if($spostid==''){
            return NULL;
        }
        else{            
            /*$photoalbum = $this->getTable('photoalbum')
                    ->where('photoalbum.StaticTextID',$spostid)
                    ->fetch();            */
            $photoalbum = $this->db
                    ->SELECT('*')
                    ->FROM('photoalbum')
                    ->WHERE('photoalbum.StaticTextID = %i', $spostid)
                    ->FETCH();
        }
        
        if($photoalbum == FALSE){
            $photoalbum = array(
                'PhotoAlbumID' => 0
            );
        }
        
        return $photoalbum;
    }
    
    public function loadPhotoAlbumStatic($spostid){
        if($spostid==''){
            /*return $this->getTable('photoalbum');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('photoalbum');
        }
        else{
            /*return $this->getTable('photo')
                    ->select('photo.*,photoalbum.*')
                    ->where('photoalbum.StaticTextID', $spostid)
                    ->fetchPairs('PhotoID');            */
            $row = $this->db
                    ->SELECT('photo.*, photoalbum.*')
                    ->FROM('photoalbum')
                    ->JOIN('photo')->ON('photo.PhotoAlbumID = photoalbum.PhotoAlbumID')
                    ->WHERE('photoalbum.StaticTextID = %i', $spostid)
                    ->FETCHASSOC('PhotoID');
        }
        return $row;
    }
    
    public function search($query) {
        /*return $this->getTable('blog')
                ->where('BlogName LIKE ?
                    OR BlogContent LIKE ?',
                        '%'.$query.'%',
                        '%'.$query.'%')
                ->fetchPairs('BlogID');            */
        $row = $this->db
                ->SELECT('*')
                ->FROM('blog')
                ->WHERE('BlogName LIKE ?'
                        . 'OR BlogContent LIKE ?',
                        '%'.$query.'%',
                        '%'.$query.'%')
                ->FETCHASSOC('BlogID');
        
        return $row;
    }
}