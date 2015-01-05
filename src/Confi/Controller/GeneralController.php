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
use Confi\Model\Entity\General; // (C)
use Confi\Model\Entity\GeneralC; // (C)

class GeneralController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    private $lin  = "/confi/general/list"; // Variable lin de acceso  0 (C)
    private $tlis = "Parametros generales"; // Titulo listado
    private $tfor = "Actualización parametros generales"; // Titulo formulario
    private $ttab = "Tipo de parametros, Modificar"; // Titulo de las columnas de la tabla
//    private $mod  = "Nivel de aspecto ,A,E"; // Funcion del modelo
    
    // Listado de registros ********************************************************************************************
    public function listAction()
    {
        $valores=array
        (
            "titulo"    =>  $this->tlis,
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
      // Niveles de aspectos
      $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
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
                $u    = new General($this->dbAdapter);// ------------------------------------------------- 3 FUNCION DENTRO DEL MODELO (C)  
                $data = $this->request->getPost();
                
                $f    = new AlbumTable($this->dbAdapter);// ------------------------------------------------- 3 FUNCION DENTRO DEL MODELO (C)                  
                $datGen = $f->getConfiguraG(" where id=1"); // Obtener datos de configuracion general        
                $rutaP = $datGen['ruta']; // Ruta padre                
                //
                //print_r($data);
                $File    = $this->params()->fromFiles('image-file');
                if ($File['name']!='')
                {
                    $adapter = new \Zend\File\Transfer\Adapter\Http();
                    $adapter->setValidators(array(new \Zend\Validator\File\Extension
                                        (array('extension'=>array('jpg','jpeg','png')))), $File['name']);  
                    if (!$adapter->isValid()){
                        echo 'Archivo no valido';
                    }else{
                        if ($rutaP=='')
                            $rutaP = './public';// Predeterminada
                                
                        $ruta = $rutaP.'/Datos/General';
                        $adapter->setDestination($ruta);
                        if ($adapter->receive($File['name'])){                        
                            echo 'Realizado';
                        }                    
                    }
                }
                $u->actRegistro($data, $File['name']);
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin.'a/'.$data->id);
            }
        }
        
    }else{              
      if ($id > 0) // Cuando ya hay un registro asociado
         {
            $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
            $u=new General($this->dbAdapter); // ---------------------------------------------------------- 4 FUNCION DENTRO DEL MODELO (C)          
            $datos = $u->getRegistroId($id);
            // Valores guardados
            $form->get("nombre")->setAttribute("value",$datos['empresa']); 
            $form->get("nit")->setAttribute("value",$datos['nit']);       
            $form->get("check1")->setAttribute("value",$datos['dia31']); 
            $form->get("check2")->setAttribute("value",$datos['escala']); 
            $form->get("dir")->setAttribute("value",$datos['ruta']); 
         }            
         $valores=array
         (
            "titulo"  => $this->tfor,
            "form"    => $form,
            'url'     => $this->getRequest()->getBaseUrl(),
            'id'      => $id,
            'ruta'    => $datos['ruta'],
            'logo'    => $datos['logo'],  
            "lin"     => $this->lin
         );                
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
            $u=new General($this->dbAdapter);  // ---------------------------------------------------------- 5 FUNCION DENTRO DEL MODELO (C)         
            $u->delRegistro($id);
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin);
          }
          
   }
   //----------------------------------------------------------------------------------------------------------

   // DIAS NO HABILES CALENDARIO **************************************************************************************
   public function listcAction()
   {
      $form = new Formulario("form");
      $id = (int) $this->params()->fromRoute('id', 0);
      $form->get("id")->setAttribute("value",$id);
      $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
      
      if($this->getRequest()->isPost()) 
      {
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Zona de validacion del fomrulario  --------------------
            $album = new ValFormulario();
            $form->setInputFilter($album->getInputFilter());            
            $form->setData($request->getPost());           
            $form->setValidationGroup('id'); // ------------------------------------- 2 CAMPOS A VALDIAR DEL FORMULARIO  (C)            
            // Fin validacion de formulario ---------------------------
            if ($form->isValid()) {                
                $u    = new GeneralC($this->dbAdapter);// ------------------------------------------------- 3 FUNCION DENTRO DEL MODELO (C)  
                $data = $this->request->getPost();                
                $u->actRegistro($data);
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin.'c');
            }
        }
      }       
      $d = new AlbumTable($this->dbAdapter); // ---------------------------------------------------------- 1 FUNCION DENTRO DEL MODELO (C)
      $valores=array
      (
           "titulo"    =>  'Días festivos en año',
           "datos"     =>  $d->getGeneral("select * from c_general_dnh "),// Listado de formularios            
           "ttablas"   =>  'Fecha , Eliminar',
           'url'       =>  $this->getRequest()->getBaseUrl(),
           "form"      =>  $form,
           "id"        =>  $id,
           "lin"       =>  $this->lin
       );                
       return new ViewModel($valores);        
   } // Fin dias calendario
   // Borrar fecha 
   public function listcdAction() 
   {
      $id = (int) $this->params()->fromRoute('id', 0);
      if ($id > 0)
         {
            $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
            $u=new GeneralC($this->dbAdapter);  // ---------------------------------------------------------- 5 FUNCION DENTRO DEL MODELO (C)         
            $u->delRegistro($id);
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin.'c');
          }
          
   }
   // --
   // DIAS HABILES CALENDARIO **************************************************************************************
   public function listhAction()
   {
      $form = new Formulario("form");
      $id = (int) $this->params()->fromRoute('id', 0);
      $form->get("id")->setAttribute("value",$id);
      $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
      $d = new AlbumTable($this->dbAdapter); // ---------------------------------------------------------- 1 FUNCION DENTRO DEL MODELO (C)
	  
      if($this->getRequest()->isPost()) 
      {
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Zona de validacion del fomrulario  --------------------
            $album = new ValFormulario();
            $form->setInputFilter($album->getInputFilter());            
            $form->setData($request->getPost());           
            $form->setValidationGroup('id'); // ------------------------------------- 2 CAMPOS A VALDIAR DEL FORMULARIO  (C)            
            // Fin validacion de formulario ---------------------------
            if ($form->isValid()) {                
                $data = $this->request->getPost();                
                $d->modGeneral("Delete from c_general_dh ");                 
                // Dia sabado
                $i=0;                
                if ($data->idCcosM1!='')
                {                
                   foreach ($data->idCcosM1 as $dato){ 
                      $idTnom = $data->idCcosM1[$i];$i++;           
                      $d->modGeneral("insert into c_general_dh ( dia, idCcos ) values( 1,".$idTnom.")");                 
                   }
                }                                
                // Dia domingo
                $i=0;    
                if ($data->idCcosM2!='')
                {
                   foreach ($data->idCcosM2 as $dato){ 
                      $idTnom = $data->idCcosM2[$i];$i++;           
                      $d->modGeneral("insert into c_general_dh ( dia, idCcos ) values( 2,".$idTnom.")");                 
                   }
                }                                                
                //return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$this->lin.'h');
            }
        }
      }       
      // Centros de costos
      $arreglo[0]='Todos los centros de costos';            
      $datos = $d->getCencos(); 
      foreach ($datos as $dat){
         $idc=$dat['id'];$nom=$dat['nombre'];
         $arreglo[$idc]= $nom;
      }              
      $form->get("idCcosM1")->setValueOptions($arreglo);
	  $form->get("idCcosM2")->setValueOptions($arreglo);

      // Buscar centros de costos con dias habiles dia sabado
      $datos = $d->getGeneral('select * from c_general_dh where dia=1');
      $arreglo='';            
      foreach ($datos as $dat){
         $arreglo[]=$dat['idCcos'];
      }                
      if ( $arreglo!='' )
        $form->get("idCcosM1")->setValue($arreglo);                       
      // Buscar centros de costos con dias habiles dia domingo
      $datos = $d->getGeneral('select * from c_general_dh where dia=2');
      $arreglo='';            
      foreach ($datos as $dat){
         $arreglo[]=$dat['idCcos'];
      }                
      if ( $arreglo!='' )
         $form->get("idCcosM2")->setValue($arreglo);                                 
      
      $d = new AlbumTable($this->dbAdapter); // ---------------------------------------------------------- 1 FUNCION DENTRO DEL MODELO (C)
      $valores=array
      (
           "titulo"    =>  'Días habiles',
           "datos"     =>  $d->getGeneral("select * from c_general_dnh "),// Listado de formularios            
           "ttablas"   =>  'Fecha , Eliminar',
           'url'       =>  $this->getRequest()->getBaseUrl(),
           "form"      =>  $form,
           "id"        =>  $id,
           "lin"       =>  $this->lin
       );                
       return new ViewModel($valores);        
   } // Fin dias habiles   
   
}
