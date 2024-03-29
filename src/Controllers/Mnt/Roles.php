<?php
namespace Controllers\Mnt;

use Controllers\PrivateController;
use Views\Renderer;

class Roles extends PrivateController {
    public function run() :void
    {
        $viewData = array(
            "edit_enabled"=>true,
            "delete_enabled"=>true,
            "new_enabled"=>true
        );
        $viewData["roles"] = \Dao\Mnt\Roles::findAll();
        Renderer::render('mnt/roles', $viewData);
    }
}
?>