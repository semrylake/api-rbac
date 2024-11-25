<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->post('/api/v1', 'Home::index');


$routes->group('api', function ($routes) {
     $routes->group('v1', function ($routes) {
          $routes->post('/', 'Home::index');
          
          $routes->group('auth', function ($routes) {
               $routes->post('login', 'AuthControllers::login');
          });
          $routes->group('admin', ['filter' => 'auth'], function ($routes) {
               $routes->post('dashboard', 'DashboardControllers::Index');
          });
          $routes->group('client', ['filter' => 'auth'], function ($routes) {
               $routes->post('getlist', 'Client\ClientControllers::getlist');
               $routes->post('add', 'AuthControllers::register');
               $routes->post('edit', 'Client\ClientControllers::update_client');
               $routes->post('detail', 'Client\ClientControllers::detail_client');
          });
          $routes->group('menu', ['filter' => 'auth'], function ($routes) {
               $routes->post('getlist', 'Mstmenu\MstMenuController::getlist');
               $routes->post('add', 'Mstmenu\MstMenuController::add');
               $routes->post('update-menu', 'Mstmenu\MstMenuController::update_menu');
               $routes->post('delete', 'Mstmenu\MstMenuController::delete_menu');
          });
          $routes->group('master-pengguna', ['filter' => 'auth'], function ($routes) {
               $routes->post('add', 'Mstpengguna\MstPengguna::add');
               $routes->post('getlist', 'Mstpengguna\MstPengguna::getlist');
               $routes->post('edit', 'Mstpengguna\MstPengguna::update_data');
               $routes->post('delete', 'Mstpengguna\MstPengguna::delete_data');
          });
          $routes->group('master-group', ['filter' => 'auth'], function ($routes) {
               $routes->post('add', 'Mstgroup\Mstgrup::tambah');
               $routes->post('getlist', 'Mstgroup\Mstgrup::getlist');
              //  $routes->post('edit', 'Mstpengguna\MstPengguna::update_data');
              //  $routes->post('delete', 'Mstpengguna\MstPengguna::delete_data');
          });
     });
});
