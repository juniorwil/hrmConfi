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
use Confi\Model\Entity\Roles; // (C)
use Confi\Model\Entity\Roles1; // (C)


class RolesController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    private $lin  = "/confi/roles/list"; // Variable lin de acceso  0 (C)
    private $tlis = "Roles del programa"; // Titulo listado
    private $tfor = "Actualización rol"; // Titulo formulario
    private $ttab = "Rol, Opciones , Modificar,Quitar"; // Titulo de las columnas de la tabla
    
    // Listado de registros ********************************************************************************************
    public function listAction()
    {
        
        $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
        $u=new Roles($this->dbAdapter);
        $valores=array
        (
            "titulo"    =>  $this->tlis,
            "datos"     =>  $u->getRegistro(),            
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
                $u    = new Roles($this->dbAdapter);// ------------------------------------------------- 3 FUNCION DENTRO DEL MODELO (C)  
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
            $u=new Roles($this->dbAdapter); // ---------------------------------------------------------- 4 FUNCION DENTRO DEL MODELO (C)          
            $datos = $u->getRegistroId($id);
            $n = $datos['nombre'];
            // Valores guardados
            $form->get("nombre")->setAttribute("value","$n"); 
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
            $u=new Roles($this->dbAdapter);  // ---------------------------------------------------------- 5 FUNCION DENTRO DEL MODELO (C)         
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
      $datos = $d->getGeneral('select * from c_mu order by nombre'); 
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
                $u    = new Modulo2($this->dbAdapter);// ------------------------------------------------- 3 FUNCION DENTRO DEL MODELO (C)  
                $data = $this->request->getPost();                
               // print_r($data);
                $u->actRegistro($data,$id);
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin.'i/'.$data->id);
            }
        }
      }      

      $valores=array
      (
           "titulo"    =>  'Opciones del rol',
           "datos"     =>  $d->getGeneral("Select * , CONCAT( modelo ,'/', controlador ,'/', vista ) as link from c_mu2
                                           where idM1=".$id." order by nombre"),// Listado de formularios            
           "ttablas"   =>  'Opción, Link , Eliminar',
           'url'       =>  $this->getRequest()->getBaseUrl(),
           "form"      =>  $form,
           "lin"       =>  $this->lin
       );                
       return new ViewModel($valores);        
   } // Fin listar registros items 

   // Opciones ver modulos
   public function listpAction()
   {
      if($this->getRequest()->isPost()) 
      {
        $request = $this->getRequest();
        if ($request->isPost()) 
            {   
              $form = new Formulario("form");
              $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
              $d = New AlbumTable($this->dbAdapter);                    
              $data = $this->request->getPost();  
              $id1 = $data->id1;
              $id2 = $data->id2;
              // Modulos asociados
              $arreglo='';
              $datos = $d->getGeneral('select * from c_mu1 where idM='.$id2); 
              foreach ($datos as $dat){
                $idc=$dat['id'];$nom=$dat['nombre'];
                $arreglo[$idc]= $nom;
              }
              if ($arreglo!='')
                 $form->get("idM")->setValueOptions($arreglo);                                                                     
              
              $valores=array
              (
                 'url'       =>  $this->getRequest()->getBaseUrl(),
                 "form"      =>  $form,
                 "lin"       =>  $this->lin
              );             
             $view = new ViewModel($valores);        
             $this->layout('layout/blanco'); // Layout del login
             return $view;              
            }
      }
    
   } // Fin listar registros items   

   // Opciones ver modulos
   public function listoAction()
   {
      if($this->getRequest()->isPost()) 
      {
        $request = $this->getRequest();
        if ($request->isPost()) 
            {   
              $form = new Formulario("form");
              $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
              $d = New AlbumTable($this->dbAdapter);                    
              $data = $this->request->getPost();  
              $id1 = $data->id1;
              $id2 = $data->id2;
              $id3 = $data->id3;
              print_r($data);
              $valores=array
              (
                 "titulo"    =>  'Opciones del rol',
                 "datos"     =>  $d->getGeneral("select a.idM2 , b.nombre as nomM2, a.nuevo, a.modificar, a.eliminar, a.aprobar 
                                      from c_roles_o a 
                                      inner join c_mu2 b on b.id=a.idM3  where a.idRol=".$id1."  and a.idM2= ".$id3."
                                 union all
                                      select a.id as idM2, a.nombre as nomM2,
                                      0 as nuevo,
                                      0 as modificar,
                                      0 as eliminar,
                                      0 as aprobar
                                      from c_mu2 a 
                                      inner join c_mu1 b on a.idM1=b.id
                                      where not exists (select null from c_roles_o 
                                      where idM2=a.idM1 and idM3=a.id and idRol=".$id1." ) and a.idM1=".$id3."  "),// Listado de formularios            
                 "ttablas"   =>  'Opcion, Nuevo, Modificar, Eliminar, Aprobar',
                 'url'       =>  $this->getRequest()->getBaseUrl(),
                 "form"      =>  $form,
                 "lin"       =>  $this->lin
              );             
             $view = new ViewModel($valores);        
             $this->layout('layout/blanco'); // Layout del login
             return $view;              
            }
      }
    
   } // Fin listar registros items      

   // guardar o eliminar opciones  
   public function listogAction() 
   {
      if($this->getRequest()->isPost()) // Actulizar datos
      {
        $request = $this->getRequest();
        if ($request->isPost()) {       
           $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
           $d = New Roles1($this->dbAdapter);      
           $data = $this->request->getPost();
           // Verificar si existe opcion ya guardada
           $u = New AlbumTable($this->dbAdapter);              
           $idm3=substr( $data->idm3 , 2, 5 );
           $dat = $u->getGeneral1("select count(idRol) as registro from c_roles_o a where idRol=".$data->idrol." and idM1=".$data->idm1
                   ." and idM2=".$data->idm2." and idM3=".$idm3);           
           
           $d->actRegistro($data, $dat['registro']);      
        }
      }
   }
   
   // Eliminar dato ********************************************************************************************
   public function listidAction() 
   {
      $id = (int) $this->params()->fromRoute('id', 0);
      if ($id > 0)
         {
            $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
            $u=new Modulo2($this->dbAdapter);  // ---------------------------------------------------------- 5 FUNCION DENTRO DEL MODELO (C)         
            $d = New AlbumTable($this->dbAdapter);  
            // bucar id de parametro
            $datos = $d->getGeneral1("select idM1 from c_mu2 where id=".$id);// Listado de formularios                                
            $u->delRegistro($id);
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin.'i/'.$datos['idM1']);
          }          
   }// Fin eliminar datos
   
   
}
