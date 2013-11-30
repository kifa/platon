<?php

/*
 * Class CategoryModel
 * CategoryModel is used for manipulating with categories.
 * CRUD functions.
 */

class CategoryModel extends Repository {
    /*
     * Load Categories
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */
    public function loadCategoryList(){
        $status = array(1, 2);
        /*return $this->getTable('category')
                ->where('CategoryStatus', $status)
                ->fetchPairs('CategoryID');         
         */
        $row = $this->db                
                ->SELECT('*')
                ->FROM('category')
                ->WHERE('CategoryStatus = 1 '
                        . 'OR CategoryStatus = 2')
                ->FETCHASSOC('CategoryID');
        
        return $row;
    }
    
    public function loadCategoryListAdmin(){
  
        /*return $this->getTable('category')
                ->order('CategoryName ASC')
                ->where('CategoryStatus!=', 4)
                ->fetchPairs('CategoryID');*/
        
        $row = $this->db
                ->SELECT('*')
                ->FROM('category')
                ->WHERE('CategoryStatus != 4')
                ->orderBy('CategoryName ASC')
                ->FETCHASSOC('CategoryID');
        
        return $row;
    }
    
    /*
     * Load Category info
     */    
    public function loadCategory($id){
        /*return $this->getTable('category')
                ->where('CategoryID', $id)
                ->fetch();*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('category')
                ->WHERE('CategoryID = %i', $id)
                ->FETCH();
                
        return $row;
    }    
    
    public function loadChildCategoryList($catID){
        /*return $this->getTable('category')
                    ->select('CategoryID, CategoryName')
                     ->where('CategoryStatus',1)
                    ->where('HigherCategoryID',$catID)
                    ->fetchPairs('CategoryID');*/
        $row = $this->db
                ->SELECT('CategoryID, CategoryName')
                ->FROM('category')
                ->WHERE('CategoryStatus = 1 ' .
                    'AND HigherCategoryID = %i', $catID)
                ->FETCHASSOC('CategoryID');
        
        return $row;
    }
    
    public function loadChildCategoryListAdmin($catID){
        /*return $this->getTable('category')
                    ->select('CategoryID, CategoryName')
                    ->where('HigherCategoryID',$catID)
                    ->fetchPairs('CategoryID');*/
        $row = $this->db
                ->SELECT('CategoryID, CategoryName')
                ->FROM('category')
                ->WHERE('HigherCategoryID = %i', $catID)
                ->FETCHASSOC('CategoryID');
                
        return $row;
    }
    
    public function loadFeaturedCategories() {
        /*$row = $this->getTable('category')
                ->where('CategoryStatus',2)
                ->fetchPairs('CategoryID');*/
        $categoryStatus = 2;
        
        $row = $this->db
                ->SELECT('*')
                ->FROM('category')
                ->WHERE('CategoryStatus = %i', $categoryStatus)
                ->FETCHASSOC('CategoryID');
                
        if(!$row) return NULL;
        return $row;
    }

    /*
     * Create Category
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */
    public function createCategory($name, $description=NULL, $higher=NULL, $photo=NULL){
       
        $insert = array(
            'CategoryName' => $name,
            'CategorySeoName' => $name,
            'CategoryDescription' => $description,
            'HigherCategoryID' => $higher,
            'CategoryPhoto' => $photo,
            'CategoryStatus' => 0
        );
        
        /*$row = $this->getTable('category')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('category', $insert)
                ->EXECUTE();
        
        return $row->CategoryID;        
    }

    /*
     * Update Category
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */

    public function updateCategory($id, $name, $desc=NULL, $higher=NULL, $status=1, $photo=NULL){
        
        $update = array(
            'CategoryName' => $name,
            'CategoryDescription' => $desc,
            'HigherCategoryID' => $higher,
            'CategoryStatus' => $status,
            'CategoryPhoto' => $photo
            );        
        
        /*return $this->getTable('category')
                ->where('CategoryID',$id)
                ->update($update);       */
        
        $row = $this->db
                ->UPDATE('category', $update)
                ->WHERE('CategoryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateCat($id, $name, $value) {
        $update = array(
            $name => $value
            );        
        
        /*return $this->getTable('category')
                ->where('CategoryID',$id)
                ->update($update);       */
        
        $row = $this->db
                ->UDPATE('category', $update)
                ->WHERE('CategoryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }

        public function updateCategoryParent($id, $higher){
        
        $update = array(
            'HigherCategoryID' => $higher
            );        

        /*return $this->getTable('category')
                ->where('CategoryID',$id)
                ->update($update);       */
        
        $row = $this->db
                ->UPDATE('category', $update)
                ->WHERE('CategoryID = %i', $id)
                ->EXECUTE();
        
        return $row;        
    }
    
    /*
     * Update Category Description
     */
    public function updateCategoryDesc($id, $desc){
            
        $update = array(                
            'CategoryDescription' => $desc         
            );        

        /*return $this->getTable('category')
                ->where('CategoryID', $id)
                ->update($update);       */
        $row = $this->db
                ->UPDATE('category', $update)
                ->WHERE('CategoryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }

    /*
     * Delete Category
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string */    
    public function deleteCategory($catID){
        
        /*return $this->getTable('category')
                ->where('CategoryID',$catID)
                ->delete();        */
        $row = $this->db
                ->DELETE('category')
                ->WHERE('CategoryID = %i', $catID)
                ->EXECUTE();
                
        return $row;
    }
    
    public function setCategoryStatus($id, $status){

        $update = array(
            'CategoryStatus' => $status
            );        
        
        /*return $this->getTable('category')
                ->where('CategoryID',$id)
                ->update($update);*/
        
        $row = $this->db
                ->UDPATE('category', $update)
                ->WHERE('CategoryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function addPhoto($id, $name) {
        $update = array(
            'CategoryPhoto' => $name
            );
        
        /*return $this->getTable('category')
                ->where('CategoryID', $id)
                ->update($update);*/
        
        $row = $this->db
                ->UPDATE('category', $update)
                ->WHERE('CategoryID = %i', $id)
                ->EXECUTE();
        
        return $row;       
    }
    
    public function deletePhoto($id) {
        $update = array(
            'CategoryPhoto' => NULL
            );
        
        /*return $this->getTable('category')
                ->where('CategoryID', $id)
                ->update($update);*/
        
        $row = $this->db
                ->UPDATE('category', $update)
                ->WHERE('CategoryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function getStatusName($categorystatusid) {
        /*$row = $this->getTable('categorystatus')
                ->where('CategoryStatusID', $categorystatusid)
                ->fetch();*/
        
        $row = $this->db
                ->SELECT('*')
                ->FROM('categorystatus')
                ->WHERE('CategoryStatusID = %i', $categorystatusid)
                ->FETCH();
        
        return $row->CategoryStatusName;
    }
    
    public function search($query) {
        /*return $this->getTable('category')
                ->where('CategoryName LIKE ?
                    OR CategoryDescription LIKE ?', 
                        '%'.$query.'%',
                        '%'.$query.'%')
                ->fetchPairs('CategoryID');*/
        
        $row = $this->db
                ->SELECT('*')
                ->FROM('category')
                ->WHERE('CategoryName LIKE ?
                    OR CategoryDescription LIKE ?', 
                        '%'.$query.'%',
                        '%'.$query.'%')
                ->FETCHASSOC('CategoryID');
        
        return $row;
    }
}