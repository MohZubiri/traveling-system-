<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'price' => 'nullable|numeric|min:0',
            'is_featured' => 'boolean',
            'active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'تم إضافة الخدمة بنجاح');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'price' => 'nullable|numeric|min:0',
            'is_featured' => 'boolean',
            'active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'تم تحديث الخدمة بنجاح');
    }

    public function destroy(Service $service)
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'تم حذف الخدمة بنجاح');
    }

    public function toggleFeatured(Service $service)
    {
        $service->update(['is_featured' => !$service->is_featured]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الخدمة بنجاح',
            'is_featured' => $service->is_featured
        ]);
    }

    public function toggleActive(Service $service)
    {
        $service->update(['active' => !$service->active]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الخدمة بنجاح',
            'active' => $service->active
        ]);
    }
}
