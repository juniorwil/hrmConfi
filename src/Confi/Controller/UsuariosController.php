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
use Confi\Model\Entity\Usuarios; // (C)


class UsuariosController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    private $lin  = "/confi/usuarios/list"; // Variable lin de acceso  0 (C)
    private $tlis = "Usuarios del programa"; // Titulo listado
    private $tfor = "Actualización usuario"; // Titulo formulario
    private $ttab = "Id usuario, Usuario, Rol,  Modificar ,Quitar"; // Titulo de las columnas de la tabla
    
    // Listado de registros ********************************************************************************************
    public function listAction()
    {
        
        $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
        $u=new AlbumTable($this->dbAdapter);
        $valores=array
        (
            "titulo"    =>  $this->tlis,
            "datos"     =>  $u->getGeneral("select a.id,a.usr_name,a.usuario,b.nombre 
                                            from users a inner join c_roles b on a.idRol=b.id order by b.nombre,a.usuario"),            
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
      $datos = $d->getGeneral('select * from c_roles order by nombre'); 
      foreach ($datos as $dat){
         $idc=$dat['id'];$nom=$dat['nombre'];
         $arreglo[$idc]= $nom;
      }              
      $form->get("idRol")->setValueOptions($arreglo);                                                 
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
            $form->setValidationGroup('nombre','cedula','clave1','clave2'); // ------------------------------------- 2 CAMPOS A VALDIAR DEL FORMULARIO  (C)            
            // Fin validacion de formulario ---------------------------
            if ($form->isValid()) {
                $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
                $u    = new Usuarios($this->dbAdapter);// ------------------------------------------------- 3 FUNCION DENTRO DEL MODELO (C)  
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
            $u=new Usuarios($this->dbAdapter); // ---------------------------------------------------------- 4 FUNCION DENTRO DEL MODELO (C)          
            $datos = $u->getRegistroId($id);
            // Valores guardados
            $form->get("cedula")->setAttribute("value",$datos['usr_name']); 
            $form->get("nombre")->setAttribute("value",$datos['usuario']); 
            $form->get("idRol")->setAttribute("value",$datos['idRol']); 
            $form->get("clave1")->setAttribute("value",$datos['usr_password']); 
            $form->get("clave1")->setAttribute("value",$datos['usr_password']); 
            $form->get("clave2")->setAttribute("value",$datos['usr_password']); 
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
            $u=new Usuarios($this->dbAdapter);  // ---------------------------------------------------------- 5 FUNCION DENTRO DEL MODELO (C)         
            $u->delRegistro($id);
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin);
          }
          
   }   
   
}
