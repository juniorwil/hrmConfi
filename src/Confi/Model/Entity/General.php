<?php
/*
 * STANDAR DE NISSI MODELO A LA BD MAESTROS
 * 
 */
namespace Confi\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

class General extends TableGateway
{
    private $id;
    private $nombre;
    private $nit;
    private $logo;
    private $dia31;
        
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('c_general', $adapter, $databaseSchema,$selectResultPrototype);
    }

    private function cargaAtributos($datos=array())
    {
        $this->id      = $datos["id"];    
        $this->nombre  = $datos["nombre"];   
        $this->nit     = $datos["nit"]; 
        $this->dia31   = $datos["check1"]; 
    }
    
    public function getRegistro()
    {
       $datos = $this->select();
       return $datos->toArray();
    }
    
    public function actRegistro($data=array(),$ruta)
    {
       self::cargaAtributos($data);
       if ($ruta!='')
       {
          $datos=array
          (
             'empresa'     => $this->nombre,
             'nit'         => $this->nit,
             'dia31'       => $this->dia31,
             'ruta'        => $data['dir'],
             'logo'        => $ruta,           
          );
          $this->update($datos, array('id' => $this->id));
       }else{
          $datos=array
          (
             'empresa'     => $this->nombre,
             'nit'         => $this->nit,
             'dia31'       => $this->dia31,
             'escala'      => $data['check2'],             
          );
          $this->update($datos, array('id' => $this->id));          
        }
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
}
?>
