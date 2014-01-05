<?php

/**
 * Class ProductModel
 * ProductModel is used for manipulating and managing products.
 * CRUD operations, etc.
 * @author lukas
 */
class CatalogModel extends Repository {
      /**
     * Load Product Catalog
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */
    public function loadCatalog($catID, $filter=NULL) {   
        //load only published products                
        if($catID==''){
            /*return $this->getDB()->query('
                SELECT *
                FROM product
                JOIN price ON price.ProductID = product.ProductID
                JOIN category ON category.CategoryID = product.CategoryID
                WHERE (product.ProductStatusID=2
                    OR product.ProductStatusID=3)
                    AND product.ProductVariants IS NULL
                    AND (category.CategoryStatus=1
                    OR category.CategoryStatus=2)
                    ');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('product')
                    ->JOIN('price')->ON('price.ProductID = product.ProductID')
                    ->JOIN('category')->ON('category.CategoryID = product.CategoryID')
                    ->WHERE('(product.ProductStatusID = 2
                        OR product.ProductStatusID = 3)
                        AND product.ProductVariants IS NULL
                        AND (category.CategoryStatus = 1
                        OR category.CategoryStatus = 2');
        }
        else
        {  
            //return $this->getDB()->query('SELECT * FROM product JOIN price ON 
            //price.ProductID=product.ProductID 
            //WHERE (product.ProductStatusID="2" OR product.ProductStatusID="3") 
            //AND product.ProductVariants IS NULL AND product.CategoryID=?',$catID);
            if($filter==NULL){
                /*$higher = $this->getTable('category')
                        ->select('CategoryID')
                        ->where('HigherCategoryID',$catID)
                        ->fetch();            */
                $higher = $this -> db
                        ->SELECT('CategoryID')
                        ->FROM('category')
                        ->WHERE('HigherCategoryID = %i', $catID)
                        ->FETCH();

                if($higher == FALSE){
                    /*return $this->getTable('price')
                        ->select('price.*, product.*')
                        ->where('(product.ProductStatusID=2
                            OR product.ProductStatusID=3)
                            AND product.ProductVariants IS NULL
                            AND product.CategoryID=?', $catID);            */
                    $row = $this->db
                            ->SELECT('price.*, product.*')
                            ->FROM('price')
                            ->JOIN('product')->ON('product.ProductID = price.ProductID')
                            ->WHERE('(product.ProductStatusID = 2
                                OR product.ProductStatusID = 3)
                                AND product.ProductVariants IS NULL
                                AND product.CategoryID = %i', $catID);
                }
                else{
                    /*return $this->getDB()->query('
                    SELECT *
                    FROM product
                    JOIN price ON price.ProductID = product.ProductID
                    JOIN category ON category.CategoryID = product.CategoryID
                    WHERE (product.ProductStatusID=2
                        OR product.ProductStatusID=3)
                        AND product.ProductVariants IS NULL
                        AND (product.CategoryID=?
                        OR category.HigherCategoryID=?)
                        ', $catID, $catID);                */
                    $row = $this->db
                            ->SELECT('price.*, product.*, category.*')
                            ->FROM('price')
                            ->JOIN('product')->ON('product.ProductID = price.ProductID')
                            ->JOIN('category')->ON('category.CategoryID = product.CategoryID') 
                            ->WHERE('(product.ProductStatusID = 2
                                OR product.ProductStatusID = 3)
                                AND product.ProductVariants IS NULL
                                AND (product.CategoryID = %i
                                OR category.HigherCategoryID = %i)', $catID, $catID);
                }
            }
            else{
                $higher = $this -> db
                        ->SELECT('CategoryID')
                        ->FROM('category')
                        ->WHERE('HigherCategoryID = %i', $catID)
                        ->FETCH();           
                
                if($higher == FALSE){
                    /*$result = $this->getDB()->query('
                        SELECT *
                        FROM product
                        JOIN price ON price.ProductID = product.ProductID
                        JOIN category ON category.CategoryID = product.CategoryID
                        WHERE (product.ProductStatusID=2
                            OR product.ProductStatusID=3)
                            AND product.ProductVariants IS NULL
                            AND product.CategoryID=?
                            ORDER BY ' . $filter[0] .' '. $filter[1] .'
                            ', $catID);*/
                    $result = $this->db()
                            ->SELECT('price.*, product.*, category.*')
                            ->FROM('price')
                            ->JOIN('product')->ON('product.ProductID = price.ProductID')
                            ->JOIN('category')->ON('category.CategoryID = product.CategoryID') 
                            ->WHERE('(product.ProductStatusID=2
                                    OR product.ProductStatusID=3)
                                    AND product.ProductVariants IS NULL
                                    AND product.CategoryID = %i', $catID)
                            ->orderBy($filter[0] .' '. $filter[1]);
                }
                else{
                    /*
                        $result = $this->getDB()->query('
                        SELECT *
                        FROM product
                        JOIN price ON price.ProductID = product.ProductID
                        JOIN category ON category.CategoryID = product.CategoryID
                        WHERE (product.ProductStatusID=2
                            OR product.ProductStatusID=3)
                            AND product.ProductVariants IS NULL
                            AND (product.CategoryID=?
                            OR category.HigherCategoryID=?)
                            ORDER BY ' . $filter[0] .' '. $filter[1] .'
                            ', $catID, $catID);                   
                     */
                    $result = $this->db()
                            ->SELECT('price.*, product.*, category.*')
                            ->FROM('price')
                            ->JOIN('product')->ON('product.ProductID = price.ProductID')
                            ->JOIN('category')->ON('category.CategoryID = product.CategoryID') 
                            ->WHERE('(product.ProductStatusID=2
                                    OR product.ProductStatusID=3)
                                    AND product.ProductVariants IS NULL
                                    AND (product.CategoryID = %i
                                    OR category.HigherCategoryID = %i', $catID, $catID)
                            ->orderBy($filter[0] .' '. $filter[1]);
                }                
                return $result;
            }
        }
        return $row;
    }
    
