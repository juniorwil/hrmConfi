<?php
/** STANDAR MAESTROS NISSI  */
// (C): Cambiar en el controlador 
namespace Confi\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Form\Annotation\AnnotationBuilder;

use Principal\Form\Formulario;         // Componentes generales de todos los formularios
use Principal\Model\ValFormulario;     // Validaciones de entradas de datos
use Principal\Model\AlbumTable;        // Libreria de datos
use Confi\Model\Entity\Modulo1; // (C)
use Confi\Model\Entity\Modulo2; // (C)
use Confi\Model\Entity\Modulo3; // (C)

class Modulo2Controller extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    private $lin  = "/confi/modulo2/list"; // Variable lin de acceso  0 (C)
    private $tlis = "Modulos 2 del programa"; // Titulo listado
    private $tfor = "Actualización modulo 2"; // Titulo formulario
    private $ttab = "Modulo padre, Modulo 1, Modulo 2, Opciones , Modificar,Quitar"; // Titulo de las columnas de la tabla
//    private $mod  = "Nivel de aspecto ,A,E"; // Funcion del modelo
    
    // Listado de registros ********************************************************************************************
    public function listAction()
    {
        
        $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
        $u=new AlbumTable($this->dbAdapter);
        $valores=array
        (
            "titulo"    =>  $this->tlis,
            "datos"     =>  $u->getGeneral("Select a.nombre as nomMod, b.nombre, c.id, c.nombre as nomMod2   
                                            from c_mu a 
                                            inner join c_mu1 b on a.id=b.idM
                                            inner join c_mu2 c on c.idM1 = b.id 
                                            order by a.nombre, b.nombre, c.nombre "),            
            "ttablas"   =>  $this->ttab,
            "lin"       =>  $this->lin
        );                
        return new ViewModel($valores);
        
    } // Fin listar registros 
    
 
   // Editar y nuevos datos *********************************************************************************************
   public function listaAction() 
   { 
      $form = new Formulario("form");
      //  valores iniciales formulario   (C)
      $id = (int) $this->params()->fromRoute('id', 0);
      $form->get("id")->setAttribute("value",$id);                       
      
      $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
      $d=new AlbumTable($this->dbAdapter);
      // Modulos asociados
      $arreglo='';
      $datos = $d->getGeneral('select * from c_mu order by nombre'); 
      foreach ($datos as $dat){
         $idc=$dat['id'];$nom=$dat['nombre'];
         $arreglo[$idc]= $nom;
      }              
      $form->get("tipo")->setValueOptions($arreglo);                                                 
      //
      $datos=0;
      $valores=array
      (
           "titulo"  => $this->tfor,
           "form"    => $form,
           'url'     => $this->getRequest()->getBaseUrl(),
           'id'      => $id,
           'datos'   => $datos,  
           "lin"     => $this->lin
      );       
      // ------------------------ Fin valores del formulario 
      
      if($this->getRequest()->isPost()) // Actulizar datos
      {
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Zona de validacion del fomrulario  --------------------
            $album = new ValFormulario();
            $form->setInputFilter($album->getInputFilter());            
            $form->setData($request->getPost());           
            $form->setValidationGroup('nombre'); // ------------------------------------- 2 CAMPOS A VALDIAR DEL FORMULARIO  (C)            
            // Fin validacion de formulario ---------------------------
            if ($form->isValid()) {
                $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
                $u    = new Modulo1($this->dbAdapter);// ------------------------------------------------- 3 FUNCION DENTRO DEL MODELO (C)  
                $data = $this->request->getPost();
                $u->actRegistro($data);
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin);
            }
        }
        return new ViewModel($valores);
        
    }else{              
      if ($id > 0) // Cuando ya hay un registro asociado
         {
            $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
            $u=new Modulo3($this->dbAdapter); // ---------------------------------------------------------- 4 FUNCION DENTRO DEL MODELO (C)          
            $datos = $u->getRegistroId($id);
            $n = $datos['nombre'];
            // Valores guardados
            $form->get("nombre")->setAttribute("value","$n"); 
            $form->get("tipo")->setAttribute("value",$datos['idM']); 
         }            
         return new ViewModel($valores);
      }
   } // Fin actualizar datos 
   
   // Eliminar dato ********************************************************************************************
   public function listdAction() 
   {
      $id = (int) $this->params()->fromRoute('id', 0);
      if ($id > 0)
         {
            $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
            $u=new Modulo3($this->dbAdapter);  // ---------------------------------------------------------- 5 FUNCION DENTRO DEL MODELO (C)         
            $u->delRegistro($id);
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin);
          }
          
   }
   //----------------------------------------------------------------------------------------------------------
   //----------------------------------------------------------------------------------------------------------
   // FUNCIONES ADICIONALES GUARDADO DE ITEMS   
     
   // Listado de items de la etapa **************************************************************************************
   public function listiAction()
   {
      $form = new Formulario("form");
      $id = (int) $this->params()->fromRoute('id', 0);
      $form->get("id")->setAttribute("value",$id);
      $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
      $d = New AlbumTable($this->dbAdapter);      
      // Modulos asociados
      $arreglo='';
      $datos = $d->getGeneral('select * from c_opciones_m order by nombre'); 
      foreach ($datos as $dat){
         $idc=$dat['id'];$nom=$dat['nombre'];
         $arreglo[$idc]= $nom;
      }              
      $form->get("tipo")->setValueOptions($arreglo);                                                 
      
      
      if($this->getRequest()->isPost()) 
      {
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Zona de validacion del fomrulario  --------------------
            $album = new ValFormulario();
            $form->setInputFilter($album->getInputFilter());            
            $form->setData($request->getPost());           
            $form->setValidationGroup('nombre'); // ------------------------------------- 2 CAMPOS A VALDIAR DEL FORMULARIO  (C)            
            // Fin validacion de formulario ---------------------------
            if ($form->isValid()) {
                $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
                $u    = new Modulo3($this->dbAdapter);// ------------------------------------------------- 3 FUNCION DENTRO DEL MODELO (C)  
                $data = $this->request->getPost();                
               // print_r($data);
                $u->actRegistro($data);
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin.'i/'.$data->id);
            }
        }
      }      

      $valores=array
      (
           "titulo"    =>  'Opciones modulo',
           "datos"     =>  $d->getGeneral("Select a.id, a.nombre , CONCAT( a.modelo ,'/', a.controlador ,'/', a.vista ) as link, b.nombre as nomgop 
                                           from c_mu3 a inner join c_opciones_m b on b.id=a.idOpM
                                           where a.idM2=".$id." order by a.nombre"),// Listado de formularios            
           "ttablas"   =>  'Opción, Link , Grupo de opciones , Eliminar',
           'url'       =>  $this->getRequest()->getBaseUrl(),
           "form"      =>  $form,
           "lin"       =>  $this->lin
       );                
       return new ViewModel($valores);        
   } // Fin listar registros items
   // Eliminar dato ********************************************************************************************
   public function listidAction() 
   {
      $id = (int) $this->params()->fromRoute('id', 0);
      if ($id > 0)
         {
            $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
            $u=new Modulo3($this->dbAdapter);  // ---------------------------------------------------------- 5 FUNCION DENTRO DEL MODELO (C)         
            $d = New AlbumTable($this->dbAdapter);  
            // bucar id de parametro
            $datos = $d->getGeneral1("select idM2 from c_mu3 where id=".$id);// Listado de formularios                                
            $u->delRegistro($id);
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin.'i/'.$datos['idM2']);
          }          
   }// Fin eliminar datos
   
   
}
