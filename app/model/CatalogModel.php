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
         /*   return $this->getTable('price')
                    ->select('price.*, product.*')
                    ->where('(product.ProductStatusID=2
                       OR product.ProductStatusID=3)
                        AND product.ProductVariants IS NULL');             */
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
                    ');
        }
        else
        {  
            //return $this->getDB()->query('SELECT * FROM product JOIN price ON 
            //price.ProductID=product.ProductID 
            //WHERE (product.ProductStatusID="2" OR product.ProductStatusID="3") 
            //AND product.ProductVariants IS NULL AND product.CategoryID=?',$catID);
            if($filter==NULL){
                $higher = $this->getTable('category')
                        ->select('CategoryID')
                        ->where('HigherCategoryID',$catID)
                        ->fetch();            

                if($higher == FALSE){
                    return $this->getTable('price')
                        ->select('price.*, product.*')
                        ->where('(product.ProductStatusID=2
                            OR product.ProductStatusID=3)
                            AND product.ProductVariants IS NULL
                            AND product.CategoryID=?', $catID);            
                }
                else{
                    return $this->getDB()->query('
                    SELECT *
                    FROM product
                    JOIN price ON price.ProductID = product.ProductID
                    JOIN category ON category.CategoryID = product.CategoryID
                    WHERE (product.ProductStatusID=2
                        OR product.ProductStatusID=3)
                        AND product.ProductVariants IS NULL
                        AND (product.CategoryID=?
                        OR category.HigherCategoryID=?)
                        ', $catID, $catID);                
                }
            }
            else{
                $higher = $this->getTable('category')
                        ->select('CategoryID')
                        ->where('HigherCategoryID',$catID)
                        ->fetch();            
                
                if($higher == FALSE){
                    /*$result = $this->getTable('price')
                        ->select('price.*, product.*')
                        ->where('(product.ProductStatusID=2
                            OR product.ProductStatusID=3)
                            AND product.ProductVariants IS NULL
                            AND product.CategoryID=?
                            ORDER BY ? ?', $catID, $filter[0], $filter[1]);            */
                    $result = $this->getDB()->query('
                        SELECT *
                        FROM product
                        JOIN price ON price.ProductID = product.ProductID
                        JOIN category ON category.CategoryID = product.CategoryID
                        WHERE (product.ProductStatusID=2
                            OR product.ProductStatusID=3)
                            AND product.ProductVariants IS NULL
                            AND product.CategoryID=?
                            ORDER BY ' . $filter[0] .' '. $filter[1] .'
                            ', $catID);
                }
                else{
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
                }                
                return $result;
            }
        }
    }
    
     public function loadCatalogBrand($prodID) {            
          /*return $this->getTable('price')
                  ->select('price.*, product.*')
                  ->where('(product.ProductStatusID=2
                        OR product.ProductStatusID=3)
                        AND product.ProductVariants IS NULL
                        AND product.ProducerID=?', $prodID);
          */
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
    }
    
    public function loadCatalogAdmin($catID) {
        
        // load ALL products, even unpublished        
        if($catID==''){            
            //return $this->getDB()->query('SELECT * FROM product JOIN price 
                //ON price.ProductID=product.ProductID 
                //WHERE product.ProductVariants IS NULL');                              
            return $this->getTable('price')
                    ->select('price.*, product.*')
                    ->where('(product.ProductStatusID = 1
                        OR product.ProductStatusID = 2
                        OR product.ProductStatusID = 3) 
                        AND product.ProductVariants IS NULL');
            }
        else
        {  
            //return $this->getTable('price')
            //        ->select('price.*, product.*')
            //        ->where('product.ProductVariants IS NULL
            //            AND product.CategoryID=?', $catID);
            $higher = $this->getTable('category')
                    ->select('CategoryID')
                    ->where('HigherCategoryID',$catID)
                    ->fetch();            
            
            if($higher == FALSE){
                return $this->getTable('price')
                    ->select('price.*, product.*')                      
                    ->where('(product.ProductStatusID = 1
                        OR product.ProductStatusID = 2
                        OR product.ProductStatusID = 3)
                        AND product.ProductVariants IS NULL
                        AND product.CategoryID=?', $catID);          
            }
            else{
                return $this->getDB()->query('
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
            }
        }
    }
    
    public function loadMainPage(){
        return $this->getDB()->query('
            SELECT *
            FROM product
            JOIN price ON price.ProductID = product.ProductID
            JOIN category ON category.CategoryID = product.CategoryID
            WHERE product.ProductStatusID=3
                AND product.ProductVariants IS NULL
                AND (category.CategoryStatus=1
                OR category.CategoryStatus=2)
                ');
    }    
    
    public function loadArchivedCatalog(){
        return $this->getTable('price')
                ->select('price.*, product.*')
                ->where('product.ProductStatusID = 0');
    }
    
    public function loadHeurekaCatalog() {       
        return $this->getDB()
                ->query('SELECT * 
                    FROM product 
                    JOIN price ON price.ProductID=product.ProductID 
                    LEFT JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
                    LEFT JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
                    WHERE product.ProductVariants IS NULL
                        AND photo.CoverPhoto = 1'
                    );
    }
    
     /*
     * Count number of product
     */
    public function countProducts()
    {
        return $this->getTable('product')
                ->count();
    }
    
    public function search($query) {       
        return $this->getTable('price')
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
                 ->fetchPairs('ProductID');                 
    }  
}