@extends('layouts.admin')

@section('content')
<div class="container-fluid" x-data="pageBuilder">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ isset($page) ? 'تعديل صفحة: ' . $page->title : 'إنشاء صفحة جديدة' }}</h2>
        <div>
            <button @click="savePage" class="btn btn-primary">
                <i class="fas fa-save"></i> حفظ
            </button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> إلغاء
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Page Settings -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">إعدادات الصفحة</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">عنوان الصفحة</label>
                        <input type="text" 
                               class="form-control" 
                               id="title" 
                               x-model="page.title"
                               @input="generateSlug">
                    </div>

                    <div class="form-group">
                        <label for="slug">الرابط</label>
                        <input type="text" 
                               class="form-control" 
                               id="slug" 
                               x-model="page.slug">
                    </div>

                    <div class="form-group">
                        <label for="meta_description">وصف SEO</label>
                        <textarea class="form-control" 
                                  id="meta_description" 
                                  x-model="page.meta_description"
                                  rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="meta_keywords">كلمات مفتاحية SEO</label>
                        <input type="text" 
                               class="form-control" 
                               id="meta_keywords" 
                               x-model="page.meta_keywords">
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" 
                                   class="custom-control-input" 
                                   id="is_published" 
                                   x-model="page.is_published">
                            <label class="custom-control-label" for="is_published">نشر الصفحة</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">إضافة قسم جديد</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <button @click="addSection('hero')" 
                                class="list-group-item list-group-item-action">
                            <i class="fas fa-image"></i> قسم رئيسي (Hero)
                        </button>
                        <button @click="addSection('content')" 
                                class="list-group-item list-group-item-action">
                            <i class="fas fa-paragraph"></i> محتوى
                        </button>
                        <button @click="addSection('features')" 
                                class="list-group-item list-group-item-action">
                            <i class="fas fa-list"></i> مميزات
                        </button>
                        <button @click="addSection('gallery')" 
                                class="list-group-item list-group-item-action">
                            <i class="fas fa-images"></i> معرض صور
                        </button>
                        <button @click="addSection('stats')" 
                                class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar"></i> إحصائيات
                        </button>
                        <button @click="addSection('timeline')" 
                                class="list-group-item list-group-item-action">
                            <i class="fas fa-stream"></i> جدول زمني
                        </button>
                        <button @click="addSection('faq')" 
                                class="list-group-item list-group-item-action">
                            <i class="fas fa-question-circle"></i> أسئلة شائعة
                        </button>
                        <button @click="addSection('cta')" 
                                class="list-group-item list-group-item-action">
                            <i class="fas fa-bullhorn"></i> دعوة للعمل
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Preview -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">معاينة الصفحة</h5>
                </div>
                <div class="card-body p-0">
                    <div class="page-preview">
                        <template x-for="(section, index) in page.sections" :key="section.id">
                            <div class="section-wrapper" 
                                 :class="{'selected': selectedSection === section}"
                                 @click="selectSection(section)">
                                <div class="section-controls">
                                    <button @click.stop="moveSection(index, -1)" 
                                            class="btn btn-sm btn-light"
                                            :disabled="index === 0">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button @click.stop="moveSection(index, 1)" 
                                            class="btn btn-sm btn-light"
                                            :disabled="index === page.sections.length - 1">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                    <button @click.stop="removeSection(section)" 
                                            class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <div class="section-content">
                                    <component :is="'section-' + section.type"
                                             :section="section"
                                             @update="updateSection"></component>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css" rel="stylesheet">
<style>
.page-preview {
    min-height: 500px;
    background: #f8f9fa;
    padding: 1rem;
}

.section-wrapper {
    position: relative;
    margin-bottom: 1rem;
    border: 2px solid transparent;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.section-wrapper:hover {
    border-color: #e2e8f0;
}

.section-wrapper.selected {
    border-color: #4299e1;
}

.section-controls {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: none;
    gap: 0.5rem;
    z-index: 10;
}

.section-wrapper:hover .section-controls {
    display: flex;
}

.section-content {
    position: relative;
    min-height: 100px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('pageBuilder', () => ({
        page: @json($page ?? [
            'title' => '',
            'slug' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'is_published' => false,
            'sections' => []
        ]),
        selectedSection: null,

        generateSlug() {
            this.page.slug = this.page.title
                .toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        },

        addSection(type) {
            const section = {
                id: Date.now(),
                type,
                title: '',
                subtitle: '',
                content: '',
                background_color: '#ffffff',
                text_color: '#1a1a1a'
            };

            this.page.sections.push(section);
            this.selectSection(section);
        },

        selectSection(section) {
            this.selectedSection = section;
        },

        updateSection(section, data) {
            const index = this.page.sections.findIndex(s => s.id === section.id);
            if (index !== -1) {
                this.page.sections[index] = { ...section, ...data };
            }
        },

        moveSection(index, direction) {
            const newIndex = index + direction;
            if (newIndex >= 0 && newIndex < this.page.sections.length) {
                const sections = [...this.page.sections];
                const temp = sections[index];
                sections[index] = sections[newIndex];
                sections[newIndex] = temp;
                this.page.sections = sections;
            }
        },

        removeSection(section) {
            if (confirm('هل أنت متأكد من حذف هذا القسم؟')) {
                this.page.sections = this.page.sections.filter(s => s.id !== section.id);
                if (this.selectedSection === section) {
                    this.selectedSection = null;
                }
            }
        },

        async savePage() {
            try {
                const response = await fetch('{{ isset($page) ? route("admin.pages.update", $page) : route("admin.pages.store") }}', {
                    method: '{{ isset($page) ? "PUT" : "POST" }}',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.page)
                });

                if (response.ok) {
                    window.location.href = '{{ route("admin.pages.index") }}';
                } else {
                    throw new Error('Failed to save page');
                }
            } catch (error) {
                alert('حدث خطأ أثناء حفظ الصفحة');
                console.error(error);
            }
        }
    }));
});
</script>
@endpush
@endsection
