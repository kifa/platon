<?php

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
        // return $this->getTable('blog')->order('BlogID DESC')->fetchPairs('BlogID');
        
        return $this->getDB()->query('SELECT * FROM blog JOIN photoalbum ON blog.BlogID=photoalbum.BlogID 
                JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
                WHERE Photo.CoverPhoto="1" ORDER BY blog.BlogID DESC');
        }
        else {
        return $this->getTable('blog')->where('CategoryID', $id)->order('BlogID DESC')->fetchPairs('BlogID');    
         /*   return $this->getDB()->query('SELECT * FROM blog JOIN photoalbum ON blog.BlogID=photoalbum.BlogID 
            JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
             WHERE Photo.CoverPhoto="1" blog.CategoryID=?',$id); */
        }
    }
    
    public function loadPost($postid){
      //  return $this->getTable('blog')->where('BlogID',$postid)->fetch();
         return $this->getDB()->query('SELECT * FROM blog
JOIN photoalbum ON blog.BlogID=photoalbum.BlogID 
JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID
WHERE blog.BlogID=?',$postid)->fetch();
        
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
    

    public function updatePost($id, $where, $content) {
        $update = array(
          $where => $content,
        );

        return $this->getTable('blog')->where('BlogID', $id)->update($update);
    }

    public function deletePost($id) {
        return $this->getTable('blog')->where('BlogID', $id)->delete();
    }
    
    public function loadCoverPhoto($id){
        return $this->getTable('photo')->select('photo.PhotoURL, photoalbum.BlogID')->where('photoalbum.BlogID',$id)
                ->where('photo.CoverPhoto','1')->fetch();
    }
    
     public function loadPhotoAlbum($id){
        if($id==''){
            return $this->getTable('PhotoAlbum');
        }
        else{
            //return $this->getTable('PhotoAlbum')->where('ProductID',$id);
            $row = $this->getDB()->query('SELECT * FROM blog JOIN photoalbum 
                ON blog.BlogID=photoalbum.BlogID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
                WHERE Blog.BlogID=?',$id); 
           // dump($row);
            return $row;
        }
    }
}