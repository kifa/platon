<?php

class CustomerPresenter extends BasePresenter{
    
    protected $translator;
    private $blog;
    private $productModel;
    private $categoryModel;
    private $shopModel;
    private $orderModel;
    private $userModel;
    
    public function startup() {
        parent::startup();
    }
    
    public function injectTranslator(\Kdyby\Translation\Translator $translator) {
        $this->translator = $translator;
    }

    public function injectProductModel(\ProductModel $productModel) {
        parent::injectProductModel($productModel);
        $this->productModel = $productModel;
    }

    public function injectCategoryModel(\CategoryModel $categoryModel) {
        parent::injectCategoryModel($categoryModel);
        $this->categoryModel = $categoryModel;
    }

    public function injectBlogModel(\BlogModel $blogModel) {
        parent::injectBlogModel($blogModel);
        $this->blog = $blogModel;
    }

    public function injectShopModel(\ShopModel $shopModel) {
        parent::injectShopModel($shopModel);
        $this->shopModel = $shopModel;
    }
    
    public function injectOrderModel(\OrderModel $orderModel) {
        parent::injectOrderModel($orderModel);
        $this->orderModel = $orderModel;
    }
    
    public function injectUserModel(\UserModel $userModel) {
        parent::injectUserModel($userModel);
        $this->userModel = $userModel;
    }
    
    public function renderDefault() {
        
    }

}
