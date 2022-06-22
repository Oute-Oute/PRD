<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    'app_activity_index' => [[], ['_controller' => 'App\\Controller\\ActivityController::index'], [], [['text', '/activity/']], [], [], []],
    'app_activity_new' => [[], ['_controller' => 'App\\Controller\\ActivityController::new'], [], [['text', '/activity/new']], [], [], []],
    'app_activity_show' => [['id'], ['_controller' => 'App\\Controller\\ActivityController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/activity']], [], [], []],
    'app_activity_edit' => [['id'], ['_controller' => 'App\\Controller\\ActivityController::edit'], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/activity']], [], [], []],
    'app_activity_delete' => [['id'], ['_controller' => 'App\\Controller\\ActivityController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/activity']], [], [], []],
    'app_circuit_index' => [[], ['_controller' => 'App\\Controller\\CircuitController::index'], [], [['text', '/circuit/']], [], [], []],
    'app_circuit_new' => [[], ['_controller' => 'App\\Controller\\CircuitController::new'], [], [['text', '/circuit/new']], [], [], []],
    'app_circuit_show' => [['id'], ['_controller' => 'App\\Controller\\CircuitController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/circuit']], [], [], []],
    'app_circuit_edit' => [['id'], ['_controller' => 'App\\Controller\\CircuitController::edit'], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/circuit']], [], [], []],
    'app_circuit_delete' => [['id'], ['_controller' => 'App\\Controller\\CircuitController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/circuit']], [], [], []],
    'app_circuit_type_index' => [[], ['_controller' => 'App\\Controller\\CircuitTypeController::index'], [], [['text', '/circuit-type/']], [], [], []],
    'app_circuit_type_new' => [[], ['_controller' => 'App\\Controller\\CircuitTypeController::new'], [], [['text', '/circuit-type/new']], [], [], []],
    'app_circuit_type_show' => [['id'], ['_controller' => 'App\\Controller\\CircuitTypeController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/circuit-type']], [], [], []],
    'app_circuit_type_edit' => [['id'], ['_controller' => 'App\\Controller\\CircuitTypeController::edit'], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/circuit-type']], [], [], []],
    'app_circuit_type_delete' => [['id'], ['_controller' => 'App\\Controller\\CircuitTypeController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/circuit-type']], [], [], []],
    'app_connexion' => [[], ['_controller' => 'App\\Controller\\ConnexionController::connexionGet'], [], [['text', '/connexion']], [], [], []],
    'app_patient_index' => [[], ['_controller' => 'App\\Controller\\PatientController::index'], [], [['text', '/patient/']], [], [], []],
    'app_patient_new' => [[], ['_controller' => 'App\\Controller\\PatientController::new'], [], [['text', '/patient/new']], [], [], []],
    'app_patient_show' => [['id'], ['_controller' => 'App\\Controller\\PatientController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/patient']], [], [], []],
    'app_patient_edit' => [['id'], ['_controller' => 'App\\Controller\\PatientController::edit'], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/patient']], [], [], []],
    'app_patient_delete' => [['id'], ['_controller' => 'App\\Controller\\PatientController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/patient']], [], [], []],
    'app_resource_index' => [[], ['_controller' => 'App\\Controller\\ResourceController::index'], [], [['text', '/resource/']], [], [], []],
    'app_resource_new' => [[], ['_controller' => 'App\\Controller\\ResourceController::new'], [], [['text', '/resource/new']], [], [], []],
    'app_resource_show' => [['id'], ['_controller' => 'App\\Controller\\ResourceController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/resource']], [], [], []],
    'app_resource_edit' => [['id'], ['_controller' => 'App\\Controller\\ResourceController::edit'], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/resource']], [], [], []],
    'app_resource_delete' => [['id'], ['_controller' => 'App\\Controller\\ResourceController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/resource']], [], [], []],
    'app_resource_type_index' => [[], ['_controller' => 'App\\Controller\\ResourceTypeController::index'], [], [['text', '/resource_type/']], [], [], []],
    'app_resource_type_new' => [[], ['_controller' => 'App\\Controller\\ResourceTypeController::new'], [], [['text', '/resource_type/new']], [], [], []],
    'app_resource_type_show' => [['id'], ['_controller' => 'App\\Controller\\ResourceTypeController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/resource_type']], [], [], []],
    'app_resource_type_edit' => [['id'], ['_controller' => 'App\\Controller\\ResourceTypeController::edit'], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/resource_type']], [], [], []],
    'app_resource_type_delete' => [['id'], ['_controller' => 'App\\Controller\\ResourceTypeController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/resource_type']], [], [], []],
    'app_user_index' => [[], ['_controller' => 'App\\Controller\\UserController::index'], [], [['text', '/user/']], [], [], []],
    'app_user_new' => [[], ['_controller' => 'App\\Controller\\UserController::new'], [], [['text', '/user/new']], [], [], []],
    'app_user_show' => [['id'], ['_controller' => 'App\\Controller\\UserController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/user']], [], [], []],
    'app_user_edit' => [['id'], ['_controller' => 'App\\Controller\\UserController::edit'], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/user']], [], [], []],
    'app_user_delete' => [['id'], ['_controller' => 'App\\Controller\\UserController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/user']], [], [], []],
    '_preview_error' => [['code', '_format'], ['_controller' => 'error_controller::preview', '_format' => 'html'], ['code' => '\\d+'], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '\\d+', 'code', true], ['text', '/_error']], [], [], []],
    '_wdt' => [['token'], ['_controller' => 'web_profiler.controller.profiler::toolbarAction'], [], [['variable', '/', '[^/]++', 'token', true], ['text', '/_wdt']], [], [], []],
    '_profiler_home' => [[], ['_controller' => 'web_profiler.controller.profiler::homeAction'], [], [['text', '/_profiler/']], [], [], []],
    '_profiler_search' => [[], ['_controller' => 'web_profiler.controller.profiler::searchAction'], [], [['text', '/_profiler/search']], [], [], []],
    '_profiler_search_bar' => [[], ['_controller' => 'web_profiler.controller.profiler::searchBarAction'], [], [['text', '/_profiler/search_bar']], [], [], []],
    '_profiler_phpinfo' => [[], ['_controller' => 'web_profiler.controller.profiler::phpinfoAction'], [], [['text', '/_profiler/phpinfo']], [], [], []],
    '_profiler_search_results' => [['token'], ['_controller' => 'web_profiler.controller.profiler::searchResultsAction'], [], [['text', '/search/results'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_open_file' => [[], ['_controller' => 'web_profiler.controller.profiler::openAction'], [], [['text', '/_profiler/open']], [], [], []],
    '_profiler' => [['token'], ['_controller' => 'web_profiler.controller.profiler::panelAction'], [], [['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_router' => [['token'], ['_controller' => 'web_profiler.controller.router::panelAction'], [], [['text', '/router'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_exception' => [['token'], ['_controller' => 'web_profiler.controller.exception_panel::body'], [], [['text', '/exception'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_exception_css' => [['token'], ['_controller' => 'web_profiler.controller.exception_panel::stylesheet'], [], [['text', '/exception.css'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    'calendrier' => [[], ['_controller' => 'App\\Controller\\DefaultController::index'], [], [['text', '/']], [], [], []],
    'add_user' => [[], ['_controller' => 'App\\Controller\\UserController::new'], [], [['text', '/add_user']], [], [], []],
    'consult_users' => [[], ['_controller' => 'App\\Controller\\UserController::index'], [], [['text', '/consult_users']], [], [], []],
    'ConsultationPlanning' => [[], ['_controller' => 'App\\Controller\\ConsultationPlanningController::consultationPlanningGet'], [], [['text', '/ConsultationPlanning']], [], [], []],
    'ModificationPlanning' => [[], ['_controller' => 'App\\Controller\\ModificationPlanningController::modificationPlanningGet'], [], [['text', '/ModificationPlanning']], [], [], []],
    'index_patients' => [[], ['_controller' => 'App\\Controller\\PatientController::index'], [], [['text', '/patients']], [], [], []],
    'index_activities' => [[], ['_controller' => 'App\\Controller\\ActivityController:index'], [], [['text', '/activities']], [], [], []],
    'index_circuits' => [[], ['_controller' => 'App\\Controller\\CircuitController:index'], [], [['text', '/circuits']], [], [], []],
    'index_type_circuits' => [[], ['_controller' => 'App\\Controller\\CircuitTypeController:index'], [], [['text', '/type-circuits']], [], [], []],
    'index_resources' => [[], ['_controller' => 'App\\Controller\\ResourceController:index'], [], [['text', '/resources']], [], [], []],
    'index_resources_types' => [[], ['_controller' => 'App\\Controller\\ResourceTypeController:index'], [], [['text', '/resources-types']], [], [], []],
];