     public function loadCatalogBrand($prodID) {            
         /*
         return $this->getDB()->query('
                SELECT *
                FROM product
                JOIN price ON price.ProductID = product.ProductID
                JOIN category ON category.CategoryID = product.CategoryID
                WHERE (product.ProductStatusID=2
                    OR product.ProductStatusID=3)
                    AND product.ProductVariants IS NULL
                    AND (category.CategoryStatus=1
                    OR category.CategoryStatus=2)
                    AND product.ProducerID=?
                    ', $prodID);
        */  
        $row = $this->db
                ->SELECT('price.*, product.*, category.*')
                ->FROM('product')
                ->JOIN('price')->ON('price.ProductID = product.ProductID')
                ->JOIN('category')->ON('category.CategoryID = product.CategoryID')
                ->WHERE('product.ProductStatusID = 2
                    OR product.ProductStatusID = 3)
                    AND product.ProductVariants IS NULL
                    AND (category.CategoryStatus = 1
                    OR category.CategoryStatus = 2)
                    AND product.ProducerID = %i', $prodID);
                  
        return $row;
    }
    
    public function loadCatalogAdmin($catID) {
        
        // load ALL products, even unpublished        
        if($catID==''){                                     
            /*return $this->getTable('price')
                    ->select('price.*, product.*')
                    ->where('(product.ProductStatusID = 1
                        OR product.ProductStatusID = 2
                        OR product.ProductStatusID = 3) 
                        AND product.ProductVariants IS NULL');             
             */
            $result = $this->db
                    ->SELECT('price.*, product.*')
                    ->FROM('price')
                    ->JOIN('product')->ON('product.ProductID = price.ProductID')
                    ->WHERE('(product.ProductStatusID = 1
                        OR product.ProductStatusID = 2
                        OR product.ProductStatusID = 3) 
                        AND product.ProductVariants IS NULL');
            }
        else
        {  
            /*$higher = $this->getTable('category')
                    ->select('CategoryID')
                    ->where('HigherCategoryID',$catID)
                    ->fetch();                                     
             */
            $higher = $this -> db
                        ->SELECT('CategoryID')
                        ->FROM('category')
                        ->WHERE('HigherCategoryID = %i', $catID)
                        ->FETCH(); 
            
            if($higher == FALSE){
                /*return $this->getTable('price')
                    ->select('price.*, product.*')                      
                    ->where('(product.ProductStatusID = 1
                        OR product.ProductStatusID = 2
                        OR product.ProductStatusID = 3)
                        AND product.ProductVariants IS NULL
                        AND product.CategoryID=?', $catID);                           
                 */
                $result = $this->db
                        ->SELECT('price.*, product.*')
                        ->FROM('price')
                        ->JOIN('product')->ON('product.ProductID = price.ProductID')
                        ->WHERE('(product.ProductStatusID = 1
                        OR product.ProductStatusID = 2
                        OR product.ProductStatusID = 3)
                        AND product.ProductVariants IS NULL
                        AND product.CategoryID = %i', $catID);
            }
            else{
                /*return $this->getDB()->query('
                SELECT *
                FROM product
                JOIN price ON price.ProductID = product.ProductID
                JOIN category ON category.CategoryID = product.CategoryID
                WHERE (product.ProductStatusID = 1
                    OR product.ProductStatusID = 2
                    OR product.ProductStatusID = 3)
                    AND product.ProductVariants IS NULL
                    AND (product.CategoryID=?
                    OR category.HigherCategoryID=?)
                    ', $catID, $catID);                
                 */
                $result = $this->db
                        ->SELECT('product.*, price.*, category.*')
                        ->FROM('product')
                        ->JOIN('price')->ON('price.ProductID = product.ProductID')
                        ->JOIN('category')->ON('category.CategoryID = product.CategoryID')
                        ->WHERE('product.ProductStatusID = 1
                                    OR product.ProductStatusID = 2
                                    OR product.ProductStatusID = 3)
                                    AND product.ProductVariants IS NULL
                                    AND (product.CategoryID = %i
                                    OR category.HigherCategoryID = %i)
                                    ', $catID, $catID);
            }
        }
        return $result;
    }
    
