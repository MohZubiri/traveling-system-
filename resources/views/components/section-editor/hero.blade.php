<div class="section-editor" x-data="heroEditor">
    <div class="form-group">
        <label>عنوان القسم</label>
        <input type="text" class="form-control" x-model="section.title" @input="updateSection">
    </div>

    <div class="form-group">
        <label>عنوان فرعي</label>
        <input type="text" class="form-control" x-model="section.subtitle" @input="updateSection">
    </div>

    <div class="form-group">
        <label>محتوى</label>
        <textarea class="form-control" x-model="section.content" @input="updateSection" rows="4"></textarea>
    </div>

    <div class="form-group">
        <label>صورة الخلفية</label>
        <div class="custom-file">
            <input type="file" class="custom-file-input" @change="handleImageUpload" accept="image/*">
            <label class="custom-file-label">اختر صورة</label>
        </div>
        <div x-show="section.image" class="mt-2">
            <img :src="section.image_url" class="img-thumbnail" style="max-height: 100px">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>نص الزر</label>
                <input type="text" class="form-control" x-model="section.button_text" @input="updateSection">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>رابط الزر</label>
                <input type="text" class="form-control" x-model="section.button_url" @input="updateSection">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>لون الخلفية</label>
                <input type="color" class="form-control" x-model="section.background_color" @input="updateSection">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>لون النص</label>
                <input type="color" class="form-control" x-model="section.text_color" @input="updateSection">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('heroEditor', () => ({
        section: @json($section ?? []),

        updateSection() {
            this.$dispatch('section-updated', this.section);
        },

        async handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('/admin/upload-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.section.image = data.path;
                    this.section.image_url = data.url;
                    this.updateSection();
                }
            } catch (error) {
                console.error('Error uploading image:', error);
                alert('حدث خطأ أثناء رفع الصورة');
            }
        }
    }));
});
</script>
@endpush
