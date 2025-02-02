<div class="section-editor" x-data="featuresEditor">
    <div class="form-group">
        <label>عنوان القسم</label>
        <input type="text" class="form-control" x-model="section.title" @input="updateSection">
    </div>

    <div class="form-group">
        <label>عنوان فرعي</label>
        <input type="text" class="form-control" x-model="section.subtitle" @input="updateSection">
    </div>

    <div class="features-list">
        <template x-for="(feature, index) in features" :key="feature.id">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">ميزة #<span x-text="index + 1"></span></h6>
                        <button @click="removeFeature(index)" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <div class="form-group">
                        <label>أيقونة</label>
                        <div class="input-group">
                            <input type="text" class="form-control" x-model="feature.icon" @input="updateFeatures"
                                   placeholder="fas fa-star">
                            <div class="input-group-append">
                                <button @click="openIconPicker(index)" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>عنوان</label>
                        <input type="text" class="form-control" x-model="feature.title" @input="updateFeatures">
                    </div>

                    <div class="form-group">
                        <label>وصف</label>
                        <textarea class="form-control" x-model="feature.description" @input="updateFeatures" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </template>

        <button @click="addFeature" class="btn btn-outline-primary btn-block">
            <i class="fas fa-plus"></i> إضافة ميزة
        </button>
    </div>

    <div class="row mt-4">
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

    <!-- Icon Picker Modal -->
    <div class="modal fade" id="iconPickerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">اختر أيقونة</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="بحث..." x-model="iconSearch" @input="filterIcons">
                    </div>
                    <div class="icons-grid">
                        <template x-for="icon in filteredIcons" :key="icon">
                            <div class="icon-item" @click="selectIcon(icon)">
                                <i :class="icon"></i>
                                <small x-text="icon"></small>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.icons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 1rem;
    max-height: 400px;
    overflow-y: auto;
}

.icon-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
}

.icon-item:hover {
    background-color: #f7fafc;
    border-color: #4299e1;
}

.icon-item i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.icon-item small {
    font-size: 0.75rem;
    text-align: center;
    word-break: break-all;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('featuresEditor', () => ({
        section: @json($section ?? []),
        features: [],
        iconSearch: '',
        selectedFeatureIndex: null,
        icons: [
            'fas fa-star', 'fas fa-heart', 'fas fa-user', 'fas fa-home', 'fas fa-cog',
            'fas fa-envelope', 'fas fa-phone', 'fas fa-map-marker-alt', 'fas fa-clock',
            'fas fa-calendar', 'fas fa-search', 'fas fa-shopping-cart', 'fas fa-check',
            'fas fa-times', 'fas fa-plus', 'fas fa-minus', 'fas fa-edit', 'fas fa-trash',
            // Add more icons as needed
        ],
        filteredIcons: [],

        init() {
            this.features = JSON.parse(this.section.content || '[]');
            this.filteredIcons = this.icons;
        },

        addFeature() {
            this.features.push({
                id: Date.now(),
                icon: 'fas fa-star',
                title: '',
                description: ''
            });
            this.updateFeatures();
        },

        removeFeature(index) {
            this.features.splice(index, 1);
            this.updateFeatures();
        },

        updateFeatures() {
            this.section.content = JSON.stringify(this.features);
            this.updateSection();
        },

        updateSection() {
            this.$dispatch('section-updated', this.section);
        },

        openIconPicker(index) {
            this.selectedFeatureIndex = index;
            $('#iconPickerModal').modal('show');
        },

        selectIcon(icon) {
            if (this.selectedFeatureIndex !== null) {
                this.features[this.selectedFeatureIndex].icon = icon;
                this.updateFeatures();
            }
            $('#iconPickerModal').modal('hide');
        },

        filterIcons() {
            this.filteredIcons = this.icons.filter(icon => 
                icon.toLowerCase().includes(this.iconSearch.toLowerCase())
            );
        }
    }));
});
</script>
@endpush
