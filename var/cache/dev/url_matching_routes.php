<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/circuit-type' => [[['_route' => 'app_circuit_type_index', '_controller' => 'App\\Controller\\CircuitTypeController::index'], null, ['GET' => 0], null, true, false, null]],
        '/circuit-type/new' => [[['_route' => 'app_circuit_type_new', '_controller' => 'App\\Controller\\CircuitTypeController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/human/resource/category' => [[['_route' => 'app_human_resource_category_index', '_controller' => 'App\\Controller\\HumanResourceCategoryController::index'], null, ['GET' => 0], null, true, false, null]],
        '/human/resource/category/new' => [[['_route' => 'app_human_resource_category_new', '_controller' => 'App\\Controller\\HumanResourceCategoryController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/human/resource/new' => [[['_route' => 'app_human_resource_new', '_controller' => 'App\\Controller\\HumanResourceController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/material/resource/category' => [[['_route' => 'app_material_resource_category_index', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::index'], null, ['GET' => 0], null, true, false, null]],
        '/material/resource/category/new' => [[['_route' => 'app_material_resource_category_new', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/material/resource' => [[['_route' => 'app_material_resource_index', '_controller' => 'App\\Controller\\MaterialResourceController::index'], null, ['GET' => 0], null, true, false, null]],
        '/material/resource/new' => [[['_route' => 'app_material_resource_new', '_controller' => 'App\\Controller\\MaterialResourceController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/resource' => [[['_route' => 'app_human_resource_index', '_controller' => 'App\\Controller\\ResourceController::index'], null, ['GET' => 0], null, true, false, null]],
        '/resource/new' => [[['_route' => 'app_resource_new', '_controller' => 'App\\Controller\\ResourceController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/resources-types' => [[['_route' => 'app_resource_type_index', '_controller' => 'App\\Controller\\ResourceTypeController::index'], null, ['GET' => 0], null, true, false, null]],
        '/resources-types-humans' => [[['_route' => 'app_resource_type_index_humans', '_controller' => 'App\\Controller\\ResourceTypeController::indexFilteredHumans'], null, ['GET' => 0], null, false, false, null]],
        '/resources-types-materials' => [[['_route' => 'app_resource_type_index_materials', '_controller' => 'App\\Controller\\ResourceTypeController::indexFilteredMaterials'], null, ['GET' => 0], null, false, false, null]],
        '/resources-types/new' => [[['_route' => 'app_resource_type_new', '_controller' => 'App\\Controller\\ResourceTypeController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, null, null, false, false, null]],
        '/user' => [[['_route' => 'app_user_index', '_controller' => 'App\\Controller\\UserController::index'], null, ['GET' => 0], null, true, false, null]],
        '/user/new' => [[['_route' => 'app_user_new', '_controller' => 'App\\Controller\\UserController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/' => [[['_route' => 'calendrier', '_controller' => 'App\\Controller\\ConsultationPlanningController::consultationPlanningGet'], null, null, null, false, false, null]],
        '/add_user' => [[['_route' => 'add_user', '_controller' => 'App\\Controller\\UserController::new'], null, null, null, false, false, null]],
        '/consult_users' => [[['_route' => 'consult_users', '_controller' => 'App\\Controller\\UserController::index'], null, null, null, false, false, null]],
        '/ConsultationPlanning' => [[['_route' => 'ConsultationPlanning', '_controller' => 'App\\Controller\\ConsultationPlanningController::consultationPlanningGet'], null, null, null, false, false, null]],
        '/connexion' => [
            [['_route' => 'Connexion', '_controller' => 'App\\Controller\\ConnexionController::afficherPage'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'ConnexionPost', '_controller' => 'App\\Controller\\ConnexionController::connexionPost'], null, ['POST' => 0], null, false, false, null],
        ],
        '/ModificationPlanning' => [
            [['_route' => 'ModificationPlanning', '_controller' => 'App\\Controller\\ModificationPlanningController::modificationPlanningGet'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'ModificationPlanningPost', '_controller' => 'App\\Controller\\ModificationPlanningController::modificationPlanningPost'], null, ['POST' => 0], null, false, false, null],
        ],
        '/patients' => [
            [['_route' => 'Patients', '_controller' => 'App\\Controller\\PatientController::patientGet'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'PatientsPost', '_controller' => 'App\\Controller\\PatientController::patientPost'], null, ['POST' => 0], null, false, false, null],
        ],
        '/patient/edit' => [[['_route' => 'PatientEdit', '_controller' => 'App\\Controller\\PatientController::patientEdit'], null, ['POST' => 0], null, false, false, null]],
        '/appointments' => [[['_route' => 'Appointment', '_controller' => 'App\\Controller\\AppointmentController::appointmentGet'], null, ['GET' => 0], null, false, false, null]],
        '/appointmentAdd' => [[['_route' => 'AppointmentAdd', '_controller' => 'App\\Controller\\AppointmentController::appointmentAdd'], null, ['POST' => 0], null, false, false, null]],
        '/appointment/edit' => [[['_route' => 'AppointmentEdit', '_controller' => 'App\\Controller\\AppointmentController::appointmentEdit'], null, ['POST' => 0], null, false, false, null]],
        '/activities' => [[['_route' => 'app_activity_index', '_controller' => 'App\\Controller\\ActivityController:index'], null, null, null, false, false, null]],
        '/pathways' => [
            [['_route' => 'Pathways', '_controller' => 'App\\Controller\\PathwayController:index'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'PathwaysPost', '_controller' => 'App\\Controller\\PathwayController:new'], null, ['POST' => 0], null, false, false, null],
        ],
        '/human-resources' => [[['_route' => 'index_human_resources', '_controller' => 'App\\Controller\\HumanResourceController:index'], null, null, null, false, false, null]],
        '/material-resources' => [[['_route' => 'index_material_resources', '_controller' => 'App\\Controller\\MaterialResourceController:index'], null, null, null, false, false, null]],
        '/mon_profil' => [[['_route' => 'index_mon_profil', '_controller' => 'App\\Controller\\UserController:edit'], null, null, null, false, false, null]],
        '/activities/new' => [[['_route' => 'app_activity_new', '_controller' => 'App\\Controller\\ActivityController:new'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/a(?'
                    .'|ctivity/(?'
                        .'|([^/]++)(*:31)'
                        .'|edit(*:42)'
                        .'|([^/]++)(*:57)'
                    .')'
                    .'|ppointment/([^/]++)/delete(*:91)'
                .')'
                .'|/circuit(?'
                    .'|\\-type/([^/]++)(?'
                        .'|(*:128)'
                        .'|/edit(*:141)'
                        .'|(*:149)'
                    .')'
                    .'|/([^/]++)(?'
                        .'|(*:170)'
                        .'|/edit(*:183)'
                        .'|(*:191)'
                    .')'
                .')'
                .'|/human/resource/(?'
                    .'|category/([^/]++)(?'
                        .'|(*:240)'
                        .'|/edit(*:253)'
                        .'|(*:261)'
                    .')'
                    .'|([^/]++)(?'
                        .'|(*:281)'
                        .'|/edit(*:294)'
                        .'|(*:302)'
                    .')'
                .')'
                .'|/material/resource/(?'
                    .'|category/([^/]++)(?'
                        .'|(*:354)'
                        .'|/edit(*:367)'
                        .'|(*:375)'
                    .')'
                    .'|([^/]++)(?'
                        .'|(*:395)'
                        .'|/edit(*:408)'
                        .'|(*:416)'
                    .')'
                .')'
                .'|/resource(?'
                    .'|/([^/]++)(?'
                        .'|(*:450)'
                        .'|/edit(*:463)'
                        .'|(*:471)'
                    .')'
                    .'|s\\-types/([^/]++)(?'
                        .'|(*:500)'
                        .'|/edit(*:513)'
                        .'|(*:521)'
                    .')'
                .')'
                .'|/user/(?'
                    .'|([^/]++)(?'
                        .'|(*:551)'
                        .'|/edit(*:564)'
                    .')'
                    .'|profile/([^/]++)(*:589)'
                    .'|([^/]++)(*:605)'
                .')'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:645)'
                    .'|wdt/([^/]++)(*:665)'
                    .'|profiler/([^/]++)(?'
                        .'|/(?'
                            .'|search/results(*:711)'
                            .'|router(*:725)'
                            .'|exception(?'
                                .'|(*:745)'
                                .'|\\.css(*:758)'
                            .')'
                        .')'
                        .'|(*:768)'
                    .')'
                .')'
                .'|/patient/([^/]++)/delete(*:802)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        31 => [[['_route' => 'app_activity_show', '_controller' => 'App\\Controller\\ActivityController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        42 => [[['_route' => 'app_activity_edit', '_controller' => 'App\\Controller\\ActivityController::edit'], [], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        57 => [[['_route' => 'app_activity_delete', '_controller' => 'App\\Controller\\ActivityController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        91 => [[['_route' => 'AppointmentDelete', '_controller' => 'App\\Controller\\AppointmentController::appointmentDelete'], ['id'], ['POST' => 0], null, false, false, null]],
        128 => [[['_route' => 'app_circuit_type_show', '_controller' => 'App\\Controller\\CircuitTypeController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        141 => [[['_route' => 'app_circuit_type_edit', '_controller' => 'App\\Controller\\CircuitTypeController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        149 => [[['_route' => 'app_circuit_type_delete', '_controller' => 'App\\Controller\\CircuitTypeController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        170 => [[['_route' => 'app_circuit_show', '_controller' => 'App\\Controller\\PathwayController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        183 => [[['_route' => 'app_circuit_edit', '_controller' => 'App\\Controller\\PathwayController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        191 => [[['_route' => 'app_circuit_delete', '_controller' => 'App\\Controller\\PathwayController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        240 => [[['_route' => 'app_human_resource_category_show', '_controller' => 'App\\Controller\\HumanResourceCategoryController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        253 => [[['_route' => 'app_human_resource_category_edit', '_controller' => 'App\\Controller\\HumanResourceCategoryController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        261 => [[['_route' => 'app_human_resource_category_delete', '_controller' => 'App\\Controller\\HumanResourceCategoryController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        281 => [[['_route' => 'app_human_resource_show', '_controller' => 'App\\Controller\\HumanResourceController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        294 => [[['_route' => 'app_human_resource_edit', '_controller' => 'App\\Controller\\HumanResourceController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        302 => [[['_route' => 'app_human_resource_delete', '_controller' => 'App\\Controller\\HumanResourceController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        354 => [[['_route' => 'app_material_resource_category_show', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        367 => [[['_route' => 'app_material_resource_category_edit', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        375 => [[['_route' => 'app_material_resource_category_delete', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        395 => [[['_route' => 'app_material_resource_show', '_controller' => 'App\\Controller\\MaterialResourceController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        408 => [[['_route' => 'app_material_resource_edit', '_controller' => 'App\\Controller\\MaterialResourceController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        416 => [[['_route' => 'app_material_resource_delete', '_controller' => 'App\\Controller\\MaterialResourceController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        450 => [[['_route' => 'app_resource_show', '_controller' => 'App\\Controller\\ResourceController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        463 => [[['_route' => 'app_resource_edit', '_controller' => 'App\\Controller\\ResourceController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        471 => [[['_route' => 'app_resource_delete', '_controller' => 'App\\Controller\\ResourceController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        500 => [[['_route' => 'app_resource_type_show', '_controller' => 'App\\Controller\\ResourceTypeController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        513 => [[['_route' => 'app_resource_type_edit', '_controller' => 'App\\Controller\\ResourceTypeController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        521 => [[['_route' => 'app_resource_type_delete', '_controller' => 'App\\Controller\\ResourceTypeController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        551 => [[['_route' => 'app_user_show', '_controller' => 'App\\Controller\\UserController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        564 => [[['_route' => 'app_user_edit', '_controller' => 'App\\Controller\\UserController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        589 => [[['_route' => 'app_user_edit_profile', '_controller' => 'App\\Controller\\UserController::editProfile'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        605 => [[['_route' => 'app_user_delete', '_controller' => 'App\\Controller\\UserController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        645 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        665 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        711 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        725 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        745 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        758 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        768 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        802 => [
            [['_route' => 'PatientDelete', '_controller' => 'App\\Controller\\PatientController::patientDelete'], ['id'], ['POST' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
