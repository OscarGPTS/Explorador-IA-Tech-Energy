<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecommendationAdminController extends Controller
{
    /**
     * Reglas de validación compartidas entre store y update.
     */
    private function rules(bool $isUpdate = false): array
    {
        return [
            'title'                  => ['required', 'string', 'max:255'],
            'description'            => ['nullable', 'string'],
            'content'                => ['nullable', 'string'],
            'recommendation_type_id' => ['required', 'exists:recommendations_type,id'],
            'sub_area'               => ['nullable', 'string', 'max:255'],
            'external_link'          => ['nullable', 'url', 'max:255'],
            'image_url'              => ['nullable', 'url', 'max:255'],
            'image_file'             => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $recommendation = new Recommendation();
        $this->fill($recommendation, $request, $data);
        $recommendation->is_scraped = false;
        $recommendation->save();

        return back()->with('status', 'Recomendación creada correctamente.');
    }

    public function update(Request $request, Recommendation $recommendation)
    {
        $data = $request->validate($this->rules(true));

        $this->fill($recommendation, $request, $data);
        $recommendation->save();

        return back()->with('status', 'Recomendación actualizada correctamente.');
    }

    public function destroy(Recommendation $recommendation)
    {
        // Borra la imagen local si existe.
        if ($recommendation->image && Storage::disk('public')->exists('recommendations/' . $recommendation->image)) {
            Storage::disk('public')->delete('recommendations/' . $recommendation->image);
        }

        $recommendation->delete();

        return back()->with('status', 'Recomendación eliminada correctamente.');
    }

    /**
     * Asigna los campos al modelo y procesa la imagen subida.
     */
    private function fill(Recommendation $recommendation, Request $request, array $data): void
    {
        $recommendation->fill([
            'title'                  => $data['title'],
            'description'            => $data['description'] ?? null,
            'content'                => $data['content'] ?? null,
            'recommendation_type_id' => $data['recommendation_type_id'],
            'sub_area'               => $data['sub_area'] ?? null,
            'external_link'          => $data['external_link'] ?? null,
            'image_url'              => $data['image_url'] ?? null,
        ]);

        if ($request->hasFile('image_file')) {
            // Guarda el archivo en storage/app/public/recommendations
            $file = $request->file('image_file');
            $name = uniqid('rec_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('recommendations', $name, 'public');

            // Limpia la imagen anterior si la había.
            if ($recommendation->image && Storage::disk('public')->exists('recommendations/' . $recommendation->image)) {
                Storage::disk('public')->delete('recommendations/' . $recommendation->image);
            }
            $recommendation->image = $name;
        }
    }
}
