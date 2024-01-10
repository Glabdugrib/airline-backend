<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Http\Requests\IndexAirportRequest;
use App\Http\Requests\StoreAirportRequest;
use App\Http\Requests\UpdateAirportRequest;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class AirportController extends Controller
{
    /**
     * Display a listing of airports based on specified filters, sorting, and pagination.
     */
    public function index(IndexAirportRequest $request): JsonResponse
    {
      try {
        $request->validated();
        $query = Airport::query();

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('country', $request->input('country'));
        }

        // Sorting
        if ($request->filled('sort_by')) {
          $sortParam = $request->input('sort_by');
          $sortFields = explode(',', $sortParam);

          foreach ($sortFields as $sortField) {
            $field = ltrim($sortField, '+-');;
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

        $airports = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json($airports);
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
     * Handle validation and creation of a new airport based on the provided request data.
     */
    public function store(StoreAirportRequest $request): JsonResponse
    {
      try {
        $validatedData = $request->validated();
        
        $airport = Airport::create($validatedData);

        return response()->json($airport, 201);

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
     * Display the specified airport using route parameter binding.
     */
    public function show(Airport $airport): JsonResponse
    {
      try {
        // Automatically retrieve the airport based on the route parameter binding
        return response()->json($airport);
      } catch (\Exception $e) {
          // Other unexpected errors
          return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
      }
    }

    /**
     * Handle the validation and update of the specified airport based on the provided request data.
     */
    public function update(UpdateAirportRequest $request, Airport $airport): JsonResponse
    {
      try {
        $validatedData = $request->validated();

        $airport->update($validatedData);

        return response()->json($airport, 200);
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
     * Handles the deletion of the specified airport.
     */
    public function destroy(Airport $airport)
    {
      try {
        $airport->delete();

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
