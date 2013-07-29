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
        $tax = $this->getTable('settings')->where('SettingName',"TAX")->fetch();
        return $tax['Value'];
    }
    
    public function getShopInfo($name)
    {
        if($name != '') {
            $value = $this->getTable('settings')->where('SettingName', $name)->fetch();
            return $value['Value'];
        }
        else {
            return $this->getTable('settings')->fetchPairs('SettingID');
        }
    }
    
     public function getShopInfoPublic() {
         $param = array('Name', 'Description', 'CompanyAddress', 'TAX', 'OrderMail', 'ContactMail', 'ContactPhone', 'InvoicePrefix', 'GA');
        return $this->getTable('settings')->where('SettingName', $param)->fetchPairs('SettingID'); 
     }
    
    public function setShopInfo($name, $value)
    {
        if ($name == 'ShopLayout') {
            $update = array(
                'Value' => "layout" . $value
              );
            
        }
        
        elseif ($name == 'ProductLayout') {
            $update = array(
                'Value' => "product" . $value
              );
            
        }
        elseif ($name == 'ProductMiniLayout') {
            $update = array(
                'Value' => "ProductMini" . $value
              );
            
        }
        
        else {
            $update = array(
              'Value' => $value  
            );
        }       
        return $this->getTable('settings')->where('SettingName', $name)->update($update);
    }
    
    public function setShopInfoByID($id, $value)
    {        
            $update = array(
              'Value' => $value  
            );
       
        return $this->getTable('settings')->where('SettingID', $id)->update($update);
    }
    
    public function insertShopInfo($name, $value){
        $insert = array(
            'SettingName' => $name,
            'Value' => $value
        );
                
        return $this->getTable('settings')->insert($insert);
    }
    
    public function deleteShopInfo($name) {
        return $this->getTable('settings')->where('SettingName', $name)->delete();
    }

    public function loadStaticText($id){
        if($id==''){
            return $this->getTable('statictext')->order('StaticTextName')->fetchPairs('StaticTextID');
        }
        else{
            return $this->getTable('statictext')->where('StaticTextID',$id)->fetch();
        }
    }
    
    public function loadActiveStaticText($id){
        $activeID = $this->getTable('status')->where('StatusName','Active')->fetch();     
        if($id==''){
            return $this->getTable('statictext')->where('StatusID',$activeID['StatusID'])->order('StaticTextName')->fetchPairs('StaticTextID');
        }
        else{
            return $this->getTable('statictext')->where('StaticTextID',$id);
        }
    }
    
    public function loadPhotoAlbumStatic($postid){
        
        $album = $this->getTable('photoalbum')->where('StaticTextID', $postid)->fetch();
        
        if ($album->PhotoAlbumID == NULL){
            $album->PhotoAlbumID = 1;
        }
        return $album->PhotoAlbumID;
    }
    
    public function insertStaticText($title, $content, $status){
        $insert = array(
            'StaticTextName' => $title,
            'StaticTextContent' => $content,
            'StatusID' => $status
        );
        
        $row = $this->getTable('statictext')->insert($insert);
        return $row->StaticTextID;
    }
    
    public function updateStaticText($id, $type, $content){
        $update = array(
            $type => $content
        );
        
        return $this->getTable('statictext')->where('StaticTextID', $id)->update($update);
    }
    
    public function deleteStaticText($id){
        return $this->getTable('statictext')->where('StaticTextID', $id)->delete();
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
    public function loadModule($id, $type=NULL){
        if($type=NULL){
            if($id==''){
                return $this->getTable('module')->fetchPairs('ModuleID');
            }
            else{
                return $this->getTable('module')->where('ModuleID',$id)->fetchPairs('ModuleID');
            }
        }
        else{
            return $this->getTable('module')->where('ModuleType',$type)->fetchPairs('ModuleID');
        }
    }
    
    public function loadModules($type) {
        return $this->getTable('module')->where('ModuleType', $type)->fetchPairs('ModuleID');
    }


    public function loadModuleByName($name){
		return $this->getTable('module')->where('CompModuleName',$name)->fetch();
	}
    
    public function insertModule($name, $compname, $description=NULL, $type='Default', $status='2'){
        $insert = array(
            'ModuleName' => $name,
            'CompModuleName' => $compname,
            'ModuleDescription' => $description,
            'ModuleType' => $type,
            'StatusID' => $status
        );
        
        return $this->getTable('module')->insert($insert);
    }
    
    public function updateModule($compnameold, $name, $compname, $description, $type, $status){
        $update = array(
            'ModuleName' => $name,
            'CompModuleName' => $compname,
            'ModuleDescription' => $description,
            'ModuleType' => $type,
            'StatusID' => $status
        );
        
        return $this->getTable('module')->where('CompModuleName', $compnameold)->update($update);
    }
    
    public function updateModuleStatus($compname,$status){
        $update = array(
            'StatusID' => $status
        );
                
        return $this->getTable('module')->where('CompModuleName',$compname)->update($update);
    }
    
        public function isModuleActive($compname){
        $query = $this->getTable('module')
                ->select('module.*,status.*')
                ->where('module.CompModuleName', $compname)
                ->where('status.StatusName', 'active');
        
        if($query == ''){
            return 'FALSE';
        }
        else{
            return 'TRUE';
        }           
    }
}