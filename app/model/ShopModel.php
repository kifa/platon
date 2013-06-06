<?php

/*
 * Class ShopModel
 * ShopModel is managing all basic Shop informations and settings.
 */

class ShopModel extends Repository {
    /*
     * Load shop info
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */


    /*
     * Load shipping info
     *  @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */

    /*
     * Load tax value
     */
    public function getTax()
    {
        $tax = $this->getTable('settings')->where('Name',"TAX")->fetch();
        return $tax['Value'];
    }
    
    public function getShopInfo($name)
    {
        $value = $this->getTable('settings')->where('Name', $name)->fetch();
        return $value['Value'];
    }
    
    public function setShopInfo($name, $value)
    {
        if ($name == 'CatalogLayout') {
            $update = array(
                'Value' => "layout" . $value
              );
            
        }
        else {
        $update = array(
          $name => $value  
        );
        }       
        return $this->getTable('settings')->where('Name', $name)->update($update);
    }


    /*
     * Load VAT etc
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */


    /*
     * ETC...
     */
}

?>
