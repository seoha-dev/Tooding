<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// 페이지 관련 경로
$routes->get('pages/(:segment)', 'Pages::view/$1');
$routes->get('pages', 'Pages::index');

// 'todo' 관련 경로
$routes->post('todo/add', 'TodoController::add_todo');
$routes->get('todo', 'TodoController::index');
$routes->get('todo/reload', 'TodoController::getTodos');
$routes->post('todo/hide', 'TodoController::hide_todo');
$routes->post('todo/complete', 'TodoController::complete_todo');
$routes->post('todo/update', 'TodoController::update_todo');

// 여기는 'todo'가 아닌 다른 페이지를 라우트하기 위한 경로로 별도로 정의할 수 있음
// 예: $routes->get('todo/view', 'TodoController::view');

// '(:segment)'는 가장 아래에 위치해야 합니다.
$routes->get('(:segment)', 'Pages::view/$1');
