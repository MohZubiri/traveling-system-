<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageSectionController extends Controller
{
    public function store(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'type' => 'required|string|max:50',
            'order' => 'integer',
            'background_color' => 'nullable|string|max:50',
            'text_color' => 'nullable|string|max:50'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('page-sections', 'public');
        }

        $section = $page->sections()->create($validated);

        return response()->json([
            'message' => 'تم إضافة القسم بنجاح',
            'section' => $section
        ]);
    }

    public function update(Request $request, Page $page, PageSection $section)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'type' => 'required|string|max:50',
            'order' => 'integer',
            'background_color' => 'nullable|string|max:50',
            'text_color' => 'nullable|string|max:50'
        ]);

        if ($request->hasFile('image')) {
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }
            $validated['image'] = $request->file('image')->store('page-sections', 'public');
        }

        $section->update($validated);

        return response()->json([
            'message' => 'تم تحديث القسم بنجاح',
            'section' => $section
        ]);
    }

    public function destroy(Page $page, PageSection $section)
    {
        if ($section->image) {
            Storage::disk('public')->delete($section->image);
        }

        $section->delete();

        return response()->json(['message' => 'تم حذف القسم بنجاح']);
    }

    public function updateOrder(Request $request, Page $page)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:page_sections,id',
            'sections.*.order' => 'required|integer|min:0'
        ]);

        foreach ($validated['sections'] as $sectionData) {
            PageSection::where('id', $sectionData['id'])
                ->where('page_id', $page->id)
                ->update(['order' => $sectionData['order']]);
        }

        return response()->json(['message' => 'تم تحديث الترتيب بنجاح']);
    }
}
