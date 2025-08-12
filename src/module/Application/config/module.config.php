<?php

declare(strict_types=1);

namespace Application;

use Application\Controller\AppointmentController;
use Application\Controller\DoctorController;
use Application\Controller\TimeSlotController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'api' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'doctors' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/doctors',
                            'defaults' => [
                                'controller' => DoctorController::class,
                                'action' => 'index'
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'get' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/:id',
                                    'defaults' => [
                                        'action' => 'get'
                                    ]
                                ]
                            ],
                            'search' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/search',
                                    'defaults' => [
                                        'action' => 'search'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'timeSlots' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/timeslots',
                            'defaults' => [
                                'controller' => TimeSlotController::class,
                                'action' => 'index'
                            ]
                        ],
                    ],
                    'appointments' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/appointments',
                            'defaults' => [
                                'controller' => AppointmentController::class,
                                'action' => 'index',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'get' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/:id',
                                    'defaults' => [
                                        'action' => 'get'
                                    ],
                                ],
                            ],
                            'create' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/create',
                                    'defaults' => [
                                        'action' => 'create'
                                    ]
                                ]
                            ],
                            'cancel' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/:id/cancel',
                                    'defaults' => [
                                        'action' => 'cancel'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
    ],
    'controllers' => [
        'abstract_factories' => [
            ReflectionBasedAbstractFactory::class
        ],
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\DoctorController::class => ReflectionBasedAbstractFactory::class,
            Controller\TimeSlotController::class => ReflectionBasedAbstractFactory::class,
            Controller\AppointmentController::class => ReflectionBasedAbstractFactory::class
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\AppointmentService::class => ReflectionBasedAbstractFactory::class,
            Service\TimeSlotService::class => ReflectionBasedAbstractFactory::class,
            Service\DoctorService::class => ReflectionBasedAbstractFactory::class,
            Service\NotificationService::class => ReflectionBasedAbstractFactory::class,
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy'
        ]
    ],
];
