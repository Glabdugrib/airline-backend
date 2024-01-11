<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexFlightRequest;
use App\Http\Requests\StoreFlightRequest;
use App\Http\Requests\UpdateFlightRequest;
use App\Models\Flight;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class FlightController extends Controller
{
    /**
     * Display a listing of flights based on specified filters, sorting, and pagination.
     */
    public function index(IndexFlightRequest $request): JsonResponse
    {
        try {
            $request->validated();

            $query = Flight::query();

            // Filter by departure airport
            if ($request->filled('departure_airport_id')) {
                $query->where('departure_airport_id', $request->input('departure_airport_id'));
            }

            // Filter by arrival airport
            if ($request->filled('arrival_airport_id')) {
                $query->where('arrival_airport_id', $request->input('arrival_airport_id'));
            }

            // Filter by departure time
            if ($request->filled('departure_at_date')) {
                $requestedDate = Carbon::parse($request->input('departure_at_date'));
                $query->whereDate('departure_at', '=', $requestedDate->toDateString());

                // Check if departure_at hours are in the specified range
                if ($request->filled('departure_at_from') && $request->filled('departure_at_to')) {
                    $minHour = $request->input('departure_at_from');
                    $fromTime = Carbon::parse($requestedDate)->addHours($minHour);

                    $maxHour = $request->input('departure_at_to');
                    $toTime = Carbon::parse($requestedDate)->addHours($maxHour);

                    $query->whereBetween('departure_at', [$fromTime, $toTime]);
                }
            }

            // Filter by arrival time
            if ($request->filled('arrival_at_date')) {
                $requestedDate = Carbon::parse($request->input('arrival_at_date'));
                $query->whereDate('arrival_at', '=', $requestedDate->toDateString());

                // Check if arrival_at hours are in the specified range
                if ($request->filled('arrival_at_from') && $request->filled('arrival_at_to')) {
                    $minHour = $request->input('arrival_at_from');
                    $fromTime = Carbon::parse($requestedDate)->addHours($minHour);

                    $maxHour = $request->input('arrival_at_to');
                    $toTime = Carbon::parse($requestedDate)->addHours($maxHour);

                    $query->whereBetween('arrival_at', [$fromTime, $toTime]);
                }
            }

            // Filter by price range
            if ($request->filled('price_from')) {
                $query->where('price', '>=', $request->input('price_from'));
            }

            if ($request->filled('price_to')) {
                $query->where('price', '<=', $request->input('price_to'));
            }

            // Filter by stopovers range
            if ($request->filled('stopovers_from')) {
                $query->where('stopovers', '>=', $request->input('stopovers_from'));
            }

            if ($request->filled('stopovers_to')) {
                $query->where('stopovers', '<=', $request->input('stopovers_to'));
            }

            // Sorting
            if ($request->filled('sort_by')) {
                $sortParam = $request->input('sort_by');
                $sortFields = explode(',', $sortParam);

                foreach ($sortFields as $sortField) {
                    $field = ltrim($sortField, '+-');
                    $sortFieldFirstChar = substr($sortField, 0, 1);
                    switch ($sortFieldFirstChar) {
                        case '-':
                            $direction = 'DESC';
                            break;
                        case '+':
                        default:
                            $direction = 'ASC';
                    }

                    $query->orderBy($field, $direction);
                }
            }

            // Pagination
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);

            $flights = $query->paginate($limit, ['*'], 'page', $page);

            return response()->json($flights);
        } catch (ValidationException $e) {
            // Validation failed
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (QueryException $e) {
            // Database error
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Other unexpected errors
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle validation and creation of a new flight based on the provided request data.
     */
    public function store(StoreFlightRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $flight = Flight::create($validatedData);

            return response()->json($flight, 201);
        } catch (ValidationException $e) {
            // Validation failed
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (QueryException $e) {
            // Database error
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Other unexpected errors
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified flight using route parameter binding.
     */
    public function show(Flight $flight): JsonResponse
    {
        try {
            // Automatically retrieve the flight based on the route parameter binding
            return response()->json($flight);
        } catch (\Exception $e) {
            // Other unexpected errors
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle the validation and update of the specified flight based on the provided request data.
     */
    public function update(UpdateFlightRequest $request, Flight $flight): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $flight->update($validatedData);

            return response()->json($flight, 200);
        } catch (ValidationException $e) {
            // Validation failed
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (QueryException $e) {
            // Database error (e.g., unique constraint violation)
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Other unexpected errors
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handles the deletion of the specified flight.
     */
    public function destroy(Flight $flight): JsonResponse
    {
        try {
            $flight->delete();

            return response()->json([], 204);
        } catch (QueryException $e) {
            // Database error
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Other unexpected errors
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }
}
