<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\States' => 'Admin\Controller\StatesController'
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
            'state-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/manage/states',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'States',
                        'action' => 'add'
                    )
                )
            ),
            'state-delete' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/state/delete[/:id]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'States',
                        'action' => 'delete'
                    )
                )
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
            'layout/leftmenu'=> __DIR__ .'/../view/layout/_leftmenu.phtml',
            'layout/footer'=> __DIR__ .'/../view/layout/_footer.phtml',
            'layout/header'=> __DIR__ .'/../view/layout/_header.phtml',
            'layout/message'=>__DIR__ .'/../view/layout/_message.phtml',
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