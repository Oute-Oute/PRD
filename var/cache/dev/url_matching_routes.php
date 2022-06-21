<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/activity' => [[['_route' => 'app_activity_index', '_controller' => 'App\\Controller\\ActivityController::index'], null, ['GET' => 0], null, true, false, null]],
        '/activity/new' => [[['_route' => 'app_activity_new', '_controller' => 'App\\Controller\\ActivityController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/circuit' => [[['_route' => 'app_circuit_index', '_controller' => 'App\\Controller\\CircuitController::index'], null, ['GET' => 0], null, true, false, null]],
        '/circuit/new' => [[['_route' => 'app_circuit_new', '_controller' => 'App\\Controller\\CircuitController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/circuit-type' => [[['_route' => 'app_circuit_type_index', '_controller' => 'App\\Controller\\CircuitTypeController::index'], null, ['GET' => 0], null, true, false, null]],
        '/circuit-type/new' => [[['_route' => 'app_circuit_type_new', '_controller' => 'App\\Controller\\CircuitTypeController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/patient' => [[['_route' => 'app_patient_index', '_controller' => 'App\\Controller\\PatientController::index'], null, ['GET' => 0], null, true, false, null]],
        '/patient/new' => [[['_route' => 'app_patient_new', '_controller' => 'App\\Controller\\PatientController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/resource' => [[['_route' => 'app_resource_index', '_controller' => 'App\\Controller\\ResourceController::index'], null, ['GET' => 0], null, true, false, null]],
        '/resource/new' => [[['_route' => 'app_resource_new', '_controller' => 'App\\Controller\\ResourceController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/resource/type/new' => [[['_route' => 'app_resource_type_new', '_controller' => 'App\\Controller\\ResourceTypeController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/user' => [[['_route' => 'app_user_index', '_controller' => 'App\\Controller\\UserController::index'], null, ['GET' => 0], null, true, false, null]],
        '/user/new' => [[['_route' => 'app_user_new', '_controller' => 'App\\Controller\\UserController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/' => [[['_route' => 'calendrier', '_controller' => 'App\\Controller\\DefaultController::index'], null, null, null, false, false, null]],
        '/add_user' => [[['_route' => 'add_user', '_controller' => 'App\\Controller\\UserController::new'], null, null, null, false, false, null]],
        '/consult_users' => [[['_route' => 'consult_users', '_controller' => 'App\\Controller\\UserController::index'], null, null, null, false, false, null]],
        '/ModificationPlanning' => [[['_route' => 'ModificationPlanning', '_controller' => 'App\\Controller\\ModificationPlanningController::modificationPlanningGet'], null, null, null, false, false, null]],
        '/patients' => [[['_route' => 'index_patients', '_controller' => 'App\\Controller\\PatientController::index'], null, null, null, false, false, null]],
        '/activities' => [[['_route' => 'index_activities', '_controller' => 'App\\Controller\\ActivityController:index'], null, null, null, false, false, null]],
        '/circuits' => [[['_route' => 'index_circuits', '_controller' => 'App\\Controller\\CircuitController:index'], null, null, null, false, false, null]],
        '/type-circuits' => [[['_route' => 'index_type_circuits', '_controller' => 'App\\Controller\\CircuitTypeController:index'], null, null, null, false, false, null]],
        '/resources' => [[['_route' => 'index_resources', '_controller' => 'App\\Controller\\ResourceController:index'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/activity/([^/]++)(?'
                    .'|(*:28)'
                    .'|/edit(*:40)'
                    .'|(*:47)'
                .')'
                .'|/circuit(?'
                    .'|/([^/]++)(?'
                        .'|(*:78)'
                        .'|/edit(*:90)'
                        .'|(*:97)'
                    .')'
                    .'|\\-type/([^/]++)(?'
                        .'|(*:123)'
                        .'|/edit(*:136)'
                        .'|(*:144)'
                    .')'
                .')'
                .'|/patient/([^/]++)(?'
                    .'|(*:174)'
                    .'|/edit(*:187)'
                    .'|(*:195)'
                .')'
                .'|/resource/(?'
                    .'|([^/]++)(?'
                        .'|(*:228)'
                        .'|/edit(*:241)'
                        .'|(*:249)'
                    .')'
                    .'|type(?'
                        .'|(*:265)'
                        .'|/([^/]++)(?'
                            .'|(*:285)'
                            .'|/edit(*:298)'
                            .'|(*:306)'
                        .')'
                    .')'
                .')'
                .'|/user/([^/]++)(?'
                    .'|(*:334)'
                    .'|/edit(*:347)'
                    .'|(*:355)'
                .')'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:395)'
                    .'|wdt/([^/]++)(*:415)'
                    .'|profiler/([^/]++)(?'
                        .'|/(?'
                            .'|search/results(*:461)'
                            .'|router(*:475)'
                            .'|exception(?'
                                .'|(*:495)'
                                .'|\\.css(*:508)'
                            .')'
                        .')'
                        .'|(*:518)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        28 => [[['_route' => 'app_activity_show', '_controller' => 'App\\Controller\\ActivityController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        40 => [[['_route' => 'app_activity_edit', '_controller' => 'App\\Controller\\ActivityController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        47 => [[['_route' => 'app_activity_delete', '_controller' => 'App\\Controller\\ActivityController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        78 => [[['_route' => 'app_circuit_show', '_controller' => 'App\\Controller\\CircuitController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        90 => [[['_route' => 'app_circuit_edit', '_controller' => 'App\\Controller\\CircuitController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        97 => [[['_route' => 'app_circuit_delete', '_controller' => 'App\\Controller\\CircuitController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        123 => [[['_route' => 'app_circuit_type_show', '_controller' => 'App\\Controller\\CircuitTypeController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        136 => [[['_route' => 'app_circuit_type_edit', '_controller' => 'App\\Controller\\CircuitTypeController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        144 => [[['_route' => 'app_circuit_type_delete', '_controller' => 'App\\Controller\\CircuitTypeController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        174 => [[['_route' => 'app_patient_show', '_controller' => 'App\\Controller\\PatientController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        187 => [[['_route' => 'app_patient_edit', '_controller' => 'App\\Controller\\PatientController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        195 => [[['_route' => 'app_patient_delete', '_controller' => 'App\\Controller\\PatientController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        228 => [[['_route' => 'app_resource_show', '_controller' => 'App\\Controller\\ResourceController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        241 => [[['_route' => 'app_resource_edit', '_controller' => 'App\\Controller\\ResourceController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        249 => [[['_route' => 'app_resource_delete', '_controller' => 'App\\Controller\\ResourceController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        265 => [[['_route' => 'app_resource_type_index', '_controller' => 'App\\Controller\\ResourceTypeController::index'], [], ['GET' => 0], null, true, false, null]],
        285 => [[['_route' => 'app_resource_type_show', '_controller' => 'App\\Controller\\ResourceTypeController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        298 => [[['_route' => 'app_resource_type_edit', '_controller' => 'App\\Controller\\ResourceTypeController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        306 => [[['_route' => 'app_resource_type_delete', '_controller' => 'App\\Controller\\ResourceTypeController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        334 => [[['_route' => 'app_user_show', '_controller' => 'App\\Controller\\UserController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        347 => [[['_route' => 'app_user_edit', '_controller' => 'App\\Controller\\UserController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        355 => [[['_route' => 'app_user_delete', '_controller' => 'App\\Controller\\UserController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        395 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        415 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        461 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        475 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        495 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        508 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        518 => [
            [['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
