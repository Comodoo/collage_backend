<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CreditController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ExamResultController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Public data routes (for registration)
Route::get('/programs', [ProgramController::class, 'index']);
Route::get('/programs/{id}', [ProgramController::class, 'show']);
Route::get('/departments', [ProgramController::class, 'departments']);
Route::get('/fees', [FeeController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('/profile', [AuthController::class, 'user']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Programs & Departments (protected operations)
    // Note: GET routes for programs/departments are now public above
    Route::post('/programs', [ProgramController::class, 'store']);
    Route::put('/programs/{id}', [ProgramController::class, 'update']);
    Route::delete('/programs/{id}', [ProgramController::class, 'destroy']);
    
    Route::post('/fees', [FeeController::class, 'store']);
    Route::put('/fees/{id}', [FeeController::class, 'update']);
    Route::delete('/fees/{id}', [FeeController::class, 'destroy']);
    
    Route::post('/departments', [ProgramController::class, 'storeDepartment']);
    Route::put('/departments/{id}', [ProgramController::class, 'updateDepartment']);
    Route::delete('/departments/{id}', [ProgramController::class, 'destroyDepartment']);

    // Credits
    Route::get('/credits', [CreditController::class, 'index']);
    Route::get('/credits/{id}', [CreditController::class, 'show']);
    Route::post('/credits', [CreditController::class, 'store']);
    Route::put('/credits/{id}', [CreditController::class, 'update']);
    Route::delete('/credits/{id}', [CreditController::class, 'destroy']);

    // Staff Management
    Route::get('/staff', [StaffController::class, 'index']);
    Route::post('/staff', [StaffController::class, 'store']);
    Route::get('/staff/{id}', [StaffController::class, 'show']);
    Route::put('/staff/{id}', [StaffController::class, 'update']);
    Route::delete('/staff/{id}', [StaffController::class, 'destroy']);

    // Admin & Accountant Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/accountant-stats', [DashboardController::class, 'accountantStats']);

    // Registrations
    Route::get('/registrations', [RegistrationController::class, 'index']);
    Route::post('/registrations', [RegistrationController::class, 'store']);
    Route::get('/registrations/{id}', [RegistrationController::class, 'show']);
    Route::put('/registrations/{id}', [RegistrationController::class, 'update']);
    Route::delete('/registrations/{id}', [RegistrationController::class, 'destroy']);
    Route::post('/registrations/{id}/approve', [RegistrationController::class, 'approve']);
    Route::post('/registrations/{id}/reject', [RegistrationController::class, 'reject']);

    // Payments
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::post('/payments/{id}/verify-cash', [PaymentController::class, 'verifyCashPayment']);
    Route::get('/registrations/{registrationId}/payments', [PaymentController::class, 'getPaymentsByRegistration']);

    // Exam Results
    Route::get('/exam-results', [ExamResultController::class, 'index']);
    Route::post('/exam-results', [ExamResultController::class, 'store']);
    Route::get('/exam-results/{id}', [ExamResultController::class, 'show']);
    Route::put('/exam-results/{id}', [ExamResultController::class, 'update']);
    Route::delete('/exam-results/{id}', [ExamResultController::class, 'destroy']);
    Route::post('/exam-results/{id}/submit', [ExamResultController::class, 'submit']);
    Route::post('/exam-results/{id}/publish', [ExamResultController::class, 'publish']);
    Route::get('/student-results', [ExamResultController::class, 'getStudentResults']);
    Route::get('/students/{studentId}/results', [ExamResultController::class, 'getStudentResults']);
    Route::get('/course-offerings/{courseOfferingId}/results', [ExamResultController::class, 'getCourseResults']);

    // Courses (Admin only for create/update/delete)
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

    // Instructor Assignments
    Route::get('/courses/{courseId}/instructors', [CourseController::class, 'getInstructorAssignments']);
    Route::post('/courses/{courseId}/assign-instructor', [CourseController::class, 'assignInstructor']);
    Route::post('/courses/{courseId}/remove-instructor/{assignmentId}', [CourseController::class, 'removeInstructor']);
    Route::put('/instructor-assignments/{assignmentId}/status', [CourseController::class, 'updateAssignmentStatus']);
    Route::get('/instructors', [CourseController::class, 'getInstructors']);
    Route::get('/instructor/courses', [CourseController::class, 'getMyCourses']);
});
