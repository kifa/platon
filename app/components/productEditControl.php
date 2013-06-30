<?php


/**
 * Description of productEditControl
 *
 * @author Lukas
 */
class productEditControl extends BaseControl{
    
     /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $categoryModel;
    private $productModel;
    private $blogModel;


    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setCategory($cat) {
        $this->categoryModel = $cat;

    }
    
    public function setBlog($blog) {
        $this->blogModel = $blog;

    }
    
    public function setProduct($pro) {
        $this->productModel = $pro;
    }

    public function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
    }
         
    
    
    public function render($id) {
        $this->template->setFile(__DIR__ . '/productEditControl.latte');    
        $this->template->product = $this->productModel->loadProduct($id);
        $this->template->render();
    }
}
