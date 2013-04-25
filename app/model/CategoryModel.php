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
    public function createCategory($name, $description=NULL, $higher=NULL){
       
        $insert = array(
            'CategoryName' => $name,
            'CategoryDescription' => $description,
            'HigherCategoryID' => $higher
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

    public function updateCategory($id, $name, $desc, $higher=NULL){
        
            $insert = array(
                'CategoryName' => $name,
                'CategoryDescription' => $desc,
                'HigherCategoryID' => $higher
                );        
        return $this->getTable('category')->where('CategoryID',$id)->update($insert);
       
    }

    /*
     * Delete Category
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string */
    
    
    public function deleteCategory($id){
        
        return $this->getTable('category')->where('CategoryID',$id)->delete();
        
    }
    
    
}


