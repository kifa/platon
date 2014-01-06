<?php


class NewsControl extends BaseControl {

    protected $translator;
    private $orderModel;
    private $productModel;
    private $usertracking;


    public function __construct(\OrderModel $orderModel, \ProductModel $productModel, $usertracking, \Kdyby\Translation\Translator $translator) {
        $this->orderModel = $orderModel;
        $this->productModel = $productModel;
        $this->translator = $translator;
        $this->usertracking = $usertracking;
    }
    
    public function render() {
        $this->template->setFile(__DIR__ .'/templates/news.latte');
        $orders = $this->orderModel->loadUnreadOrdersCount($this->usertracking->date);
        $comments = $this->productModel->loadUnreadCommentsCount($this->usertracking->date);
        $news = $orders + $comments;
        $this->template->news = $news;
        $this->template->orders = $orders;
        $this->template->comments = $comments;
        $this->template->render();
    }
}
