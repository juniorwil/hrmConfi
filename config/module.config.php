<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Confi\Controller\Modulo'   => 'Confi\Controller\ModuloController',
            'Confi\Controller\Modulo1'  => 'Confi\Controller\Modulo1Controller',                        
            'Confi\Controller\Roles'    => 'Confi\Controller\RolesController',                                    
            'Confi\Controller\Usuarios' => 'Confi\Controller\UsuariosController',                                    
            'Confi\Controller\Opciones' => 'Confi\Controller\OpcionesController',                                    
            'Confi\Controller\Pnomina'  => 'Confi\Controller\PnominaController',                                    
            'Confi\Controller\General'  => 'Confi\Controller\GeneralController',                                    
            'Confi\Controller\Modulodos'  => 'Confi\Controller\ModulodosController',                                                
            'Confi\Controller\Borrar'  => 'Confi\Controller\BorrarController',            
        ),
    ),
    'router' => array(
        'routes' => array(
            'confi' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/confi',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Confi\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*'
                             ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ), 
    'view_manager' => array(
        'template_path_stack' => array(
            'Confi' => __DIR__ . '/../view',
        ),
    ),
);