    public function loadMainPage(){
        /*return $this->getDB()->query('
            SELECT *
            FROM product
            JOIN price ON price.ProductID = product.ProductID
            JOIN category ON category.CategoryID = product.CategoryID
            WHERE product.ProductStatusID=3
                AND product.ProductVariants IS NULL
                AND (category.CategoryStatus=1
                OR category.CategoryStatus=2)
                ');*/            
        $row = $this->db
                ->SELECT('*')
                ->FROM('product')
                ->JOIN('price')->ON('price.ProductID = product.ProductID')
                ->JOIN('category')->ON('category.CategoryID = product.CategoryID')
                ->WHERE('product.ProductStatusID = 3
                    AND product.ProductVariants IS NULL
                    AND (category.CategoryStatus = 1
                    OR category.CategoryStatus = 2)');
        
        return $row;
    }    
    
    public function loadArchivedCatalog(){
        /*return $this->getTable('price')
                ->select('price.*, product.*')
                ->where('product.ProductStatusID = 0');*/
        $row = $this->db
                ->SELECT('price.*, product.*')
                ->FROM('price')
                ->JOIN('product')->ON('product.ProductID = price.ProductID')
                ->WHERE('product.ProductStatusID = 0');
        
        return $row;
    }
    
    public function loadHeurekaCatalog() {       
        /*return $this->getDB()
                ->query('SELECT * 
                    FROM product 
                    JOIN price ON price.ProductID=product.ProductID 
                    LEFT JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
                    LEFT JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
                    WHERE product.ProductVariants IS NULL
                        AND photo.CoverPhoto = 1'
                    );
         */
        
        $row = $this->db
                ->SELECT('product.*, price.*, photo.*, photoalbum.*')
                ->FROM('product')
                ->JOIN('price')->ON('price.ProductID = product.ProductID')
                ->LEFTJOIN('photoalbum')->ON('photoalbum.ProductID = product.ProductID')
                ->LEFTJOIN('photo')->ON('photoalbum.PhotoAlbumID=photo.PhotoAlbumID')
                ->WHERE('product.ProductVariants IS NULL
                        AND photo.CoverPhoto = 1');
        
        return $row;
    }
    
     /*
     * Count number of product
     */
    public function countProducts()
    {
        /*return $this->getTable('product')
                ->count();
         */
        $row = $this->db
                ->SELECT("COUNT(*)")
                ->FROM('product');
        
        return $row;
    }
    
    public function search($query) {       
        /*return $this->getTable('price')
                ->select('price.FinalPrice, price.SALE, price.SellingPrice, 
                     product.ProductID, product.ProductName, product.PiecesAvailable, 
                     product.ProductStatusID, product.ProductDescription, product.ProductShort')
                 ->where('(product.ProductStatusID=2
                        OR product.ProductStatusID=3)
                        AND product.ProductVariants IS NULL 
                        AND (product.ProductName LIKE ?
                        OR product.ProductShort LIKE ?
                        OR product.ProductDescription LIKE ?)',
                         '%'.$query.'%',
                         '%'.$query.'%',
                         '%'.$query.'%')
                 ->FETCHASSOC('ProductID');*/
        $row = $this->db
                ->SELECT('price.FinalPrice, price.SALE, price.SellingPrice, 
                     product.ProductID, product.ProductName, product.PiecesAvailable, 
                     product.ProductStatusID, product.ProductDescription, product.ProductShort')
                ->FROM('price')
                ->JOIN('product')->ON('product.ProductID = price.ProductID')
                ->WHERE('(product.ProductStatusID=2 ' .
                        'OR product.ProductStatusID=3) ' .
                        'AND product.ProductVariants IS NULL ' .
                        'AND (product.ProductName LIKE %s ' .
                        'OR product.ProductShort LIKE %s ' .
                        'OR product.ProductDescription LIKE %s)',
                         '%'.$query.'%',
                         '%'.$query.'%',
                         '%'.$query.'%')
                ->FETCHASSOC('ProductID');                
                
        return $row;
    }  
}