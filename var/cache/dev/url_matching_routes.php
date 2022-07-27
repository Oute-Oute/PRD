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
        '/' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, null, null, false, false, null]],
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
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
        '/ModificationPlanningValidation' => [[['_route' => 'ModificationPlanningValidation', '_controller' => 'App\\Controller\\ModificationPlanningController::modificationPlanningValidation'], null, ['POST' => 0], null, false, false, null]],
        '/ModificationDeleteOnUnload' => [[['_route' => 'ModificationDeleteOnUnload', '_controller' => 'App\\Controller\\ModificationPlanningController::modificationDeleteOnUnload'], null, ['GET' => 0], null, false, false, null]],
        '/patients' => [
            [['_route' => 'Patients', '_controller' => 'App\\Controller\\PatientController::patientGet'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'PatientAdd', '_controller' => 'App\\Controller\\PatientController::patientAdd'], null, ['POST' => 0], null, false, false, null],
        ],
        '/patient/edit' => [[['_route' => 'PatientEdit', '_controller' => 'App\\Controller\\PatientController::patientEdit'], null, ['POST' => 0], null, false, false, null]],
        '/user' => [[['_route' => 'User', '_controller' => 'App\\Controller\\UserController::userGet'], null, ['GET' => 0], null, false, false, null]],
        '/userAdd' => [[['_route' => 'UserAdd', '_controller' => 'App\\Controller\\UserController::userAdd'], null, ['POST' => 0], null, false, false, null]],
        '/user/edit' => [[['_route' => 'UserEdit', '_controller' => 'App\\Controller\\UserController::userEdit'], null, ['POST' => 0], null, false, false, null]],
        '/profil' => [
            [['_route' => 'Profil', '_controller' => 'App\\Controller\\ProfilController::profilGet'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'ProfilEdit', '_controller' => 'App\\Controller\\ProfilController::profilEdit'], null, ['POST' => 0], null, false, false, null],
        ],
        '/material-category/edit' => [[['_route' => 'MaterialResourceCategoryEdit', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::edit'], null, ['POST' => 0], null, false, false, null]],
        '/human-category/edit' => [[['_route' => 'HumanResourceCategoryEdit', '_controller' => 'App\\Controller\\HumanResourceCategoryController::edit'], null, ['POST' => 0], null, false, false, null]],
        '/appointments' => [[['_route' => 'Appointment', '_controller' => 'App\\Controller\\AppointmentController::appointmentGet'], null, ['GET' => 0], null, false, false, null]],
        '/appointmentAdd' => [[['_route' => 'AppointmentAdd', '_controller' => 'App\\Controller\\AppointmentController::appointmentAdd'], null, ['POST' => 0], null, false, false, null]],
        '/appointment/edit' => [[['_route' => 'AppointmentEdit', '_controller' => 'App\\Controller\\AppointmentController::appointmentEdit'], null, ['POST' => 0], null, false, false, null]],
        '/pathways' => [[['_route' => 'Pathways', '_controller' => 'App\\Controller\\PathwayController:pathwayGet'], null, ['GET' => 0], null, false, false, null]],
        '/pathway/add' => [
            [['_route' => 'PathwayAdd', '_controller' => 'App\\Controller\\PathwayController:pathwayAdd'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'PathwayAddPage', '_controller' => 'App\\Controller\\PathwayController:pathwayAddPage'], null, ['GET' => 0], null, false, false, null],
        ],
        '/pathway/edit' => [[['_route' => 'PathwayEdit', '_controller' => 'App\\Controller\\PathwayController:pathwayEdit'], null, ['POST' => 0], null, false, false, null]],
        '/pathway/delete' => [[['_route' => 'PathwayDelete', '_controller' => 'App\\Controller\\PathwayController:pathwayDelete'], null, ['POST' => 0], null, false, false, null]],
        '/human-resource/edit' => [[['_route' => 'HumanResourceEdit', '_controller' => 'App\\Controller\\HumanResourceController::edit'], null, ['POST' => 0], null, false, false, null]],
        '/human-resource/unavailability' => [[['_route' => 'HumanResourceUnavailability', '_controller' => 'App\\Controller\\HumanResourceController::unavailability'], null, ['POST' => 0], null, false, false, null]],
        '/material-resource/unavailability' => [[['_route' => 'MaterialResourceUnavailability', '_controller' => 'App\\Controller\\MaterialResourceController::unavailability'], null, ['POST' => 0], null, false, false, null]],
        '/deleteHumanUnavailability' => [[['_route' => 'DeleteHumanUnavailability', '_controller' => 'App\\Controller\\HumanResourceController:deleteUnavailability'], null, ['POST' => 0], null, false, false, null]],
        '/deleteMaterialUnavailability' => [[['_route' => 'DeleteMaterialUnavailability', '_controller' => 'App\\Controller\\MaterialResourceController:deleteUnavailability'], null, ['POST' => 0], null, false, false, null]],
        '/material-resource/edit' => [[['_route' => 'MaterialResourceEdit', '_controller' => 'App\\Controller\\MaterialResourceController::edit'], null, ['POST' => 0], null, false, false, null]],
        '/human-resources' => [
            [['_route' => 'HumanResPost', '_controller' => 'App\\Controller\\HumanResourceController:new'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'index_human_resources', '_controller' => 'App\\Controller\\HumanResourceController:index'], null, null, null, false, false, null],
        ],
        '/material-resources' => [
            [['_route' => 'MaterialResPost', '_controller' => 'App\\Controller\\MaterialResourceController:new'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'index_material_resources', '_controller' => 'App\\Controller\\MaterialResourceController:index'], null, null, null, false, false, null],
        ],
        '/mon_profil' => [[['_route' => 'index_mon_profil', '_controller' => 'App\\Controller\\UserController:edit'], null, null, null, false, false, null]],
        '/settings' => [[['_route' => 'Settings', '_controller' => 'App\\Controller\\SettingsController:settingsGet'], null, ['GET' => 0], null, false, false, null]],
        '/settings/edit' => [[['_route' => 'SettingsEdit', '_controller' => 'App\\Controller\\SettingsController:settingsEdit'], null, ['POST' => 0], null, false, false, null]],
        '/settings/addDefault' => [[['_route' => 'SettingsAddDefault', '_controller' => 'App\\Controller\\SettingsController:settingsAddDefault'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxAppointment' => [[['_route' => 'AjaxAppointment', '_controller' => 'App\\Controller\\AppointmentController::getTargets'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxInfosAppointment' => [[['_route' => 'AjaxInfosAppointment', '_controller' => 'App\\Controller\\AppointmentController::getInfosAppointmentById'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxPatient' => [[['_route' => 'AjaxPatient', '_controller' => 'App\\Controller\\PatientController::getDataPatient'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxPathwayAppointments' => [[['_route' => 'AjaxPathwayAppointments', '_controller' => 'App\\Controller\\PathwayController::getAppointmentsByPathwayId'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxPathwayActivities' => [[['_route' => 'AjaxPathwayActivities', '_controller' => 'App\\Controller\\PathwayController::getActivitiesByPathwayId'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxHumanResource' => [[['_route' => 'AjaxHumanResource', '_controller' => 'App\\Controller\\HumanResourceController::getDataHumanResource'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxMaterialResource' => [[['_route' => 'AjaxMaterialResource', '_controller' => 'App\\Controller\\MaterialResourceController::getDataMaterialResource'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/circuit(?'
                    .'|\\-type/([^/]++)(?'
                        .'|(*:36)'
                        .'|/edit(*:48)'
                        .'|(*:55)'
                    .')'
                    .'|/([^/]++)(*:72)'
                .')'
                .'|/human/resource/(?'
                    .'|category/([^/]++)(?'
                        .'|(*:119)'
                        .'|/edit(*:132)'
                        .'|(*:140)'
                    .')'
                    .'|([^/]++)(?'
                        .'|(*:160)'
                        .'|/edit(*:173)'
                        .'|(*:181)'
                    .')'
                .')'
                .'|/material/resource/(?'
                    .'|category/([^/]++)(?'
                        .'|(*:233)'
                        .'|/edit(*:246)'
                        .'|(*:254)'
                    .')'
                    .'|([^/]++)(?'
                        .'|/edit(*:279)'
                        .'|(*:287)'
                    .')'
                .')'
                .'|/resource(?'
                    .'|/([^/]++)(?'
                        .'|(*:321)'
                        .'|/edit(*:334)'
                        .'|(*:342)'
                    .')'
                    .'|s\\-types/([^/]++)(?'
                        .'|(*:371)'
                        .'|/edit(*:384)'
                        .'|(*:392)'
                    .')'
                .')'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:433)'
                    .'|wdt/([^/]++)(*:453)'
                    .'|profiler/([^/]++)(?'
                        .'|/(?'
                            .'|search/results(*:499)'
                            .'|router(*:513)'
                            .'|exception(?'
                                .'|(*:533)'
                                .'|\\.css(*:546)'
                            .')'
                        .')'
                        .'|(*:556)'
                    .')'
                .')'
                .'|/pat(?'
                    .'|ient/([^/]++)/delete(*:593)'
                    .'|hway/edit/([^/]++)(*:619)'
                .')'
                .'|/user/([^/]++)/delete(*:649)'
                .'|/appointment/([^/]++)/delete(*:685)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        36 => [[['_route' => 'app_circuit_type_show', '_controller' => 'App\\Controller\\CircuitTypeController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        48 => [[['_route' => 'app_circuit_type_edit', '_controller' => 'App\\Controller\\CircuitTypeController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        55 => [[['_route' => 'app_circuit_type_delete', '_controller' => 'App\\Controller\\CircuitTypeController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        72 => [[['_route' => 'app_circuit_show', '_controller' => 'App\\Controller\\PathwayController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        119 => [[['_route' => 'app_human_resource_category_show', '_controller' => 'App\\Controller\\HumanResourceCategoryController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        132 => [[['_route' => 'app_human_resource_category_edit', '_controller' => 'App\\Controller\\HumanResourceCategoryController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        140 => [[['_route' => 'app_human_resource_category_delete', '_controller' => 'App\\Controller\\HumanResourceCategoryController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        160 => [[['_route' => 'app_human_resource_show', '_controller' => 'App\\Controller\\HumanResourceController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        173 => [[['_route' => 'app_human_resource_edit', '_controller' => 'App\\Controller\\HumanResourceController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        181 => [[['_route' => 'app_human_resource_delete', '_controller' => 'App\\Controller\\HumanResourceController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        233 => [[['_route' => 'app_material_resource_category_show', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        246 => [[['_route' => 'app_material_resource_category_edit', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        254 => [[['_route' => 'app_material_resource_category_delete', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        279 => [[['_route' => 'app_material_resource_edit', '_controller' => 'App\\Controller\\MaterialResourceController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        287 => [[['_route' => 'app_material_resource_delete', '_controller' => 'App\\Controller\\MaterialResourceController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        321 => [[['_route' => 'app_resource_show', '_controller' => 'App\\Controller\\ResourceController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        334 => [[['_route' => 'app_resource_edit', '_controller' => 'App\\Controller\\ResourceController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        342 => [[['_route' => 'app_resource_delete', '_controller' => 'App\\Controller\\ResourceController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        371 => [[['_route' => 'app_resource_type_show', '_controller' => 'App\\Controller\\ResourceTypeController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        384 => [[['_route' => 'app_resource_type_edit', '_controller' => 'App\\Controller\\ResourceTypeController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        392 => [[['_route' => 'app_resource_type_delete', '_controller' => 'App\\Controller\\ResourceTypeController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        433 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        453 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        499 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        513 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        533 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        546 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        556 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        593 => [[['_route' => 'PatientDelete', '_controller' => 'App\\Controller\\PatientController::patientDelete'], ['id'], ['POST' => 0], null, false, false, null]],
        619 => [[['_route' => 'PathwayEditPage', '_controller' => 'App\\Controller\\PathwayController:pathwayEditPage'], ['id'], ['GET' => 0], null, false, true, null]],
        649 => [[['_route' => 'UserDelete', '_controller' => 'App\\Controller\\UserController::userDelete'], ['id'], ['POST' => 0], null, false, false, null]],
        685 => [
            [['_route' => 'AppointmentDelete', '_controller' => 'App\\Controller\\AppointmentController::appointmentDelete'], ['id'], ['POST' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
