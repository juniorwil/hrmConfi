<?php
/*
 * STANDAR DE NISSI MODELO A LA BD MAESTROS
 * 
 */
namespace Confi\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

class Usuarios extends TableGateway
{
    private $id;
    private $codigo; 
    private $usuario;
    private $idrol;
    private $clave;
        
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('users', $adapter, $databaseSchema,$selectResultPrototype);
    }

    private function cargaAtributos($datos=array())
    {
        $this->id      = $datos["id"];    
        $this->codigo  = $datos["cedula"];    
        $this->usuario = $datos["nombre"];   
        $this->idrol   = $datos["idRol"];   
        $this->clave   = $datos["clave1"];       
    }
    
    public function getRegistro()
    {
       $datos = $this->select();
       return $datos->toArray();
    }
    
    public function actRegistro($data=array())
    {
       self::cargaAtributos($data);
       $id = $this->id;
       $datos=array
       (
           'usr_name'     => $this->codigo,
           'usuario'      => $this->usuario,
           'idRol'        => $this->idrol,   
           'usr_password' => $this->clave,   
           
        );
       if ($id==0) // Nuevo registro
          $this->insert($datos);
       else // Mdificar registro
          $this->update($datos, array('id' => $id));
    }
    
    public function getRegistroId($id)
    {
       $id  = (int) $id;
       $rowset = $this->select(array('id' => $id));
       $row = $rowset->current();
      
       if (!$row) {
          throw new \Exception("No hay registros asociados al valor $id");
       }
       return $row;
     }        
     public function delRegistro($id)
     {
       $this->delete(array('id' => $id));               
     }
}
?>
