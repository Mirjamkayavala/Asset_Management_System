<?php

namespace App\Http\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Add custom reporting logic if needed
        });

        $this->renderable(function (Throwable $e, $request) {
            return $this->handleCustomExceptions($e, $request);
        });
    }

    /**
     * Custom exception handler for specific operations.
     */
    protected function handleCustomExceptions(Throwable $e, $request)
    {
        // Handle validation errors
        if ($e instanceof ValidationException) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }

        // Handle not found errors (e.g., ModelNotFoundException, NotFoundHttpException)
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return response()->view('errors.404', ['message' => 'The resource you are looking for could not be found.'], 404);
        }

        // Handle authentication errors
        if ($e instanceof AuthenticationException) {
            return redirect()->guest(route('login'))
                ->with('error', 'You need to log in to access this page.');
        }

        // Handle method not allowed errors
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->view('errors.405', [], 405);
        }

        // Handle database errors
        if ($e instanceof QueryException) {
            return redirect()->back()->with('error', 'A database error occurred. Please try again later.');
        }

        // Handle general exceptions for specific operations
        if ($this->isAssetOperation($request)) {
            return redirect()->route('assets.index')->with('error', 'An error occurred while managing the asset.');
        }

        if ($this->isAssetCategoryOperation($request)) {
            return redirect()->route('asset_categories.index')->with('error', 'An error occurred while managing the asset category.');
        }

        if ($this->isDepartmentOperation($request)) {
            return redirect()->route('departments.index')->with('error', 'An error occurred while managing the department.');
        }

        if ($this->isLocationOperation($request)) {
            return redirect()->route('locations.index')->with('error', 'An error occurred while managing the location.');
        }

        if ($this->isRegionOperation($request)) {
            return redirect()->route('regions.index')->with('error', 'An error occurred while managing the region.');
        }

        if ($this->isUserOperation($request)) {
            return redirect()->route('users.index')->with('error', 'An error occurred while managing the user.');
        }

        if ($this->isInvoiceOperation($request)) {
            return redirect()->route('invoices.index')->with('error', 'An error occurred while managing the invoice.');
        }

        if ($this->isInsuranceOperation($request)) {
            return redirect()->route('insurance.index')->with('error', 'An error occurred while managing the insurance.');
        }

        if ($this->isVendorOperation($request)) {
            return redirect()->route('vendors.index')->with('error', 'An error occurred while managing the vendor.');
        }

        // Handle cases where the user enters something not in the system
        if ($this->isInvalidInputOperation($request)) {
            return redirect()->back()->with('error', 'The data you entered is not valid or does not exist in the system.');
        }

        // Default handling for any other exceptions
        return parent::render($request, $e);
    }

    /**
     * Check if the request is related to Asset operations.
     */
    protected function isAssetOperation($request)
    {
        return $request->is('assets*');
    }

    /**
     * Check if the request is related to Asset Category operations.
     */
    protected function isAssetCategoryOperation($request)
    {
        return $request->is('asset_categories*');
    }

    /**
     * Check if the request is related to Department operations.
     */
    protected function isDepartmentOperation($request)
    {
        return $request->is('departments*');
    }

    /**
     * Check if the request is related to Location operations.
     */
    protected function isLocationOperation($request)
    {
        return $request->is('locations*');
    }

    /**
     * Check if the request is related to Region operations.
     */
    protected function isRegionOperation($request)
    {
        return $request->is('regions*');
    }

    /**
     * Check if the request is related to User operations.
     */
    protected function isUserOperation($request)
    {
        return $request->is('users*');
    }

    /**
     * Check if the request is related to Invoice operations.
     */
    protected function isInvoiceOperation($request)
    {
        return $request->is('invoices*');
    }

    /**
     * Check if the request is related to Insurance operations.
     */
    protected function isInsuranceOperation($request)
    {
        return $request->is('insurance*');
    }

    /**
     * Check if the request is related to Vendor operations.
     */
    protected function isVendorOperation($request)
    {
        return $request->is('vendors*');
    }

    /**
     * Check if the request contains invalid input or something not in the system.
     */
    protected function isInvalidInputOperation($request)
    {
        // You can implement custom logic to check for invalid input.
        // This is a placeholder for checking input validity.

        return false; // Return true if the input is invalid or not found.
    }
}
