<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/human/resource/category/new' => [[['_route' => 'app_human_resource_category_new', '_controller' => 'App\\Controller\\HumanResourceCategoryController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/material/resource/category/new' => [[['_route' => 'app_material_resource_category_new', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
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
        '/GetModifications' => [[['_route' => 'GetModifications', '_controller' => 'App\\Controller\\ModificationPlanningController::getModifications'], null, ['POST' => 0], null, false, false, null]],
        '/ModificationPlanning' => [
            [['_route' => 'ModificationPlanning', '_controller' => 'App\\Controller\\ModificationPlanningController::modificationPlanningGet'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'ModificationPlanningPost', '_controller' => 'App\\Controller\\ModificationPlanningController::modificationPlanningPost'], null, ['POST' => 0], null, false, false, null],
        ],
        '/ModificationPlanningValidation' => [[['_route' => 'ModificationPlanningValidation', '_controller' => 'App\\Controller\\ModificationPlanningController::modificationPlanningValidation'], null, ['POST' => 0], null, false, false, null]],
        '/ModificationDeleteOnUnload' => [[['_route' => 'ModificationDeleteOnUnload', '_controller' => 'App\\Controller\\ModificationPlanningController::modificationDeleteOnUnload'], null, ['GET' => 0], null, false, false, null]],
        '/GetAddPlanning' => [[['_route' => 'GetAddPlanning', '_controller' => 'App\\Controller\\ModificationPlanningController::GetAddPlanning'], null, ['POST' => 0], null, false, false, null]],
        '/GetAutoAddInfos' => [[['_route' => 'GetAutoAddInfos', '_controller' => 'App\\Controller\\ModificationPlanningController::GetAutoAddInfos'], null, ['POST' => 0], null, false, false, null]],
        '/GetErrorsInfos' => [[['_route' => 'GetErrorsInfos', '_controller' => 'App\\Controller\\ModificationPlanningController::GetErrorsInfos'], null, ['POST' => 0], null, false, false, null]],
        '/patients' => [
            [['_route' => 'Patients', '_controller' => 'App\\Controller\\PatientController::patientGet'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'PatientAdd', '_controller' => 'App\\Controller\\PatientController::patientAdd'], null, ['POST' => 0], null, false, false, null],
        ],
        '/patient/edit' => [[['_route' => 'PatientEdit', '_controller' => 'App\\Controller\\PatientController::patientEdit'], null, ['POST' => 0], null, false, false, null]],
        '/user' => [[['_route' => 'User', '_controller' => 'App\\Controller\\UserController::userGet'], null, ['GET' => 0], null, false, false, null]],
        '/userAdd' => [[['_route' => 'UserAdd', '_controller' => 'App\\Controller\\UserController::userAdd'], null, ['POST' => 0], null, false, false, null]],
        '/user/edit' => [[['_route' => 'UserEdit', '_controller' => 'App\\Controller\\UserController::userEdit'], null, ['POST' => 0], null, false, false, null]],
        '/autocompleteUser' => [[['_route' => 'AutocompleteUser', '_controller' => 'App\\Controller\\UserController::autocompleteUser'], null, ['GET' => 0], null, false, false, null]],
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
        '/human-resources-category' => [[['_route' => 'human_resources_category', '_controller' => 'App\\Controller\\HumanResourceController:showCategory'], null, null, null, false, false, null]],
        '/material-resources-category' => [[['_route' => 'material_resources_category', '_controller' => 'App\\Controller\\MaterialResourceController:showCategory'], null, null, null, false, false, null]],
        '/mon_profil' => [[['_route' => 'index_mon_profil', '_controller' => 'App\\Controller\\UserController:edit'], null, null, null, false, false, null]],
        '/settings' => [[['_route' => 'Settings', '_controller' => 'App\\Controller\\SettingsController:settingsGet'], null, ['GET' => 0], null, false, false, null]],
        '/settings/edit' => [[['_route' => 'SettingsEdit', '_controller' => 'App\\Controller\\SettingsController:settingsEdit'], null, ['POST' => 0], null, false, false, null]],
        '/settings/addDefault' => [[['_route' => 'SettingsAddDefault', '_controller' => 'App\\Controller\\SettingsController:settingsAddDefault'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxAppointment' => [[['_route' => 'AjaxAppointment', '_controller' => 'App\\Controller\\AppointmentController::getTargets'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxAppointmentLookAddAutocompletes' => [[['_route' => 'AjaxAppointmentLookAddAutocompletes', '_controller' => 'App\\Controller\\AppointmentController::lookAutocompletes'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxInfosAppointment' => [[['_route' => 'AjaxInfosAppointment', '_controller' => 'App\\Controller\\AppointmentController::getInfosAppointmentById'], null, ['POST' => 0], null, false, false, null]],
        '/autocompleteAppointment' => [[['_route' => 'AutocompleteAppointment', '_controller' => 'App\\Controller\\AppointmentController::autocompleteAppointment'], null, ['GET' => 0], null, false, false, null]],
        '/ajaxPathwayAppointments' => [[['_route' => 'AjaxPathwayAppointments', '_controller' => 'App\\Controller\\PathwayController::getAppointmentsByPathwayId'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxPatient' => [[['_route' => 'AjaxPatient', '_controller' => 'App\\Controller\\PatientController::getDataPatient'], null, ['POST' => 0], null, false, false, null]],
        '/autocompletePatient' => [[['_route' => 'AutocompletePatient', '_controller' => 'App\\Controller\\PatientController::autocompletePatient'], null, ['GET' => 0], null, false, false, null]],
        '/ajaxPathwayActivities' => [[['_route' => 'AjaxPathwayActivities', '_controller' => 'App\\Controller\\PathwayController::getActivitiesByPathwayId'], null, ['POST' => 0], null, false, false, null]],
        '/autocompletePathway' => [[['_route' => 'AutocompletePathway', '_controller' => 'App\\Controller\\PathwayController::autocompletePathway'], null, ['GET' => 0], null, false, false, null]],
        '/ajaxHumanResource' => [[['_route' => 'AjaxHumanResource', '_controller' => 'App\\Controller\\HumanResourceController::getDataHumanResource'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxHumanResourceCategoriesActivities' => [[['_route' => 'AjaxHumanResourceCategoryActivities', '_controller' => 'App\\Controller\\HumanResourceController::getActivitiesByHumanResourceCategoryId'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxMaterialResourceCategoriesActivities' => [[['_route' => 'AjaxMaterialResourceCategoryActivities', '_controller' => 'App\\Controller\\MaterialResourceController::getActivitiesByMaterialResourceCategoryId'], null, ['POST' => 0], null, false, false, null]],
        '/autocompleteHR' => [[['_route' => 'AutocompleteHR', '_controller' => 'App\\Controller\\HumanResourceController::autocompleteHR'], null, ['GET' => 0], null, false, false, null]],
        '/ajaxMaterialResource' => [[['_route' => 'AjaxMaterialResource', '_controller' => 'App\\Controller\\MaterialResourceController::getDataMaterialResource'], null, ['POST' => 0], null, false, false, null]],
        '/autocompleteMR' => [[['_route' => 'AutocompleteMR', '_controller' => 'App\\Controller\\MaterialResourceController::autocompleteMR'], null, ['GET' => 0], null, false, false, null]],
        '/ethics' => [[['_route' => 'Ethics', '_controller' => 'App\\Controller\\EthicsController::index'], null, ['GET' => 0], null, false, false, null]],
        '/ajaxEthicsAddComment' => [[['_route' => 'AjaxEthicsAddComment', '_controller' => 'App\\Controller\\EthicsController::addComment'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxEthicsDeleteComment' => [[['_route' => 'AjaxEthicsDeleteComment', '_controller' => 'App\\Controller\\EthicsController::deleteComment'], null, ['POST' => 0], null, false, false, null]],
        '/ajaxEthicsEditComment' => [[['_route' => 'AjaxEthicsEditComment', '_controller' => 'App\\Controller\\EthicsController::editComment'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/human(?'
                    .'|/resource/(?'
                        .'|category/([^/]++)(?'
                            .'|/edit(*:54)'
                            .'|(*:61)'
                        .')'
                        .'|([^/]++)(*:77)'
                    .')'
                    .'|\\-resource(?'
                        .'|/([^/]++)/(?'
                            .'|delete(*:117)'
                            .'|appointments(*:137)'
                        .')'
                        .'|\\-category/([^/]++)/delete(*:172)'
                    .')'
                .')'
                .'|/material(?'
                    .'|/resource/(?'
                        .'|category/([^/]++)(?'
                            .'|/edit(*:232)'
                            .'|(*:240)'
                        .')'
                        .'|([^/]++)(*:257)'
                    .')'
                    .'|\\-resource(?'
                        .'|/([^/]++)/(?'
                            .'|delete(*:298)'
                            .'|appointments(*:318)'
                        .')'
                        .'|\\-category/([^/]++)/delete(*:353)'
                    .')'
                .')'
                .'|/circuit/([^/]++)(*:380)'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:419)'
                    .'|wdt/([^/]++)(*:439)'
                    .'|profiler/([^/]++)(?'
                        .'|/(?'
                            .'|search/results(*:485)'
                            .'|router(*:499)'
                            .'|exception(?'
                                .'|(*:519)'
                                .'|\\.css(*:532)'
                            .')'
                        .')'
                        .'|(*:542)'
                    .')'
                .')'
                .'|/pat(?'
                    .'|ient/([^/]++)/delete(*:579)'
                    .'|hway/edit/([^/]++)(*:605)'
                .')'
                .'|/user/([^/]++)/delete(*:635)'
                .'|/a(?'
                    .'|ppointment/([^/]++)/delete(*:674)'
                    .'|ctivity/([^/]++)/appointments(*:711)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        54 => [[['_route' => 'app_human_resource_category_edit', '_controller' => 'App\\Controller\\HumanResourceCategoryController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        61 => [[['_route' => 'app_human_resource_category_delete', '_controller' => 'App\\Controller\\HumanResourceCategoryController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        77 => [[['_route' => 'app_human_resource_delete', '_controller' => 'App\\Controller\\HumanResourceController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        117 => [[['_route' => 'HumanResourceDelete', '_controller' => 'App\\Controller\\HumanResourceController::delete'], ['id'], ['POST' => 0], null, false, false, null]],
        137 => [[['_route' => 'GetAppointmentsFromHumanResourceId', '_controller' => 'App\\Controller\\HumanResourceController::GetAppointmentFromHumanResourceId'], ['id'], ['GET' => 0], null, false, false, null]],
        172 => [[['_route' => 'HumanResourceCategoryDelete', '_controller' => 'App\\Controller\\HumanResourceCategoryController::delete'], ['id'], ['POST' => 0], null, false, false, null]],
        232 => [[['_route' => 'app_material_resource_category_edit', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        240 => [[['_route' => 'app_material_resource_category_delete', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        257 => [[['_route' => 'app_material_resource_delete', '_controller' => 'App\\Controller\\MaterialResourceController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        298 => [[['_route' => 'MaterialResourceDelete', '_controller' => 'App\\Controller\\MaterialResourceController::delete'], ['id'], ['POST' => 0], null, false, false, null]],
        318 => [[['_route' => 'GetAppointmentsFromMaterialResourceId', '_controller' => 'App\\Controller\\MaterialResourceController::GetAppointmentFromMaterialResourceId'], ['id'], ['GET' => 0], null, false, false, null]],
        353 => [[['_route' => 'MaterialResourceCategoryDelete', '_controller' => 'App\\Controller\\MaterialResourceCategoryController::delete'], ['id'], ['POST' => 0], null, false, false, null]],
        380 => [[['_route' => 'app_circuit_show', '_controller' => 'App\\Controller\\PathwayController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        419 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        439 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        485 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        499 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        519 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        532 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        542 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        579 => [[['_route' => 'PatientDelete', '_controller' => 'App\\Controller\\PatientController::patientDelete'], ['id'], ['POST' => 0], null, false, false, null]],
        605 => [[['_route' => 'PathwayEditPage', '_controller' => 'App\\Controller\\PathwayController:pathwayEditPage'], ['id'], ['GET' => 0], null, false, true, null]],
        635 => [[['_route' => 'UserDelete', '_controller' => 'App\\Controller\\UserController::userDelete'], ['id'], ['POST' => 0], null, false, false, null]],
        674 => [[['_route' => 'AppointmentDelete', '_controller' => 'App\\Controller\\AppointmentController::appointmentDelete'], ['id'], ['POST' => 0], null, false, false, null]],
        711 => [
            [['_route' => 'GetAppointmentsFromActivityId', '_controller' => 'App\\Controller\\AppointmentController::GetAppointmentByActivityId'], ['id'], ['GET' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
