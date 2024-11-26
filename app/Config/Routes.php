<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Landing::index');

$routes->get('/login', 'Landing::login');

$routes->post('/login/auth', 'Landing::auth');
$routes->get('/login/auth/jwt/(:any)', 'Landing::auth_jwt/$1');
$routes->get('/logout', 'Landing::logout');

$routes->get('/home', 'Home::index');

// search
$routes->post('/search_db', 'Search::search_db');

// general
$routes->post('/general/delete', 'General::delete');

// users ____________________________________
$routes->get('/user', 'User::index');
$routes->post('/user/add', 'User::add');
$routes->post('/user/update', 'User::update');
$routes->post('/user/reset_password', 'User::reset_password');

// options __________________________________
$routes->get('/options', 'Options::index');
$routes->get('/options/(:any)', 'Options::index/$1');
$routes->post('/options/add', 'Options::add');
$routes->post('/options/update', 'Options::update');

// menu __________________________________
$routes->get('/menu', 'Menu::index');
$routes->get('/menu/(:any)', 'Menu::index/$1');
$routes->post('/menu/add', 'Menu::add');
$routes->post('/menu/update', 'Menu::update');
$routes->post('/menu/copy_menu', 'Menu::copy_menu');

// kelas __________________________________
$routes->get('/kelas/(:any)', 'Kelas::index/$1');
$routes->post('/kelas/add', 'Kelas::add');
$routes->post('/kelas/update', 'Kelas::update');

// siswa __________________________________
$routes->get('/siswa/(:any)', 'Siswa::index/$1');
$routes->post('/siswa/add', 'Siswa::add');
$routes->post('/siswa/update', 'Siswa::update');

// siswa __________________________________
$routes->get('/materi/(:any)', 'Materi::index/$1');
$routes->post('/materi/add', 'Materi::add');
$routes->post('/materi/update', 'Materi::update');
