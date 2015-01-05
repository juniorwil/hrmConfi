<?php
/*
 * STANDAR DE NISSI MODELO A LA BD MAESTROS
 * 
 */
namespace Confi\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

class Calculosm extends TableGateway
{
    private $id;
    private $idGrup;
    private $idLin;
        
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('c_grupos_lm', $adapter, $databaseSchema,$selectResultPrototype);
    }
   
    public function getRegistro()
    {
       $datos = $this->select();
       return $datos->toArray();
    }
    
    public function actRegistro($idLin, $idGrup)
    {
       $datos=array
       (
           'idGrup'   => $idGrup,
           'idLin'    => $idLin,  
        );
        $this->insert($datos);
    }
    
    public function getRegistroId($id)
    {
       $id  = (int) $id;
       $rowset = $this->select(array('idGrup' => $id));
       //$row = $rowset->current();
      $row=$rowset->toArray();
       return $row;
     }        
     public function delRegistro($id)
     {
       $this->delete(array('id' => $id));               
     }
}
?>
