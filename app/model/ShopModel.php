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
        //$tax = $this->getTable('settings')
        //        ->where('SettingName',"TAX")
        //        ->fetch();
        
        $tax = $this->db
                ->SELECT('SettingName, Value')
                ->WHERE('SettingName = "TAX"')
                ->FETCH();
        
        return $tax['Value'];
    }
    
    /*public function getShopInfo($name)
    {
        if($name != '') {
            $value = $this->getTable('settings')
                    ->where('SettingName', $name)
                    ->fetch();
            
            return $value['Value'];
        }
        else {
            return $this->getTable('settings')
                    ->fetchPairs('SettingID');
        }
    }*/
    public function getShopInfo($name){
        if($name != '') {
            $value = $this->db
                    ->SELECT('*')
                    ->FROM('settings')
                    ->WHERE("SettingName = %s", $name)
                    ->FETCH();
            //$value = $this->db
            //        ->query("SELECT * FROM settings WHERE SettingName = %s", $name)->fetch();
            
            return $value['Value'];
        }
        else{
            return $this->db
                    ->SELECT('*')
                    ->FROM('settings')
                    ->FETCHASSOC('SettingID');
        }
    }

    public function loadPhotoSize() {
        $param = array('Small', 'Medium', 'Large');
         
        /*return $this->getTable('settings')
                 ->select('SettingName, Value')
                 ->where('SettingName', $param)
                 ->fetchPairs('SettingName');*/
        $row = $this->db
                ->SELECT('SettingName, Value')
                ->WHERE('SettingName = %s', $param)
                ->FETCHASSOC('SettingName');
        
        return $row;
    }


    /*public function getShopSettings()
    {
            return $this->getTable('settings')
                    ->select('SettingName, Value')
                    ->fetchPairs('SettingName');
      
    }*/
    public function getShopSettings(){
        //$row = $this->db
        //        ->select('SettingName, Value')
        //        ->from('settings');
                //->fetchPairs('SettingName', 'Value');   
        //$row =  $this->db
        //        ->query("SELECT SettingName, Value FROM settings")
        //        ->fetchPairs('SettingName', 'Value');        
        $row = $this->db
                ->SELECT('SettingName, Value')
                ->FROM('settings')
                ->FETCHPAIRS('SettingName', 'Value');
        
        return $row;
    }


    public function getShopInfoPublic() {
        $param = array('Name', 'Description', 'CompanyAddress', 'TAX', 'orderByMail', 'ContactMail', 'ContactPhone', 'InvoicePrefix', 'GA');
        
        /*return $this->getTable('settings')
                ->where('SettingName', $param)
                ->fetchPairs('SettingID'); */
        $row = $this->db
                ->SELECT('SettingID, SettingName, Value')
                ->FROM('settings')
                ->WHERE('SettingName = %s', $param)
                ->FETCHASSOC('SettingID');
     }
    
    public function setShopInfo($name, $value)
    {
        $update = array(
              'Value' => $value  
            );
        
        /*return $this->getTable('settings')
                ->where('SettingName', $name)
                ->update($update);*/
        return $this->db
                ->UPDATE('settings', $update)
                ->WHERE('SettingName = %s', $name)
                ->EXECUTE();
    }
    
    public function setShopInfoByID($id, $value){        
            $update = array(
              'Value' => $value  
            );
       
        /*return $this->getTable('settings')
                ->where('SettingID', $id)
                ->update($update);*/
        return $this->db
                ->UPDATE('settings', $update)
                ->WHERE('SettingID = %i', $id)
                ->EXECUTE();                
    }
    
    public function insertShopInfo($name, $value){
        $insert = array(
            'SettingName' => $name,
            'Value' => $value
        );
                
        /*return $this->getTable('settings')
                ->insert($insert);*/
        return $this->db
                ->INSERT('settings', $insert)
                ->EXECUTE();
    }
    
    public function deleteShopInfo($name) {
        /*return $this->getTable('settings')
                ->where('SettingName', $name)
                ->delete();*/
        return $this->db
                ->DELETE('settings')
                ->WHERE('SettingName = %s', $name)
                ->EXECUTE();
    }

    public function loadStaticText($id){
        if($id==''){
            /*return $this->getTable('statictext')
                    ->order('StaticTextName')
                    ->fetchPairs('StaticTextID');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('statictext')
                    ->orderBy('StaticTextName')
                    ->FETCHASSOC('StaticTextID');
        }
        else{
            /*return $this->getTable('statictext')
                    ->where('StaticTextID',$id)
                    ->fetch();*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('statictext')
                    ->WHERE('StaticTextID = %i', $id)
                    ->FETCH();
        }
        return $row;
    }
    
    public function loadActiveStaticText($id){
        /*$activeID = $this->getTable('status')
                ->where('StatusName','Active')
                ->fetch();     */
        $activeID = $this->db
                ->SELECT('*')
                ->FROM('status')
                ->WHERE('StatusName = "active"')
                ->FETCH();
        
        if($id==''){
            /*return $this->getTable('statictext')
                    ->where('StatusID',$activeID['StatusID'])
                    ->order('StaticTextName')
                    ->fetchPairs('StaticTextID');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('statictext')
                    ->WHERE('StatusID = %i', $activeID['StatusID'])
                    ->orderBy('StaticTextName')
                    ->FETCHASSOC('StaticTextID');
                    
        }
        else{
            /*return $this->getTable('statictext')
                    ->where('StaticTextID',$id);*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('statictext')
                    ->WHERE('StaticTextID = %i', $id);
        }
        
        return $row;
    }
    
    public function loadPhotoAlbumStatic($postid){
        
        /*$album = $this->getTable('photoalbum')
                ->where('StaticTextID', $postid)
                ->fetch();*/
        $album = $this->db
                ->SELECT('*')
                ->FROM('photoalbum')
                ->WHERE('StaticTextID = %i', $postid)
                ->FETCH();
        
        if ($album){
            $album = 1;
        }
        
        return $album;
    }
    
    public function insertStaticText($title, $content, $status){
        $insert = array(
            'StaticTextName' => $title,
            'StaticTextContent' => $content,
            'StatusID' => $status
        );
        
        /*$row = $this->getTable('statictext')
                ->insert($insert);*/
        
        $row = $this->db
                ->INSERT('statictext', $insert)
                ->EXECUTE();
        
        return $row->StaticTextID;
    }
    
    public function updateStaticText($id, $type, $content){
        $update = array(
            $type => $content
        );
        
        /*return $this->getTable('statictext')
                ->where('StaticTextID', $id)
                ->update($update);*/
        return $this->db
                ->UPDATE('statictext', $update)
                ->WHERE('StaticTextID = %i', $id)
                ->EXECUTE();
    }
    
    public function deleteStaticText($id){
        /*return $this->getTable('statictext')
                ->where('StaticTextID', $id)
                ->delete();*/
        return $this->db
                ->DELETE('statictext')
                ->WHERE('StaticTextID = %i', $id)
                ->EXECUTE();
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
                //return $this->getTable('module')
                //        ->fetchPairs('ModuleID');
                $row = $this->db
                        ->SELECT('*')
                        ->FROM('module')
                        ->FETCHASSOC('ModuleID');
            }
            else{
                /*return $this->getTable('module')
                        ->where('ModuleID',$id)
                        ->fetchPairs('ModuleID');*/
                $row = $this->db
                        ->SELECT('*')
                        ->FROM('module')
                        ->WHERE('ModuleID = %i', $id)
                        ->FETCHASSOC('ModuleID');
            }
        }
        else{
            /*return $this->getTable('module')
                    ->where('ModuleType',$type)
                    ->fetchPairs('ModuleID');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('module')
                    ->WHERE('ModuleType = %s', $type)
                    ->FETCHASSOC('ModuleID');
        }
        
        return $row;
    }
    
    public function loadModules($type) {
        if ($type == '') {
            /*$return = $this->getTable('module')
                    ->order('ModuleType, StatusID, ModuleName')
                    ->fetchPairs('ModuleID');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('module')
                    ->orderBy('ModuleType, StatusID, ModuleName')
                    ->FETCHASSOC('ModuleID');
        }else {
            /*$return = $this->getTable('module')
                    ->where('ModuleType', $type)
                    ->fetchPairs('ModuleID');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('module')
                    ->WHERE('ModuleType = %s', $type)
                    ->FETCHASSOC('ModuleID');
            }
        
        return $row;
    }

    public function loadModuleByName($name){
        /*return $this->getTable('module')
                ->where('CompModuleName',$name)
                ->fetch();*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('module')
                ->WHERE('CompModuleName = %s', $name)
                ->FETCH();
        
        return $row;
    }
    
    public function insertModule($name, $compname, $description=NULL, $type='Default', $status='2'){
        $insert = array(
            'ModuleName' => $name,
            'CompModuleName' => $compname,
            'ModuleDescription' => $description,
            'ModuleType' => $type,
            'StatusID' => $status
        );
        
        /*return $this->getTable('module')
                ->insert($insert);*/
        
        $row = $this->db
                ->INSERT('module', $insert)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateModule($compnameold, $name, $compname, $description, $type, $status){
        $update = array(
            'ModuleName' => $name,
            'CompModuleName' => $compname,
            'ModuleDescription' => $description,
            'ModuleType' => $type,
            'StatusID' => $status
        );
        
        /*return $this->getTable('module')
                ->where('CompModuleName', $compnameold)
                ->update($update);*/
        
        $row = $this->db
                ->UPDATE('module', $update)
                ->WHERE('CompModuleName = %s', $compnameold)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateModuleStatus($compname,$status){
        $update = array(
            'StatusID' => $status
        );
                
        /*return $this->getTable('module')
                ->where('CompModuleName',$compname)
                ->update($update);*/
        $row = $this->db
                ->UPDATE('module', $update)
                ->WHERE('CompModuleName = %s', $compname)
                ->EXECUTE();
    }
    
    public function isModuleActive($compname){
        /*$query = $this->getTable('module')
                ->select('module.*,status.*')
                ->where('module.CompModuleName', $compname)
                ->where('status.StatusName', 'active')
                ->fetch();
                */
        $query = $this->db
                ->SELECT('module.*, status.*')
                ->FROM('module')
                ->LEFTJOIN('status')->ON('module.StatusID = status.StatusID')
                ->WHERE('module.CompModuleName = %s ' .
                    'AND status.StatusName =  "active"', $compname)
                ->FETCH();
       
        if($query == ''){
            return FALSE;
        }
        else{
            return TRUE;
        }           
    }
  /*  
    public function insertBannerType($code, $name, $description = NULL){
        $insert = array(
            'BannerTypeCode' => $code,
            'BannerTypeName' => $name,
            'BannerTypeDescription' => $description
        );
                      
        return $this->getTable('bannertype')
                ->insert($insert);
    }

    public function loadBannerTypes(){
        return $this->getTable('bannertype')
                ->fetchPairs('BannerTypeID');
    }
    
    public function loadBannerType($id){
        return $this->getTable('bannertype')
                ->where('BannerTypeID',$id)
                ->fetch();
    }
    
    public function updateBannerType($id, $code, $name, $description = NULL){
        $update = array(
            'BannerTypeCode' => $code,
            'BannerTypeName' => $name,
            'BannerTypeDescription' => $description
        );
        
        return $this->getTable('bannertype')
                ->update($update)
                ->where('BannerTypeID', $id);
    }
    */
    public function insertBanner($type, $value, $link = NULL){
        $insert = array(
            'BannerType' => $type,
            'BannerValue' => $value,
            'BannerLink' => $link
        );
        
        /*return $this->getTable('banner')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('banner', $insert)
                ->EXECUTE();
        
        return $row;
    }
    
    public function loadBanners(){
        /*return $this->getTable('banner')
                ->fetchPairs('BannerID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('banner')
                ->FETCHASSOC('BannerID');
        
        return $row;
    }
    
    public function loadBannerByID($id){
        /*return $this->getTable('banner')
                ->where('BannerID', $id)
                ->fetch();*/
        
        $row = $this->db
                ->SELECT('*')
                ->FROM('banner')
                ->WHERE('BannerID = %i', $id)
                ->FETCH();
        
        return $row;
    }
    
    public function loadBannerByType($type){
        /*return $this->getTable('banner')
                ->where('BannerType', $type)
                ->fetch();*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('banner')
                ->WHERE('BannerType = %s', $type)
                ->FETCH();
        
        return $row;
    }

    public function updateBanner($id, $value, $link){
        $update = array(            
            'BannerValue' => $value,
            'BannerLink' => $link
        );
        
        /*return $this->getTable('banner')
                ->update($update)
                ->where('BannerID', $id);*/
        $row = $this->db
                ->UPDATE('banner', $update)
                ->WHERE('BannerID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }

    public function updateBannerByType($type, $value, $link){
        
        if ($value == NULL) {
            $update = array(
                'BannerLink' => $link
             );
       
        } else {
            $update = array(
                'BannerValue' => $value,
                'BannerLink' => $link
             );
        }
        //return $this->getTable('banner')->where('BannerType', $type)->update($update);
        $row = $this->db
                ->UPDATE('banner', $update)
                ->WHERE('BannerType = %s', $type)
                ->EXECUTE();
        
        return $row;
    }       
}