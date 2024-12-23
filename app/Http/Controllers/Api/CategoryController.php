<?php

namespace App\Http\Controllers\Api;

use App\Data\CategoryData;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Category::all());
    }
    public function get($id):JsonResponse
    {
        $category = Category::query()->findOrFail($id);
        if ($category)
            return response()->json($category);
        return response()->json(['error' => 'not found'], 404);
    }
    public function create(Request $request): JsonResponse
    {
        CategoryData::validate($request->all());
        Category::query()->create($request->all());
        return response()->json(['message' => 'Category created successfully.']);
    }
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->input();
        CategoryData::validate($data);
        $service = Category::query()->findOrFail($id);
        if ($service) {
            $category = $service->update($data);
            if ($category) {
                $service->update($data);
                return response()->json(['message' => 'Category updated successfully.']);
            }
            return response()->json(['error' => 'bad request'], 403);
        }
        return response()->json(['message' => 'Category not found.'], 400);
    }
    public function delete($id): JsonResponse{
        $service = Category::query()->findOrFail($id);
        if ($service) {
            $service->delete();
            return response()->json(['message' => 'Category deleted successfully.']);
        } else {
            return response()->json(['error' => 'not found'], 404);
        }

    }
}
