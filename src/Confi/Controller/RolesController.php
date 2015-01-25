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


    public function listnAction()
    {
        
        $form = new Formulario("form");
        $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
        $d=new AlbumTable($this->dbAdapter);
        $valores=array
        (
            "titulo"    =>  $this->tlis,
            "daPer"     =>  $d->getPermisos($this->lin), // Permisos de esta opcion
            "datArb"    =>  $d->getGeneral("select a.id , a.nombre , b.id as idM1, b.nombre as nomMod1  
                                     ,c.id as idM2, c.nombre as nomMod2  
                                         from c_mu a 
                                         inner join c_mu1 b on b.idM = a.id
                                         inner join c_mu2 c on c.idM1 = b.id order by a.id, b.id, c.id"), 
            "ttablas"   =>  $this->ttab,
            "form"      => $form,
            "lin"       =>  $this->lin,
            'url'       => $this->getRequest()->getBaseUrl(),
            "flashMessages" => $this->flashMessenger()->getMessages(), // Mensaje de guardado
        );                       

        return new ViewModel($valores);
        
    } // Fin listar registros 
   
   // Opciones ver modulos
   public function listoAction()
   {

      $form = new Formulario("form");
      $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
      $d = New AlbumTable($this->dbAdapter);                    
      $id = (int) $this->params()->fromRoute('id', 0);
      
      if($this->getRequest()->isPost()) // Actulizar datos
      {
        $request = $this->getRequest();
        if ($request->isPost()) {       
           $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
           
           $u = New AlbumTable($this->dbAdapter);              
           $data = $this->request->getPost();

           $u->modGeneral("delete from c_roles_o where idRol=".$data->id );           
           $datos =  $d->getGeneral("select a.id, a.nombre, case when b.idM3 is null then 0 else b.id end as idM3,
                                         case when b.nuevo is null then 0 else b.nuevo end as nuevo,
                                         case when b.modificar is null then 0 else b.modificar end as modificar,
                                         case when b.eliminar is null then 0 else b.eliminar end as eliminar, 0 as aprobar  
                                         from c_mu3 a
                                         left join c_roles_o b on b.idM3 = a.id 
                                         where a.idM2 = ".$data->id);// Listado de formularios                       
           foreach ($datos as $dato)
           {
               $idLc = $dato['id'];
               $n = '$data->n_'.$idLc; // Nuevo
               eval("\$n = $n;");    

               $m = '$data->m_'.$idLc; // Modificar
               eval("\$m = $m;");             

               $e = '$data->e_'.$idLc; // Eliminar
               eval("\$e = $e;");             

               $a = '$data->a_'.$idLc; // Aprobar
               eval("\$a = $a;");                            

               if ( ( $n > 0 ) or ( $m > 0 ) or ( $e > 0 ) or ( $e > 0 ) )   
                  $u->modGeneral("insert into c_roles_o (idRol, idM3, nuevo, modificar, eliminar, aprobar)
                      values(".$data->id.",".$idLc.",".$n.",".$m.",".$e.",".$a.")" );           
           }
           $id = $data->id;
           // Verificar si existe opcion ya guardada           
        }
      }else{
          $lon = strlen($id); 
          $id = substr( $id, 0, $lon-1 ); // Para quitar el ultimo digito , por problemas con el 0
          $form->get("id")->setAttribute("value",$id); 

      }
      $datos =  $d->getGeneral("select a.id, a.nombre, case when b.idM3 is null then 0 else b.id end as idM3,
                                         case when b.nuevo is null then 0 else b.nuevo end as nuevo,
                                         case when b.modificar is null then 0 else b.modificar end as modificar,
                                         case when b.eliminar is null then 0 else b.eliminar end as eliminar, 0 as aprobar  
                                         from c_mu3 a
                                         left join c_roles_o b on b.idM3 = a.id 
                                         where a.idM2 = ".$id);// Listado de formularios            
      $valores=array
      (
         "titulo"    =>  'Opciones del rol',
         "datos"     =>  $datos,// Listado de formularios            
         "ttablas"   =>  'Opcion, Nuevo, Modificar, Eliminar, Aprobar',
         'url'       =>  $this->getRequest()->getBaseUrl(),
         "form"      =>  $form,
         "id"        =>  $id,
         "lin"       =>  $this->lin
      );             
      $view = new ViewModel($valores);        
      $this->layout('layout/blancoI'); // Layout del login
      return $view;              
   
   } // Fin listar registros items


}
