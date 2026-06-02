<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OperationsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [OperationsController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // OPERATIONS DASHBOARD
    Route::get('/operations/dashboard', [OperationsController::class, 'dashboard'])->name('operations.dashboard');

    // CLIENTS
    Route::get('/operations/clients', [OperationsController::class, 'clients'])->name('operations.clients');
    Route::get('/operations/clients/create', [OperationsController::class, 'createClient'])->name('operations.clients.create');
    Route::post('/operations/clients', [OperationsController::class, 'storeClient'])->name('operations.clients.store');
    Route::get('/operations/clients/{id}/edit', [OperationsController::class, 'editClient'])->name('operations.clients.edit');
    Route::put('/operations/clients/{id}', [OperationsController::class, 'updateClient'])->name('operations.clients.update');
    Route::get('/operations/clients/{id}', [OperationsController::class, 'showClient'])->name('operations.clients.show');

    // PROJECTS
    Route::get('/operations/projects', [OperationsController::class, 'projects'])->name('operations.projects');
    Route::get('/operations/projects/create', [OperationsController::class, 'createProject'])->name('operations.projects.create');
    Route::post('/operations/projects', [OperationsController::class, 'storeProject'])->name('operations.projects.store');
    Route::get('/operations/projects/{id}/edit', [OperationsController::class, 'editProject'])->name('operations.projects.edit');
    Route::put('/operations/projects/{id}', [OperationsController::class, 'updateProject'])->name('operations.projects.update');
    Route::get('/operations/projects/{id}', [OperationsController::class, 'showProject'])->name('operations.projects.show');

    // TASKS
    Route::post('/operations/projects/{projectId}/tasks', [OperationsController::class, 'storeTask'])->name('operations.tasks.store');
    Route::get('/operations/tasks/{taskId}/edit', [OperationsController::class, 'editTask'])->name('operations.tasks.edit');
    Route::put('/operations/tasks/{taskId}', [OperationsController::class, 'updateTask'])->name('operations.tasks.update');
    Route::patch('/operations/tasks/{taskId}', [OperationsController::class, 'updateTask'])->name('operations.tasks.patch');
    Route::post('/operations/tasks/{taskId}/status', [OperationsController::class, 'updateTaskStatus'])->name('operations.tasks.status');
    Route::get('/operations/tasks/{taskId}', [OperationsController::class, 'showTask'])->name('operations.tasks.show');

    // ACTIVITIES
    Route::get('/operations/activities', [OperationsController::class, 'activities'])->name('operations.activities');
    Route::get('/operations/activities/create', [OperationsController::class, 'createActivity'])->name('operations.activities.create');
    Route::post('/operations/activities', [OperationsController::class, 'storeActivity'])->name('operations.activities.store');
    Route::get('/operations/activities/{id}/edit', [OperationsController::class, 'editActivity'])->name('operations.activities.edit');
    Route::put('/operations/activities/{id}', [OperationsController::class, 'updateActivity'])->name('operations.activities.update');
    Route::get('/operations/activities/{id}', [OperationsController::class, 'showActivity'])->name('operations.activities.show');


    // EMPLOYEES
    Route::get('/operations/employees', [OperationsController::class, 'employees'])->name('operations.employees');
    Route::get('/operations/employees/create', [OperationsController::class, 'createEmployee'])->name('operations.employees.create');
    Route::post('/operations/employees', [OperationsController::class, 'storeEmployee'])->name('operations.employees.store');
    Route::get('/operations/employees/{id}/edit', [OperationsController::class, 'editEmployee'])->name('operations.employees.edit');
    Route::put('/operations/employees/{id}', [OperationsController::class, 'updateEmployee'])->name('operations.employees.update');
    Route::get('/operations/employees/{id}', [OperationsController::class, 'showEmployee'])->name('operations.employees.show');

    // MY TASKS
    Route::get('/operations/my-tasks', [OperationsController::class, 'myTasks'])->name('operations.my_tasks');

    // MY ACTIVITY LOGS
    Route::get('/operations/my-activities', [OperationsController::class, 'myActivities'])->name('operations.my_activities');

    // WORKSPACE
    Route::get('/operations/workspace', [OperationsController::class, 'workspace'])->name('operations.workspace');
    Route::post('/operations/workspace/tasks/{taskId}/quick-log', [OperationsController::class, 'quickLogTask'])->name('operations.workspace.quick_log');
});

require __DIR__.'/auth.php';