<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = SubCategory::with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $subCategories = $query->where('is_active', true)->get();

        $data = $subCategories->map(function ($subCategory) {
            return [
                'id' => $subCategory->id,
                'category_id' => $subCategory->category_id,
                'category_name' => $subCategory->category->name,
                'name' => $subCategory->name,
                'slug' => $subCategory->slug,
                'image_url' => $subCategory->image_url,
                'is_active' => $subCategory->is_active,
                'created_at' => $subCategory->created_at,
                'updated_at' => $subCategory->updated_at,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Subcategories retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'is_active' => $request->is_active ?? true,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subcategories', 'public');
            $data['image'] = $imagePath;
        }

        $subCategory = SubCategory::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Subcategory created successfully',
            'data' => [
                'id' => $subCategory->id,
                'category_id' => $subCategory->category_id,
                'category_name' => $subCategory->category->name,
                'name' => $subCategory->name,
                'slug' => $subCategory->slug,
                'image_url' => $subCategory->image_url,
                'is_active' => $subCategory->is_active,
                'created_at' => $subCategory->created_at,
            ],
        ], 201);
    }

    public function show($id)
    {
        $subCategory = SubCategory::with('category')->find($id);

        if (!$subCategory) {
            return response()->json([
                'status' => false,
                'message' => 'Subcategory not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Subcategory retrieved successfully',
            'data' => [
                'id' => $subCategory->id,
                'category_id' => $subCategory->category_id,
                'category_name' => $subCategory->category->name,
                'name' => $subCategory->name,
                'slug' => $subCategory->slug,
                'image_url' => $subCategory->image_url,
                'is_active' => $subCategory->is_active,
                'created_at' => $subCategory->created_at,
                'updated_at' => $subCategory->updated_at,
            ],
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::with('category')->find($id);

        if (!$subCategory) {
            return response()->json([
                'status' => false,
                'message' => 'Subcategory not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = [];

        if ($request->has('category_id')) {
            $data['category_id'] = $request->category_id;
        }

        if ($request->has('name')) {
            $data['name'] = $request->name;
        }

        if ($request->has('is_active')) {
            $data['is_active'] = $request->is_active;
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($subCategory->image) {
                Storage::disk('public')->delete($subCategory->image);
            }
            $imagePath = $request->file('image')->store('subcategories', 'public');
            $data['image'] = $imagePath;
        }

        $subCategory->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Subcategory updated successfully',
            'data' => [
                'id' => $subCategory->id,
                'category_id' => $subCategory->category_id,
                'category_name' => $subCategory->category->name,
                'name' => $subCategory->name,
                'slug' => $subCategory->slug,
                'image_url' => $subCategory->image_url,
                'is_active' => $subCategory->is_active,
                'updated_at' => $subCategory->updated_at,
            ],
        ], 200);
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::find($id);

        if (!$subCategory) {
            return response()->json([
                'status' => false,
                'message' => 'Subcategory not found',
            ], 404);
        }

        // Delete image if exists
        if ($subCategory->image) {
            Storage::disk('public')->delete($subCategory->image);
        }

        $subCategory->delete();

        return response()->json([
            'status' => true,
            'message' => 'Subcategory deleted successfully',
        ], 200);
    }
}
