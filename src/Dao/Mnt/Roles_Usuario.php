<?php
namespace Dao\Mnt;

use Dao\Table;
/*
`usercod` bigint(10) NOT NULL,
  `rolescod` varchar(15) NOT NULL,
  `roleuserest` char(3) DEFAULT NULL, /*estado
  `roleuserfch` datetime DEFAULT NULL,/*fechacreacion
  `roleuserexp` datetime DEFAULT NULL,/*fecha que expira*/
class Roles_Usuario extends Table{
    
    public static function insert(string $rolescod,string $roleuserest,  string $roleuserexp): int
    {
        $date = new \DateTime("now");
        $roleuserfch = $date->format(\DateTimeInterface::W3C);
        $sqlstr = "INSERT INTO roles (rolescod,roleuserest,roleuserfch,roleuserexp) values(:rolescod,:roleuserest,:roleuserfch,:rolesuserexp);";
        $rowsInserted = self::executeNonQuery(
            $sqlstr,
            array("rolescod"=>$rolescod, "rolesuserfch"=>$rolesuserfch, "roleuserexp"=>$roleuserexp)
        );
        return $rowsInserted;
    }
    public static function update(string $rolescod,string $roleuserest,  string $roleuserex){
        $sqlstr = "UPDATE roles_usuario set roleuserexp = :roleuserexp, roleuserest = :roleuserest where rolescod=:rolescod;";
        $rowsUpdated = self::executeNonQuery(
            $sqlstr,
            array(
                "roleuserexp" => $roleuserexp,
                "roleuserest" => $roleuserest,
                "rolescod" => $rolescod
            )
        );
        return $rowsUpdated;
    }
    public static function delete(string $rolescod){
        $sqlstr = "DELETE from roles_usuarios where rolescod=:rolescod;";
        $rowsDeleted = self::executeNonQuery(
            $sqlstr,
            array(
                "rolescod" => $rolescod
            )
        );
        return $rowsDeleted;
    }
    public static function findAll(){
        $sqlstr = "SELECT * from roles_usuarios;";
        return self::obtenerRegistros($sqlstr, array());
    }
    public static function findByFilter(){

    }
    public static function findById(string $rolescod){
        $sqlstr = "SELECT * from roles_usuarios where rolescod = :rolescod;";
        $row = self::obtenerUnRegistro(
            $sqlstr,
            array(
                "rolescod"=> $rolescod
            )
        );
        return $row;
    }
}