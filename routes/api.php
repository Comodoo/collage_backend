<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CreditController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ExamResultController;
use App\Http\Controllers\Api\Hr\HrProfileController;
use App\Http\Controllers\Api\Budget\BudgetCategoryController;
use App\Http\Controllers\Api\Budget\BudgetController;
use App\Http\Controllers\Api\Budget\BudgetExpenditureController;
use App\Http\Controllers\Api\Budget\BudgetLineController;
use App\Http\Controllers\Api\Budget\BudgetReportController;
use App\Http\Controllers\Api\Budget\FiscalYearController;
use App\Http\Controllers\Api\Salary\SalaryAllowanceController;
use App\Http\Controllers\Api\Salary\SalaryCostProjectionController;
use App\Http\Controllers\Api\Salary\SalaryGradeController;
use App\Http\Controllers\Api\Salary\SalaryStepController;
use App\Http\Controllers\Api\Salary\StaffSalaryPlacementController;
use App\Http\Controllers\Api\Inventory\InventoryAuditController;
use App\Http\Controllers\Api\Inventory\InventoryCategoryController;
use App\Http\Controllers\Api\Inventory\InventoryItemController;
use App\Http\Controllers\Api\Inventory\InventoryLocationController;
use App\Http\Controllers\Api\Operations\OperationsDashboardController;
use App\Http\Controllers\Api\Operations\OperationsReportController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\Proposal\ProposalController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\RoleController;
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
    Route::post('/programs', [ProgramController::class, 'store'])->middleware('permission:programs.create');
    Route::put('/programs/{id}', [ProgramController::class, 'update'])->middleware('permission:programs.update');
    Route::delete('/programs/{id}', [ProgramController::class, 'destroy'])->middleware('permission:programs.delete');

    Route::post('/fees', [FeeController::class, 'store'])->middleware('permission:settings.update');
    Route::put('/fees/{id}', [FeeController::class, 'update'])->middleware('permission:settings.update');
    Route::delete('/fees/{id}', [FeeController::class, 'destroy'])->middleware('permission:settings.update');

    Route::post('/departments', [ProgramController::class, 'storeDepartment'])->middleware('permission:programs.create');
    Route::put('/departments/{id}', [ProgramController::class, 'updateDepartment'])->middleware('permission:programs.update');
    Route::delete('/departments/{id}', [ProgramController::class, 'destroyDepartment'])->middleware('permission:programs.delete');

    // Credits
    Route::get('/credits', [CreditController::class, 'index'])->middleware('permission:settings.view');
    Route::get('/credits/{id}', [CreditController::class, 'show'])->middleware('permission:settings.view');
    Route::post('/credits', [CreditController::class, 'store'])->middleware('permission:settings.update');
    Route::put('/credits/{id}', [CreditController::class, 'update'])->middleware('permission:settings.update');
    Route::delete('/credits/{id}', [CreditController::class, 'destroy'])->middleware('permission:settings.update');

    // Staff Management
    Route::get('/staff', [StaffController::class, 'index']);
    Route::post('/staff', [StaffController::class, 'store']);
    Route::get('/staff/{id}', [StaffController::class, 'show']);
    Route::put('/staff/{id}', [StaffController::class, 'update']);
    Route::delete('/staff/{id}', [StaffController::class, 'destroy']);

    // Roles & Permissions
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/permissions', [RoleController::class, 'permissions']);
    Route::get('/roles/users', [RoleController::class, 'users']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{roleId}', [RoleController::class, 'update']);
    Route::delete('/roles/{roleId}', [RoleController::class, 'destroy']);
    Route::post('/roles/users/{userId}/assign', [RoleController::class, 'assign']);

    // Admin Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->middleware('permission:dashboard.view');

    // Operations & Planning
    Route::get('/operations/dashboard', [OperationsDashboardController::class, 'index'])->middleware('permission:inventory.view');

    // Operations & Financial Reports
    Route::get('/operations-reports/dashboard', [OperationsReportController::class, 'dashboard'])->middleware('permission:reports.operations.view');
    Route::get('/operations-reports/inventory-summary', [OperationsReportController::class, 'inventorySummary'])->middleware('permission:reports.operations.view');
    Route::get('/operations-reports/hr-summary', [OperationsReportController::class, 'hrSummary'])->middleware('permission:reports.operations.view');
    Route::get('/operations-reports/budget-summary', [OperationsReportController::class, 'budgetSummary'])->middleware('permission:reports.operations.view');
    Route::get('/operations-reports/proposal-summary', [OperationsReportController::class, 'proposalSummary'])->middleware('permission:reports.operations.view');
    Route::get('/operations-reports/financial-summary', [OperationsReportController::class, 'financialSummary'])->middleware('permission:reports.financial.view');
    Route::get('/operations-reports/hr-summary-pdf', [OperationsReportController::class, 'hrSummaryPdf'])->middleware('permission:reports.operations.view');
    Route::get('/operations-reports/budget-summary-pdf', [OperationsReportController::class, 'budgetSummaryPdf'])->middleware('permission:reports.operations.view');
    Route::get('/operations-reports/proposal-summary-pdf', [OperationsReportController::class, 'proposalSummaryPdf'])->middleware('permission:reports.operations.view');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->middleware('permission:reports.operations.view');

    // Inventory
    Route::get('/inventory-categories', [InventoryCategoryController::class, 'index'])->middleware('permission:inventory.view');
    Route::post('/inventory-categories', [InventoryCategoryController::class, 'store'])->middleware('permission:inventory.create');

    Route::get('/inventory-locations', [InventoryLocationController::class, 'index'])->middleware('permission:inventory.view');
    Route::post('/inventory-locations', [InventoryLocationController::class, 'store'])->middleware('permission:inventory.create');

    Route::get('/inventory-items', [InventoryItemController::class, 'index'])->middleware('permission:inventory.view');
    Route::get('/inventory-items/export', [InventoryItemController::class, 'export'])->middleware('permission:inventory.export');
    Route::get('/inventory-items/export-pdf', [InventoryItemController::class, 'exportPdf'])->middleware('permission:inventory.export');
    Route::post('/inventory-items', [InventoryItemController::class, 'store'])->middleware('permission:inventory.create');
    Route::get('/inventory-items/{id}', [InventoryItemController::class, 'show'])->middleware('permission:inventory.view');
    Route::put('/inventory-items/{id}', [InventoryItemController::class, 'update'])->middleware('permission:inventory.update');
    Route::delete('/inventory-items/{id}', [InventoryItemController::class, 'destroy'])->middleware('permission:inventory.delete');

    Route::post('/inventory-items/{id}/receive', [InventoryItemController::class, 'receive'])->middleware('permission:inventory.receive');
    Route::post('/inventory-items/{id}/assign', [InventoryItemController::class, 'assign'])->middleware('permission:inventory.assign');
    Route::post('/inventory-items/{id}/transfer', [InventoryItemController::class, 'transfer'])->middleware('permission:inventory.transfer');
    Route::post('/inventory-items/{id}/return', [InventoryItemController::class, 'returnItem'])->middleware('permission:inventory.return');
    Route::post('/inventory-items/{id}/repair', [InventoryItemController::class, 'repair'])->middleware('permission:inventory.update');
    Route::post('/inventory-items/{id}/mark-lost', [InventoryItemController::class, 'markLost'])->middleware('permission:inventory.update');
    Route::post('/inventory-items/{id}/dispose', [InventoryItemController::class, 'dispose'])->middleware('permission:inventory.dispose');

    Route::get('/inventory-items/{id}/history', [InventoryItemController::class, 'history'])->middleware('permission:inventory.view');
    Route::get('/inventory-transactions', [InventoryItemController::class, 'transactions'])->middleware('permission:inventory.view');

    Route::get('/inventory-audits', [InventoryAuditController::class, 'index'])->middleware('permission:inventory.view');
    Route::post('/inventory-audits', [InventoryAuditController::class, 'store'])->middleware('permission:inventory.audit');

    // HR
    Route::get('/hr-profiles', [HrProfileController::class, 'index'])->middleware('permission:hr.view');
    Route::get('/hr-profiles/stats', [HrProfileController::class, 'stats'])->middleware('permission:hr.view');
    Route::get('/hr-profiles/export', [HrProfileController::class, 'export'])->middleware('permission:hr.export');
    Route::post('/hr-profiles', [HrProfileController::class, 'store'])->middleware('permission:hr.create');
    Route::get('/hr-profiles/{id}', [HrProfileController::class, 'show'])->middleware('permission:hr.view');
    Route::put('/hr-profiles/{id}', [HrProfileController::class, 'update'])->middleware('permission:hr.update');
    Route::put('/hr-profiles/{id}/status', [HrProfileController::class, 'status'])->middleware('permission:hr.status.manage');

    Route::get('/hr-profiles/{id}/documents', [HrProfileController::class, 'documents'])->middleware('permission:hr.view');
    Route::post('/hr-profiles/{id}/documents', [HrProfileController::class, 'storeDocument'])->middleware('permission:hr.documents.manage');
    Route::get('/hr-profiles/{profileId}/documents/{documentId}/download', [HrProfileController::class, 'downloadDocument'])->middleware('permission:hr.documents.manage');

    Route::get('/hr-profiles/{id}/leave', [HrProfileController::class, 'leave'])->middleware('permission:hr.view');
    Route::post('/hr-profiles/{id}/leave', [HrProfileController::class, 'storeLeave'])->middleware('permission:hr.leave.manage');

    Route::put('/hr-leave-records/{id}/approve', [HrProfileController::class, 'approveLeave'])->middleware('permission:hr.leave.manage');
    Route::put('/hr-leave-records/{id}/reject', [HrProfileController::class, 'rejectLeave'])->middleware('permission:hr.leave.manage');

    // Salary scales
    Route::get('/salary-grades', [SalaryGradeController::class, 'index'])->middleware('permission:salary_scales.view');
    Route::post('/salary-grades', [SalaryGradeController::class, 'store'])->middleware('permission:salary_scales.create');
    Route::put('/salary-grades/{id}', [SalaryGradeController::class, 'update'])->middleware('permission:salary_scales.update');
    Route::get('/salary-grades/{id}/steps', [SalaryGradeController::class, 'steps'])->middleware('permission:salary_scales.view');
    Route::post('/salary-grades/{id}/steps', [SalaryGradeController::class, 'storeStep'])->middleware('permission:salary_scales.create');

    Route::put('/salary-steps/{id}', [SalaryStepController::class, 'update'])->middleware('permission:salary_scales.update');

    Route::get('/salary-allowances', [SalaryAllowanceController::class, 'index'])->middleware('permission:salary_scales.view');
    Route::post('/salary-allowances', [SalaryAllowanceController::class, 'store'])->middleware('permission:salary_scales.create');
    Route::put('/salary-allowances/{id}', [SalaryAllowanceController::class, 'update'])->middleware('permission:salary_scales.update');

    Route::get('/staff-salary-placements', [StaffSalaryPlacementController::class, 'index'])->middleware('permission:salary_scales.view');
    Route::post('/staff-salary-placements', [StaffSalaryPlacementController::class, 'store'])->middleware('permission:salary_scales.assign');
    Route::put('/staff-salary-placements/{id}', [StaffSalaryPlacementController::class, 'update'])->middleware('permission:salary_scales.assign');

    Route::get('/salary-cost-projection', [SalaryCostProjectionController::class, 'index'])->middleware('permission:salary_scales.view');

    // Budgets
    Route::get('/fiscal-years', [FiscalYearController::class, 'index'])->middleware('permission:budgets.view');
    Route::post('/fiscal-years', [FiscalYearController::class, 'store'])->middleware('permission:budgets.create');

    Route::get('/budget-categories', [BudgetCategoryController::class, 'index'])->middleware('permission:budgets.view');
    Route::post('/budget-categories', [BudgetCategoryController::class, 'store'])->middleware('permission:budgets.create');

    Route::get('/budgets', [BudgetController::class, 'index'])->middleware('permission:budgets.view');
    Route::get('/budgets/export', [BudgetController::class, 'export'])->middleware('permission:budgets.export');
    Route::post('/budgets', [BudgetController::class, 'store'])->middleware('permission:budgets.create');
    Route::get('/budgets/{id}', [BudgetController::class, 'show'])->middleware('permission:budgets.view');
    Route::put('/budgets/{id}', [BudgetController::class, 'update'])->middleware('permission:budgets.update');

    Route::post('/budgets/{id}/lines', [BudgetController::class, 'storeLine'])->middleware('permission:budgets.update');
    Route::put('/budget-lines/{id}', [BudgetLineController::class, 'update'])->middleware('permission:budgets.update');

    Route::post('/budgets/{id}/submit', [BudgetController::class, 'submit'])->middleware('permission:budgets.submit');
    Route::post('/budgets/{id}/review', [BudgetController::class, 'review'])->middleware('permission:budgets.review');
    Route::post('/budgets/{id}/approve', [BudgetController::class, 'approve'])->middleware('permission:budgets.approve');
    Route::post('/budgets/{id}/reject', [BudgetController::class, 'reject'])->middleware('permission:budgets.reject');
    Route::post('/budgets/{id}/close', [BudgetController::class, 'close'])->middleware('permission:budgets.close');

    Route::get('/budget-lines/{id}/expenditures', [BudgetExpenditureController::class, 'index'])->middleware('permission:budgets.view');
    Route::post('/budget-lines/{id}/expenditures', [BudgetExpenditureController::class, 'store'])->middleware('permission:budgets.update');
    Route::put('/budget-expenditures/{id}/verify', [BudgetExpenditureController::class, 'verify'])->middleware('permission:budgets.approve');

    Route::get('/budget-reports/variance', [BudgetReportController::class, 'variance'])->middleware('permission:budgets.view');
    Route::get('/budget-reports/department-summary', [BudgetReportController::class, 'departmentSummary'])->middleware('permission:budgets.view');

    // Proposals
    Route::get('/proposals', [ProposalController::class, 'index'])->middleware('permission:proposals.view');
    Route::get('/proposals/export', [ProposalController::class, 'export'])->middleware('permission:proposals.export');
    Route::post('/proposals', [ProposalController::class, 'store'])->middleware('permission:proposals.create');
    Route::get('/proposals/{id}', [ProposalController::class, 'show'])->middleware('permission:proposals.view');
    Route::put('/proposals/{id}', [ProposalController::class, 'update'])->middleware('permission:proposals.update');
    Route::delete('/proposals/{id}', [ProposalController::class, 'destroy'])->middleware('permission:proposals.update');

    Route::post('/proposals/{id}/activities', [ProposalController::class, 'storeActivity'])->middleware('permission:proposals.update');
    Route::put('/proposal-activities/{id}', [ProposalController::class, 'updateActivity'])->middleware('permission:proposals.update');

    Route::post('/proposals/{id}/kpis', [ProposalController::class, 'storeKpi'])->middleware('permission:proposals.update');
    Route::put('/proposal-kpis/{id}', [ProposalController::class, 'updateKpi'])->middleware('permission:proposals.update');

    Route::get('/proposals/{id}/attachments', [ProposalController::class, 'attachments'])->middleware('permission:proposals.view');
    Route::post('/proposals/{id}/attachments', [ProposalController::class, 'storeAttachment'])->middleware('permission:proposals.update');
    Route::get('/proposals/{proposalId}/attachments/{attachmentId}/download', [ProposalController::class, 'downloadAttachment'])->middleware('permission:proposals.view');

    Route::post('/proposals/{id}/submit', [ProposalController::class, 'submit'])->middleware('permission:proposals.submit');
    Route::post('/proposals/{id}/review', [ProposalController::class, 'review'])->middleware('permission:proposals.review');
    Route::post('/proposals/{id}/approve', [ProposalController::class, 'approve'])->middleware('permission:proposals.approve');
    Route::post('/proposals/{id}/reject', [ProposalController::class, 'reject'])->middleware('permission:proposals.reject');
    Route::post('/proposals/{id}/publish', [ProposalController::class, 'publish'])->middleware('permission:proposals.publish');
    Route::post('/proposals/{id}/close', [ProposalController::class, 'close'])->middleware('permission:proposals.update');

    // Registrations
    Route::get('/registrations', [RegistrationController::class, 'index']);
    Route::post('/registrations', [RegistrationController::class, 'store']);
    Route::get('/registrations/{id}', [RegistrationController::class, 'show']);
    Route::post('/registrations/{id}/approve', [RegistrationController::class, 'approve'])->middleware('permission:registrations.approve');
    Route::post('/registrations/{id}/reject', [RegistrationController::class, 'reject'])->middleware('permission:registrations.reject');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::post('/payments/{id}/verify-cash', [PaymentController::class, 'verifyCashPayment'])->middleware('permission:payments.verify');
    Route::get('/registrations/{registrationId}/payments', [PaymentController::class, 'getPaymentsByRegistration']);

    // Exam Results
    Route::get('/exam-results', [ExamResultController::class, 'index']);
    Route::post('/exam-results', [ExamResultController::class, 'store']);
    Route::get('/exam-results/{id}', [ExamResultController::class, 'show']);
    Route::put('/exam-results/{id}', [ExamResultController::class, 'update']);
    Route::delete('/exam-results/{id}', [ExamResultController::class, 'destroy']);
    Route::post('/exam-results/{id}/submit', [ExamResultController::class, 'submit']);
    Route::post('/exam-results/{id}/publish', [ExamResultController::class, 'publish']);
    Route::post('/exam-results/{id}/reject', [ExamResultController::class, 'reject']);
    Route::get('/student-results', [ExamResultController::class, 'getStudentResults']);
    Route::get('/students/{studentId}/results', [ExamResultController::class, 'getStudentResults']);
    Route::get('/course-offerings/{courseOfferingId}/results', [ExamResultController::class, 'getCourseResults']);

    // Courses (Admin only for create/update/delete)
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::post('/courses', [CourseController::class, 'store'])->middleware('permission:courses.create');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->middleware('permission:courses.update');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->middleware('permission:courses.delete');

    // Instructor Assignments
    Route::get('/courses/{courseId}/instructors', [CourseController::class, 'getInstructorAssignments']);
    Route::post('/courses/{courseId}/assign-instructor', [CourseController::class, 'assignInstructor'])->middleware('permission:courses.assign_instructor');
    Route::post('/courses/{courseId}/remove-instructor/{assignmentId}', [CourseController::class, 'removeInstructor'])->middleware('permission:courses.assign_instructor');
    Route::put('/instructor-assignments/{assignmentId}/status', [CourseController::class, 'updateAssignmentStatus']);
    Route::get('/instructors', [CourseController::class, 'getInstructors']);
    Route::get('/instructor/courses', [CourseController::class, 'getMyCourses']);
});
