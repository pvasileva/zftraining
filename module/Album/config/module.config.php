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
);
