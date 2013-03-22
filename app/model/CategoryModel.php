<?php


/*
 * Class CategoryModel
 * CategoryModel is used for manipulating with categories.
 * CRUD functions.
 */

class CategoryModel extends Authenticator {
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
        return $this->getTable('Category')->fetch();
    }
    
    /*
     * Create Category
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */
    public function createCategory($id, $name, $description, $higher){
        $insert = array(
            'CategoryID' => $id,
            'CategoryName' => $name,
            'CategoryDescription' => $description,
            'HigherCategoryID' => $higher
        );
        return $this->getTable('Category')->insert($insert);
    }


    /*
     * Update Category
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */



    /*
     * Delete Category
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string */
}

?>
