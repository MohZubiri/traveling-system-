@extends('layouts.admin')

@section('content')
<div class="container-fluid" x-data="templatesManager">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">قوالب الصفحات</h2>
        <button class="btn btn-primary" @click="showCreateModal">
            <i class="fas fa-plus"></i> إنشاء قالب جديد
        </button>
    </div>

    <div class="row">
        <!-- Default Templates -->
        <div class="col-12 mb-4">
            <h3 class="mb-3">القوالب الافتراضية</h3>
            <div class="row">
                <template x-for="template in defaultTemplates" :key="template.name">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title" x-text="template.name"></h5>
                                <p class="card-text" x-text="template.description"></p>
                                <button class="btn btn-primary" @click="useTemplate(template)">
                                    استخدام القالب
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Custom Templates -->
        <div class="col-12">
            <h3 class="mb-3">القوالب المخصصة</h3>
            <div class="row">
                <template x-for="template in customTemplates" :key="template.id">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img :src="template.thumbnail_url" class="card-img-top" alt="Template thumbnail"
                                 onerror="this.src='/images/default-template.jpg'">
                            <div class="card-body">
                                <h5 class="card-title" x-text="template.name"></h5>
                                <p class="card-text" x-text="template.description"></p>
                                <div class="btn-group">
                                    <button class="btn btn-primary" @click="useTemplate(template)">
                                        استخدام القالب
                                    </button>
                                    <button class="btn btn-secondary" @click="editTemplate(template)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger" @click="deleteTemplate(template)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Template Modal -->
    <div class="modal fade" id="templateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" x-text="editingTemplate ? 'تعديل القالب' : 'إنشاء قالب جديد'"></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>اسم القالب</label>
                        <input type="text" class="form-control" x-model="currentTemplate.name">
                    </div>

                    <div class="form-group">
                        <label>وصف القالب</label>
                        <textarea class="form-control" x-model="currentTemplate.description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>صورة مصغرة</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" @change="handleThumbnailUpload" accept="image/*">
                            <label class="custom-file-label">اختر صورة</label>
                        </div>
                        <div x-show="currentTemplate.thumbnail_url" class="mt-2">
                            <img :src="currentTemplate.thumbnail_url" class="img-thumbnail" style="max-height: 200px">
                        </div>
                    </div>

                    <div class="sections-editor">
                        <h6 class="mb-3">أقسام القالب</h6>
                        <template x-for="(section, index) in currentTemplate.sections" :key="section.id">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">قسم #<span x-text="index + 1"></span></h6>
                                        <div class="btn-group">
                                            <button @click="moveSection(index, -1)" 
                                                    class="btn btn-sm btn-light"
                                                    :disabled="index === 0">
                                                <i class="fas fa-arrow-up"></i>
                                            </button>
                                            <button @click="moveSection(index, 1)" 
                                                    class="btn btn-sm btn-light"
                                                    :disabled="index === currentTemplate.sections.length - 1">
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                            <button @click="removeSection(index)" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>نوع القسم</label>
                                        <select class="form-control" x-model="section.type">
                                            <option value="hero">قسم رئيسي (Hero)</option>
                                            <option value="content">محتوى</option>
                                            <option value="features">مميزات</option>
                                            <option value="gallery">معرض صور</option>
                                            <option value="stats">إحصائيات</option>
                                            <option value="timeline">جدول زمني</option>
                                            <option value="faq">أسئلة شائعة</option>
                                            <option value="cta">دعوة للعمل</option>
                                        </select>
                                    </div>

                                    <component :is="'section-editor-' + section.type"
                                             :section="section"
                                             @update="updateSection"></component>
                                </div>
                            </div>
                        </template>

                        <button @click="addSection" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-plus"></i> إضافة قسم
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" @click="saveTemplate">حفظ</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('templatesManager', () => ({
        defaultTemplates: @json(\App\Models\PageTemplate::getDefaultTemplates()),
        customTemplates: @json($templates ?? []),
        currentTemplate: null,
        editingTemplate: false,

        showCreateModal() {
            this.currentTemplate = {
                name: '',
                description: '',
                thumbnail: null,
                thumbnail_url: null,
                sections: []
            };
            this.editingTemplate = false;
            $('#templateModal').modal('show');
        },

        editTemplate(template) {
            this.currentTemplate = { ...template };
            this.editingTemplate = true;
            $('#templateModal').modal('show');
        },

        async deleteTemplate(template) {
            if (!confirm('هل أنت متأكد من حذف هذا القالب؟')) return;

            try {
                const response = await fetch(`/admin/page-templates/${template.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    this.customTemplates = this.customTemplates.filter(t => t.id !== template.id);
                }
            } catch (error) {
                console.error('Error deleting template:', error);
                alert('حدث خطأ أثناء حذف القالب');
            }
        },

        addSection() {
            this.currentTemplate.sections.push({
                id: Date.now(),
                type: 'content',
                title: '',
                content: ''
            });
        },

        removeSection(index) {
            this.currentTemplate.sections.splice(index, 1);
        },

        moveSection(index, direction) {
            const newIndex = index + direction;
            if (newIndex >= 0 && newIndex < this.currentTemplate.sections.length) {
                const sections = [...this.currentTemplate.sections];
                const temp = sections[index];
                sections[index] = sections[newIndex];
                sections[newIndex] = temp;
                this.currentTemplate.sections = sections;
            }
        },

        updateSection(section, data) {
            const index = this.currentTemplate.sections.findIndex(s => s.id === section.id);
            if (index !== -1) {
                this.currentTemplate.sections[index] = { ...section, ...data };
            }
        },

        async handleThumbnailUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('thumbnail', file);

            try {
                const response = await fetch('/admin/upload-thumbnail', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.currentTemplate.thumbnail = data.path;
                    this.currentTemplate.thumbnail_url = data.url;
                }
            } catch (error) {
                console.error('Error uploading thumbnail:', error);
                alert('حدث خطأ أثناء رفع الصورة المصغرة');
            }
        },

        async saveTemplate() {
            try {
                const url = this.editingTemplate 
                    ? `/admin/page-templates/${this.currentTemplate.id}`
                    : '/admin/page-templates';
                
                const method = this.editingTemplate ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.currentTemplate)
                });

                if (response.ok) {
                    const savedTemplate = await response.json();
                    
                    if (this.editingTemplate) {
                        const index = this.customTemplates.findIndex(t => t.id === savedTemplate.id);
                        if (index !== -1) {
                            this.customTemplates[index] = savedTemplate;
                        }
                    } else {
                        this.customTemplates.push(savedTemplate);
                    }

                    $('#templateModal').modal('hide');
                }
            } catch (error) {
                console.error('Error saving template:', error);
                alert('حدث خطأ أثناء حفظ القالب');
            }
        },

        useTemplate(template) {
            window.location.href = `/admin/pages/create?template=${template.id || 'default/' + template.name}`;
        }
    }));
});
</script>
@endpush
@endsection
