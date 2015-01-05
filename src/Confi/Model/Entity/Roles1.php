<?php
/*
 * STANDAR DE NISSI MODELO A LA BD MAESTROS
 * 
 */
namespace Confi\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

class Roles1 extends TableGateway
{
    private $idrol;
    private $idm1;
    private $idm2;
    private $idm3;
    private $permiso;
        
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('c_roles_o', $adapter, $databaseSchema,$selectResultPrototype);
    }

    private function cargaAtributos($datos=array())
    {
        $this->idrol   = $datos["idrol"];    
        $this->permiso = $datos["permiso"];   
        $this->idm1    = $datos["idm1"];    
        $this->idm2    = $datos["idm2"];    
        $this->idm3    = $datos["idm3"];    
    }
    
    public function getRegistro()
    {
       $datos = $this->select();
       return $datos->toArray();
    }
    
    public function actRegistro($data=array(), $registro)
    {
       self::cargaAtributos($data);
       // Campo de opcion 
       $val = substr( $this->idm3 , 0, 2 );
       $this->idm3 = substr( $this->idm3, 2, 5 );       
       $cam = '';
       switch ($val) {
           case 'n_':
                $cam = 'nuevo';
                break;
           case 'm_':
                $cam = 'modificar';
                break;
           case 'e_':
                $cam = 'eliminar';
                break;
           case 'a_':
                $cam = 'aprobar';
                break;            
       }
       $datos=array
       (
           'idRol'=> $this->idrol,
           'idM1' => $this->idm1,
           'idM2' => $this->idm2,
           'idM3' => $this->idm3,
           $cam   => $this->permiso,
        );
       if ($registro==0) // Permiso
          $this->insert($datos);
       else // Sin permiso
          $this->update($datos, array('idRol'=>$this->idrol,'idM1' => $this->idm1,'idM2' => $this->idm2,'idM3' => $this->idm3) );
    }    

}
?>
