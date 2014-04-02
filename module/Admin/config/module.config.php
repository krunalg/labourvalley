<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController'
        )
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'AuthenticationPlugin' => 'Admin\Controller\Plugin\AuthenticationPlugin'
        )
    ),
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action' => 'index'
                    )
                )
            ),
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/home',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action' => 'home'
                    )
                )
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action' => 'logout'
                    )
                )
            ),
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route' => '/admin',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                ),
                /*'may_terminate' => true,
                /*'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )*/
            )
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout'=> __DIR__ .'/../view/layout/layout.phtml',
            'layout/login' => __DIR__ . '/../view/layout/login.phtml',
            'layout/main' => __DIR__ . '/../view/layout/main.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'identitty' => 'Authentication\View\Helper\Identity'
        )
    )
);