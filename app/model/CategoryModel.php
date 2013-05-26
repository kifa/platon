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
        return $this->getTable('Category')->fetchPairs('CategoryID');
    }
    /*
     * Load Category info
     */
    
    public function loadCategory($id){
        return $this->getTable('Category')->where('CategoryID', $id)->fetch();
    }
    
    public function loadCategory2($id){
        return $this->getTable('Category')->where('CategoryID', $id);
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
            'CategoryDescription' => $description,
            'HigherCategoryID' => $higher,
            'CategoryPhoto' => $photo
        );
        $row = $this->getTable('Category')->insert($insert);
        return $row->CategoryID;
        
    }


    /*
     * Update Category
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */

    public function updateCategory($id, $name, $desc, $higher=NULL, $status, $photo=NULL){
        
            $update = array(
                'CategoryName' => $name,
                'CategoryDescription' => $desc,
                'HigherCategoryID' => $higher,
                'CategoryStatus' => $status,
                'CategoryPhoto' => $photo
                );        
        return $this->getTable('category')->where('CategoryID',$id)->update($update);
       
    }

    /*
     * Delete Category
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string */
    
    
    public function deleteCategory($id){
        
        return $this->getTable('category')->where('CategoryID',$id)->delete();
        
    }
    
      public function setCategoryStatus($id, $status){
        
            $update = array(

                'CategoryStatus' => $status
                );        
        return $this->getTable('category')->where('CategoryID',$id)->update($update);
       
    }
    
    public function addPhoto($id, $name) {
        $update = array('CategoryPhoto' => $name);
        return $this->getTable('category')->where('CategoryID', $id)->update($update);
    }
    
    public function deletePhoto($id) {
        $update = array('CategoryPhoto' => NULL);
        return $this->getTable('category')->where('CategoryID', $id)->update($update);
    }
}


