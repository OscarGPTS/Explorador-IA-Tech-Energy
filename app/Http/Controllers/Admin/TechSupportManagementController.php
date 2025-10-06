<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechSupportCategory;
use App\Models\TechSupportProblem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TechSupportManagementController extends Controller
{
    public function index()
    {
        $categories = TechSupportCategory::with('allProblems')->ordered()->get();
        return view('admin.tech-support.index', compact('categories'));
    }

    // Categorías
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:tech_support_categories',
            'display_name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0'
        ]);

        TechSupportCategory::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada exitosamente'
        ]);
    }

    public function updateCategory(Request $request, TechSupportCategory $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('tech_support_categories')->ignore($category->id)],
            'display_name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada exitosamente'
        ]);
    }

    public function destroyCategory(TechSupportCategory $category)
    {
        if ($category->allProblems()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar una categoría con problemas asociados'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada exitosamente'
        ]);
    }

    // Problemas
    public function storeProblem(Request $request)
    {
        $request->validate([
            'tech_support_category_id' => 'required|exists:tech_support_categories,id',
            'problem_key' => 'required|string|max:100|unique:tech_support_problems',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'solution_title' => 'required|string|max:255',
            'solution_content' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'estimated_time' => 'nullable|string|max:50',
            'sort_order' => 'integer|min:0',
            'keywords' => 'nullable|array'
        ]);

        TechSupportProblem::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Problema creado exitosamente'
        ]);
    }

    public function updateProblem(Request $request, TechSupportProblem $problem)
    {
        $request->validate([
            'tech_support_category_id' => 'required|exists:tech_support_categories,id',
            'problem_key' => ['required', 'string', 'max:100', Rule::unique('tech_support_problems')->ignore($problem->id)],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'solution_title' => 'required|string|max:255',
            'solution_content' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'estimated_time' => 'nullable|string|max:50',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'keywords' => 'nullable|array'
        ]);

        $problem->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Problema actualizado exitosamente'
        ]);
    }

    public function destroyProblem(TechSupportProblem $problem)
    {
        $problem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Problema eliminado exitosamente'
        ]);
    }

    public function toggleActive(Request $request)
    {
        $request->validate([
            'type' => 'required|in:category,problem',
            'id' => 'required|integer',
            'is_active' => 'required|boolean'
        ]);

        if ($request->type === 'category') {
            $item = TechSupportCategory::findOrFail($request->id);
        } else {
            $item = TechSupportProblem::findOrFail($request->id);
        }

        $item->update(['is_active' => $request->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado exitosamente'
        ]);
    }
}
