<?php
return array(

    // list of all controllers for the current module
    'controllers' => array(
        'invokables' => array(
            'Album\Controller\Album' => 'Album\Controller\AlbumController',
        ),
    ),

    // routing configuration
    'router' => array(
        'routes' => array(
            'album' => array(  // name of the root
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/album[/:action][/:id]', // action and id are optional
                    'constraints' => array (
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Album\Controller\Album',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    // Add view directory to the TemplatePathStack configuration.
    // This will allow it to find the view scripts for the Album module
    // that are stored in our view/ directory
    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),

    // register Album\Entity into doctrine driver
    'doctrine' => array(
        'driver' => array(
            'Album_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Album/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Album\Entity' =>  'Album_driver'
                ),
            ),
        ),
    ),
);
