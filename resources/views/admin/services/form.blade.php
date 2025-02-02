@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ isset($service) ? 'تعديل خدمة' : 'إضافة خدمة جديدة' }}</h2>
        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @if(isset($service))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="name">اسم الخدمة <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $service->name ?? '') }}" 
                           required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">وصف الخدمة <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="5" 
                              required>{{ old('description', $service->description ?? '') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">صورة الخدمة</label>
                    <input type="file" 
                           class="form-control-file @error('image') is-invalid @enderror" 
                           id="image" 
                           name="image" 
                           accept="image/*">
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if(isset($service) && $service->image)
                    <div class="mt-2">
                        <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="price">السعر</label>
                    <div class="input-group">
                        <input type="number" 
                               class="form-control @error('price') is-invalid @enderror" 
                               id="price" 
                               name="price" 
                               step="0.01" 
                               min="0" 
                               value="{{ old('price', $service->price ?? '') }}">
                        <div class="input-group-append">
                            <span class="input-group-text">ريال</span>
                        </div>
                    </div>
                    @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1" 
                               {{ old('is_featured', $service->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_featured">عرض في الصفحة الرئيسية</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="active" 
                               name="active" 
                               value="1" 
                               {{ old('active', $service->active ?? true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="active">نشط</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#description'), {
        language: 'ar'
    })
    .catch(error => {
        console.error(error);
    });
</script>
@endpush
@endsection
