<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Home::index');

// ---------- AUTH (Users/Admin/Doctors) ----------
$routes->get('auth/login', 'AuthController::login');
$routes->post('auth/login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// ---------- PATIENT AUTH ----------
$routes->group('patient', function($routes) {
    $routes->match(['get', 'post'], 'login', 'AuthController::Patient_login');
    $routes->post('verify-otp', 'AuthController::Patient_verifyOtp');
    $routes->get('logout', 'AuthController::Patient_logout');
    $routes->get('register','PatientController::create');
    $routes->post('register','PatientController::create');


});

// ---------- DASHBOARDS ----------
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard','DashboardController::index');
    $routes->get('/doctor_dashboard','DashboardController::doctor_index');
});

$routes->group('patient', ['filter' => 'patientAuth'], function($routes) {
    $routes->get('dashboard', 'DashboardController::patient_index');
    $routes->get('profile', 'PatientController::profile');
    $routes->post('update(:num)', 'PatientController::edit/$1');
    $routes->get('appointments/history', 'PatientController::appointmentHistory');
    $routes->get('book','PatientController::book');
    $routes->post('book/save','PatientController::saveBooking');
    $routes->get('appointments/rebook/(:num)','PatientController::rebook/$1');
    $routes->post('appointments/saveRebook','PatientController::saveRebook');
    $routes->get('appointments/reschedule/(:num)', 'PatientController::reschedule/$1');
$routes->post('appointments/reschedule/(:num)', 'PatientController::saveReschedule/$1');

    
    

});

// ---------- USERS (Admin Only) ----------
$routes->group('users', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('create', 'UserController::create');
    $routes->post('store', 'UserController::store');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('delete/(:num)', 'UserController::delete/$1');
    $routes->match(['get', 'post'], 'add_from_user/(:num)', 'DoctorController::adddoctor/$1');
});

// ---------- DOCTORS ----------
$routes->group('doctors', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'DoctorController::listdoctor');
    $routes->get('edit/(:num)', 'DoctorController::edit/$1');
    $routes->post('edit/(:num)', 'DoctorController::edit/$1');
    $routes->get('delete/(:num)', 'DoctorController::delete/$1');
    $routes->get('patients/(:num)', 'DoctorController::doctorPatient/$1');
});

// ---------- PATIENTS (Admin Access) ----------
$routes->group('patients', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'PatientController::index');
    $routes->get('create', 'PatientController::create');
    $routes->post('create', 'PatientController::create');
    $routes->get('edit/(:num)', 'PatientController::edit/$1');
    $routes->post('update/(:num)', 'PatientController::update/$1');
    $routes->get('delete/(:num)', 'PatientController::delete/$1');
     $routes->get('appointments/reschedule/(:num)', 'PatientController::reschedule/$1');
    $routes->post('appointments/reschedule/(:num)', 'PatientController::saveReschedule/$1');
    $routes->get('appointments/update/(:num)','PatientController::UpdateAppointment/$1');
});

// ---------- APPOINTMENTS ----------
$routes->group('appointments', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'AppointmentController::index');
    $routes->get('create', 'AppointmentController::create');
    $routes->post('create', 'AppointmentController::create');
    $routes->get('complete/(:num)', 'AppointmentController::markComplete/$1');
    $routes->get('cancel/(:num)', 'AppointmentController::markCancel/$1');
    $routes->get('export', 'AppointmentController::export');

});


