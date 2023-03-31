<?php
namespace Controllers\Mnt;

use Controllers\PublicController;
use Exception;
use Views\Renderer;

class Roles_Usuario extends PublicController{
    private $redirectTo = "index.php?page=Mnt-Roles";
    private $viewData = array(
        "usercod" => "",
        "rolescod" => "",
        "roleuserest" =>"",
        "roleuserfch" => "",
        "roleuserexp" => "",        
        
    );
    private $modes = array(
        "DSP" => "Detalle de %s (%s)",
        "INS" => "Nuevo Rol",
        "UPD" => "Editar %s (%s)",
        "DEL" => "Borrar %s (%s)"
    );
    public function run() :void
    {
        try {
            $this->page_loaded();
            if($this->isPostBack()){
                $this->validatePostData();
                if(!$this->viewData["has_errors"]){
                    $this->executeAction();
                }
            }
            $this->render();
        } catch (Exception $error) {
            unset($_SESSION["xssToken_Mnt_Rol"]);
            error_log(sprintf("Controller/Mnt/Rol ERROR: %s", $error->getMessage()));
            \Utilities\Site::redirectToWithMsg(
                $this->redirectTo,
                "Algo Inesperado Sucedió. Intente de Nuevo."
            );
        }
       

    }
    private function page_loaded()
    {
        if(isset($_GET['mode'])){
            if(isset($this->modes[$_GET['mode']])){
                $this->viewData["mode"] = $_GET['mode'];
            } else {
                throw new Exception("Mode Not available");
            }
        } else {
            throw new Exception("Mode not defined on Query Params");
        }
        if($this->viewData["mode"] !== "INS") {
            if(isset($_GET['rolescod'])){
                $this->viewData["rolescod"] = $_GET["rolescod"];
            } else {
                throw new Exception("Id not found on Query Params");
            }
        }
    }
    private function validatePostData(){
        if(isset($_POST["xssToken"])){
            if(isset($_SESSION["xssToken_Mnt_Rol"])){
                if($_POST["xssToken"] !== $_SESSION["xssToken_Mnt_Rol"]){
                    throw new Exception("Invalid Xss Token no match");
                }
            } else {
                throw new Exception("Invalid xss Token on session");
            }
        } else {
            throw new Exception("Invalid xss Token");
        }
        if(isset($_POST["rolesdsc"])){
            if(\Utilities\Validators::IsEmpty($_POST["rolesdsc"])){
                $this->viewData["has_errors"] = true;
                $this->viewData["general_errors"][] = "La descripción no puede ir vacía!";
            }
        } else {
            throw new Exception("rolescod not present in form");
        }

        if(isset($_POST["oleuserest"])){
            if (!in_array( $_POST["roleuserest"], array("ACT","INA"))){
                throw new Exception("roleuserest incorrect value");
            }
        }else {
            if($this->viewData["mode"]!=="DEL") {
                throw new Exception("roleuserest not present in form");
            }
        }
        
        if(isset($_POST["mode"])){
            if(!key_exists($_POST["mode"], $this->modes)){
                throw new Exception("mode has a bad value");
            }
            if($this->viewData["mode"]!== $_POST["mode"]){
                throw new Exception("mode value is different from query");
            }
        }else {
            throw new Exception("mode not present in form");
        }
        if(isset($_POST["rolescod"])){            
            if($this->viewData["rolescod"]!== $_POST["rolescod"] && $this->viewData["mode"] !== "INS"){
                throw new Exception("rolescod value is different from query");
            }
        }else {
            throw new Exception("rolescod not present in form");
        }
        if($this->viewData["mode"] === "INS"){
            $this->viewData["rolescod"] = $_POST["rolescoddummy"];       
            
        }        
         
        $this->viewData["roleuserexp"] = $_POST["roleuserexp"];        
        if($this->viewData["mode"]!=="DEL"){
            $this->viewData["roleuserest"] = $_POST["roleuserest"];
        }
    }
    private function executeAction(){
        switch($this->viewData["mode"]){
            case "INS":
                $inserted = \Dao\Mnt\Roles_Usuarios::insert(
                    $this->viewData["rolescod"],
                    $this->viewData["roleuserexp"],
                    $this->viewData["roleuserest"]                    
                );
                if($inserted > 0){
                    \Utilities\Site::redirectToWithMsg(
                        $this->redirectTo,
                        "Rol Creado Exitosamente"
                    );
                }
                break;
            case "UPD":
                $updated = \Dao\Mnt\Roles_Usuarios::update(
                    $this->viewData["rolescod"],
                    $this->viewData["roleuserexp"],
                    $this->viewData["roleuserest"] 
                );
                if($updated > 0){
                    \Utilities\Site::redirectToWithMsg(
                        $this->redirectTo,
                        "Rol Actualizado Exitosamente"
                    );
                }
                break;
            case "DEL":
                $deleted = \Dao\Mnt\Roles_Usuarios::delete(
                    $this->viewData["rolescod"]
                );
                if($deleted > 0){
                    \Utilities\Site::redirectToWithMsg(
                        $this->redirectTo,
                        "Rol Eliminado Exitosamente"
                    );
                }
                break;
        }
    }
    private function render(){
        $xssToken = md5("ROL" . rand(0,4000) * rand(5000,9999));
        $this-> viewData["xssToken"] = $xssToken;
        $_SESSION["xssToken_Mnt_Rol"] = $xssToken;

        if($this->viewData["mode"] === "INS") {
            $this->viewData["modedsc"] = $this->modes["INS"];
        } else {
            $tmpRoles = \Dao\Mnt\Roles_Usuarios::findById($this->viewData["rolescod"]);
            if(!$tmpRoles){
                throw new Exception("Rol no existe en DB");
            }
            \Utilities\ArrUtils::mergeFullArrayTo($tmpRoles, $this->viewData);
            $this->viewData["rolesest_ACT"] = $this->viewData["rolesest"] === "ACT" ? "selected": "";
            $this->viewData["rolesest_INA"] = $this->viewData["rolesest"] === "INA" ? "selected": "";
            $this->viewData["modedsc"] = sprintf(
                $this->modes[$this->viewData["mode"]],
                $this->viewData["rolesdsc"],
                $this->viewData["rolescod"]
            );
            if(in_array($this->viewData["mode"], array("DSP","DEL"))){
                $this->viewData["readonly"] = "readonly";
            }
            if($this->viewData["mode"] === "DSP") {
                $this->viewData["show_action"] = false;
            }
            if($this->viewData["mode"] === "UPD"){
                $this->viewData["readonly_edit"] = "readonly";
            }
        }
        Renderer::render("mnt/rol", $this->viewData);
    }
}

?>