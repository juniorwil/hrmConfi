<?php
/** STANDAR MAESTROS NISSI  */
// (C): Cambiar en el controlador 
namespace Confi\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Form\Annotation\AnnotationBuilder;

use Principal\Model\AlbumTable;        // Libreria de datos

class BorrarController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    private $lin  = "/confi/borrar/list"; // Variable lin de acceso  0 (C)
    private $tlis = "reseteo base de datos "; // Titulo listado
    private $tfor = "Actualización reseteo"; // Titulo formulario
    private $ttab = "Modulo,Modificar,Quitar"; // Titulo de las columnas de la tabla
//    privat  e $mod  = "Nivel de aspecto ,A,E"; // Funcion del modelo
    
    // Listado de registros ********************************************************************************************
    public function listAction()
    {
        
        $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
        $d = new AlbumTable($this->dbAdapter); // ---------------------------------------------------------- 1 FUNCION DENTRO DEL MODELO (C)
        // Borrar todas tablas y reseteo de base de datos
		$tablas = array(
		   "a_0" => "a_capacitaciones_i", // Tabla de capacitaciones  --- TABLAS ADMINISTRATIVAS -------------------------------------
		   "a_1" => "a_capacitaciones",
		   "a_2" => "a_doc_dotaciones", // Dotaciones
		   "a_3" => "a_dotaciones",		   
		   "a_4" => "a_grudotaciones_d", // Grupo de dotaciones 
		   "a_5" => "a_grudotaciones",		   		   
		   "a_6" => "a_lindotaciones", 
		   "a_7" => "a_eventos_i",// Eventos 
		   "a_8" => "a_eventos", 
		   "a_9" => "a_tipo_eventos",
		   "a_10" => "a_empleados_rete_d",// Retefuente	   		   		   
		   "a_11" => "a_empleados_rete",	   		   		  
		   "t_2" => "t_lista_cheq_vc", // Lista de chequeo
		   "t_3" => "t_lista_cheq_hr",
		   "t_4" => "t_lista_cheq_hl",
		   "t_5" => "t_lista_cheq_he",		   
		   "t_6" => "t_lista_cheq_f",
		   "t_7" => "t_lista_cheq_d_a",		   		   
		   "t_8" => "t_lista_cheq_d",		   		   		   
		   "t_9" => "t_lista_cheq",		   		   		   		   
		   "t_13" => "t_nivel_cargo_o",// Etapas de contratacion		   		   		   		   		   
		   "t_10" => "t_etapas_con_i",// Etapas de contratacion
		   "t_11" => "t_etapas_con_r",		   
		   "t_12" => "t_etapas_con",		   		   
		   "t_0" => "t_sol_con", // Solicitud de contratacion 
		   "t_14" => "t_eva_descar",// Descargos		   		   		   		   		   		   
		   "t_15" => "t_descargos_t",
		   "t_16" => "t_descargos_i",
		   "t_17" => "t_descargos_d",
		   "t_19" => "t_descargos",		   		   		   		   		   		   
		   "t_20" => "t_tipo_descar",		   		   		   
		   "t_21" => "t_sol_cap_i_e",// Solicitud de capacitacines
		   "t_22" => "t_sol_cap_i_e",
		   "t_23" => "t_sol_cap_i",
		   "t_24" => "t_sol_cap",		   		   
		   "t_25" => "t_tipo_capa",		   		   		   
		   "t_26" => "t_areas_capa",
		   "t_28" => "t_dotaciones",// Dotaciones		   
		   "t_29" => "t_grup_dota_m",
		   "t_33" => "t_mat_dota_tll",		   		   		   		   
		   "t_32" => "t_mat_dota",
		   "t_31" => "t_lineas_dot",		   		   		   
		   "t_1" => "t_cargos_sa",// salarios en los cargos		   
		   "c_0" => "c_general_dh",// Centros de costos y dias habiles --- TABLAS DE CONFIGURACION -----------------------------------
		   "h_0" => "h_logmaestros",// Log maestros 
		   "n_0" => "n_asalarial_d",// Escalas salariales  -- TABLAS DE NOMINA -------------------------------------------------------		   
		   "n_1" => "n_vacaciones_p",// Vacaciones		   		   
		   "n_2" => "n_libvacaciones",		   		   
		   "n_3" => "n_vacaciones",		   		  
		   "n_4" => "n_pg_embargos",// Embargos 		   
		   "n_5" => "n_pg_primas_ant",// 	
		   "n_6" => "n_novedades",		   // Nomina
		   "n_7" => "n_nomina_e_d_integrar",
		   "n_8" => "n_nomina_e_i",// 		   		   	   		   
		   "n_9" => "n_nomina_e_d",		   		   
		   "n_10" => "n_nomina_e",// 		   
		   "n_11" => "n_nomina",		   		   		   
		   "n_12" => "n_incapacidades",// Incapacidades	  
		   "n_13" => "n_prestamos_tn", // Prestamos		   		   		   		    
		   "n_14" => "n_prestamos",		   		   		   
		   "n_15" => "n_tip_prestamo_tn",	   		   
		   "n_16" => "n_tip_prestamo",	   		   		   
		   "n_17" => "n_primas",	   // Primas		   
		   "n_18" => "n_prima_anti",	 // Primas de antiguedad  		   		   		   
		   "n_19" => "n_rete_conc",	   		   		   		   		   
		   "n_20" => "n_asalarial_d", // Salarios	   		   		   		   
		   "n_21" => "n_asalarial", 
		   "n_22" => "n_salarios",	   		   		   		   		   
		   "n_23" => "n_ausentismos", // Ausentismos
		   "n_24" => "n_cesantias", // Cesantias	   		   		   		   		   		   
		   "n_25" => "n_embargos", // Embargos
		   "n_26" => "n_emp_contratos", // Contratos de empleaod 
		   "n_27" => "n_embargos", // Embargos
		   "n_28" => "n_emp_contratos", 
		   "n_30" => "n_emp_conc_tn", // oonceptos otros automaticos empleados
		   "n_31" => "n_emp_conc", 	  
		   "a_12" => "a_empleados_rete_d", // Rete fuente empleados
		   "a_13" => "a_empleados_rete", 		   		   		   
		   "a_14" => "a_empleados_hj", 
		   "a_15" => "a_empleados_g", 
		   "a_17" => "a_empleados_co", // Quitar esta tabla		   		   
		   "a_18" => "a_empleados_con", 		   		   		   
		   "a_16" => "a_empleados", 		   
		   "t_31" => "t_cargos_a", // Cargos		   		   
		   "t_27" => "t_cargos", // Cargos		   
		   "n_30" => "n_tip_auto_i", // Automaticos 
		   "n_31" => "n_tip_auto_tn", 
		   "n_32" => "n_tip_auto", 		   		   
		   "n_29" => "n_cencostos", // Centros de costos 	
		   "t_30" => "t_grup_dota",			   	   		   		   		   		   
		   "t_31" => "t_fondos",// Fondos prestacionales y seguridad social			   	   		   		   		   		   		   
		);
        $this->getBorrarTablas($tablas);		
       
	    // Fin reseteo 
        $valores=array
        (
            "titulo"    =>  $this->tlis,           
            "ttablas"   =>  $this->ttab,
            "lin"       =>  $this->lin
        );                
        return new ViewModel($valores);
        
    } // Fin listar registros 
    
   // Total para suma de creditos y debitos en documento de empleados
   public function getBorrarTablas($tablas)
   {
   	  $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
      $d = new AlbumTable($this->dbAdapter); // ---------------------------------------------------------- 1 FUNCION DENTRO DEL MODELO (C)
	  
	  foreach($tablas as $tabla )
	  {
	  	 //echo $tabla.' .... Ok <br />';
         $d->modGeneral("delete from ".$tabla);		
	     $d->modGeneral("alter table ".$tabla." auto_increment = 1");
	  }	 
        
   }                
           
}



