<?php
namespace Controllers\Mnt;

use Controllers\PublicController;
use Views\Renderer;

class Roles_Usuarios extends PublicController {
    public function run() :void
    {
        $viewData = array(
            "edit_enabled"=>true,
            "delete_enabled"=>true,
            "new_enabled"=>true
        );
        $viewData["roles_usuarios"] = \Dao\Mnt\Roles::findAll();
        Renderer::render('mnt/roles', $viewData);
    }
}