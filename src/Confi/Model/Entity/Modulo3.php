<?php
/*
 * STANDAR DE NISSI MODELO A LA BD MAESTROS
 * 
 */
namespace Confi\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

class Modulo3 extends TableGateway
{
    private $id;
    private $nombre;
    private $modulo;
    private $controlador;
    private $vista;
    private $tipo;
        
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('c_mu3', $adapter, $databaseSchema,$selectResultPrototype);
    }

    private function cargaAtributos($datos=array())
    {
        $this->id     = $datos["id"];    
        $this->nombre = $datos["nombre"];  
        $this->modulo  = $datos["nombre1"];  
        $this->controlador  = $datos["nombre2"];  
        $this->vista  = $datos["apellido1"];  
        $this->tipo  = $datos["tipo"];    
    }
    
    public function getRegistro()
    {
       $datos = $this->select();
       return $datos->toArray();
    }
    
    public function actRegistro($data=array())
    {
       self::cargaAtributos($data);
       $datos=array
       (
           'nombre'  => $this->nombre,
           'idM2'    => $this->id,
           'modelo'  => $this->modulo,
           'controlador'  => $this->controlador,
           'vista'  => $this->vista,    
           'idOpM'  => $this->tipo,  
           'repor'  => $data['check1'],   
        );
        $this->insert($datos);
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
