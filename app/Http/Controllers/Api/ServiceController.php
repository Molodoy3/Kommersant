<?php

namespace App\Http\Controllers\Api;

use App\Data\ServiceData;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Service::query()->with('category')->get());
    }
    public function get($id):JsonResponse
    {
        $Service = Service::query()->findOrFail($id);
        if ($Service)
            return response()->json($Service);
        return response()->json(['error' => 'not found'], 404);
    }
    public function create(Request $request): JsonResponse
    {
        ServiceData::validate($request->all());
        $category = Category::query()->findOrFail($request->get('category_id'));
        if ($category) {
            Service::query()->create($request->all());
            return response()->json(['message' => 'Service created successfully.']);
        }
        return response()->json(['error' => 'bad request'], 403);
    }
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->input();
        ServiceData::validate($data);
        $Service = Service::query()->findOrFail($id);
        if ($Service) {
            $Service->update($data);
            return response()->json(['message' => 'Service updated successfully.']);
        }
        return response()->json(['message' => 'Service not found.'], 400);
    }
    public function delete($id): JsonResponse{
        $Service = Service::query()->findOrFail($id);
        if ($Service) {
            $Service->delete();
            return response()->json(['message' => 'Service deleted successfully.']);
        } else {
            return response()->json(['error' => 'not found'], 404);
        }

    }
}
