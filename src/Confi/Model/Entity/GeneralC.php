<?php
/*
 * STANDAR DE NISSI MODELO A LA BD MAESTROS
 * 
 */
namespace Confi\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

class GeneralC extends TableGateway
{
    private $id;
    private $fecha;

        
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('c_general_dnh', $adapter, $databaseSchema,$selectResultPrototype);
    }

    private function cargaAtributos($datos=array())
    {
        $this->id      = $datos["id"];    
        $this->fecha  = $datos["fecDoc"];   
    }
    
    public function getRegistro()
    {
       $datos = $this->select();
       return $datos->toArray();
    }
    
    public function actRegistro($data=array())
    {
       self::cargaAtributos($data);
       self::delRegistro($this->fecha);
       $datos=array
       (
           'fecha'     => $this->fecha,

        );       
        $this->insert($datos);
    }
     public function delRegistro($id)
     {
       $this->delete(array('id' => $id));               
     }    

}
?>
