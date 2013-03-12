<?php

use Nette\Application\UI;

/*
 * Menu Control component
 */

class MenuControl extends UI\Control {

    public function render() {
        $this->template->setFile(__DIR__ . '/MenuControl.latte');
        $this->template->menuItems = $this->ShopModel->getMenu();
        $this->template->render();
    }

}
