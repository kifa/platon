<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class BlogPresenter extends BasePresenter {
    
     protected $translator;
     private $blog;


     public function injectTranslator(NetteTranslator\Gettext $translator) {
        $this->translator = $translator;
    }
    
    public function injectBlog(BlogPresenter $blog) {
        $this->blog = $blog;
    }
    
    public function renderCategory() {
        
        $this->template->posts = $this->blog->loadPosts();
        
    }
      
    public function renderPost() {
        $this->template->post = $this->blog->loadPost();
        
    }
    
    
    
}
