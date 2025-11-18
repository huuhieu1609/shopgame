<template>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold">Quản Lý Danh Mục</h2>
            <button class="btn btn-primary" @click="openModal('create')">
                <i class="bx bx-plus me-2"></i>
                Thêm Mới
            </button>
        </div>

        <!-- Search Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tìm Kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bx bx-search"></i>
                            </span>
                            <input 
                                type="text" 
                                class="form-control" 
                                placeholder="Tìm kiếm theo tên danh mục..."
                                v-model="searchQuery"
                                @keyup.enter="loadData"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sắp Xếp</label>
                        <select class="form-select" v-model="sortBy" @change="loadData">
                            <option value="id">Theo ID</option>
                            <option value="ten">Theo tên</option>
                            <option value="created_at">Mới nhất</option>
                            <option value="updated_at">Cập nhật gần nhất</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Hiển Thị</label>
                        <select class="form-select" v-model="perPage" @change="loadData">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Tên Danh Mục</th>
                                <th>Mô Tả</th>
                                <th class="text-center" style="width: 120px;">Trạng Thái</th>
                                <th class="text-center" style="width: 150px;">Ngày Tạo</th>
                                <th class="text-center" style="width: 180px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else-if="danhMucs.length === 0">
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Không có dữ liệu
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="(item, index) in danhMucs" :key="item.id">
                                <td class="text-center fw-semibold">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">{{ item.ten }}</div>
                                    <small class="text-muted">ID: {{ item.id }}</small>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 300px;">
                                        {{ item.mo_ta || 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span 
                                        class="badge" 
                                        :class="item.trang_thai ? 'bg-success' : 'bg-secondary'"
                                    >
                                        {{ item.trang_thai ? 'Hoạt động' : 'Tạm dừng' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <small>{{ formatDate(item.created_at) }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button 
                                            class="btn btn-sm btn-warning" 
                                            title="Sửa"
                                            @click="openModal('edit', item)"
                                        >
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button 
                                            class="btn btn-sm btn-info text-white" 
                                            title="Đổi trạng thái"
                                            @click="toggleStatus(item)"
                                        >
                                            <i class="bx bx-refresh"></i>
                                        </button>
                                        <button 
                                            class="btn btn-sm btn-danger" 
                                            title="Xóa"
                                            @click="confirmDelete(item)"
                                        >
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation" v-if="totalPages > 1">
                    <ul class="pagination justify-content-center mb-0 mt-3">
                        <li class="page-item" :class="{ disabled: currentPage === 1 }">
                            <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">Trước</a>
                        </li>
                        <li 
                            v-for="page in totalPages" 
                            :key="page"
                            class="page-item" 
                            :class="{ active: page === currentPage }"
                        >
                            <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                        </li>
                        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                            <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">Sau</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Modal Thêm/Sửa -->
        <div class="modal fade" id="danhMucModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ modalTitle }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveData">
                            <div class="mb-3">
                                <label class="form-label">Tên Danh Mục <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    v-model="formData.ten"
                                    required
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô Tả</label>
                                <textarea 
                                    class="form-control" 
                                    rows="3"
                                    v-model="formData.mo_ta"
                                ></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="trangThai"
                                        v-model="formData.trang_thai"
                                    >
                                    <label class="form-check-label" for="trangThai">
                                        Trạng thái hoạt động
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary" :disabled="saving">
                                    <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
                                    {{ saving ? 'Đang lưu...' : 'Lưu' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Xác nhận xóa -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa danh mục <strong>{{ selectedItem?.ten }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-danger" @click="deleteData" :disabled="deleting">
                            <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
                            {{ deleting ? 'Đang xóa...' : 'Xóa' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { danhMucService } from '@/services/danhMucService';

export default {
    name: 'DanhMuc',
    data() {
        return {
            danhMucs: [],
            loading: false,
            saving: false,
            deleting: false,
            searchQuery: '',
            sortBy: 'id',
            perPage: 10,
            currentPage: 1,
            totalPages: 1,
            totalItems: 0,
            modalTitle: 'Thêm Danh Mục',
            formData: {
                id: null,
                ten: '',
                mo_ta: '',
                trang_thai: true,
            },
            selectedItem: null,
            modal: null,
            deleteModal: null,
            searchTimeout: null,
        };
    },
    mounted() {
        this.loadData();
        // Sử dụng Bootstrap global từ CDN
        if (window.bootstrap) {
            this.modal = new window.bootstrap.Modal(document.getElementById('danhMucModal'));
            this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
        } else {
            // Fallback nếu bootstrap chưa load
            setTimeout(() => {
                if (window.bootstrap) {
                    this.modal = new window.bootstrap.Modal(document.getElementById('danhMucModal'));
                    this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
                }
            }, 500);
        }
    },
    methods: {
        async loadData() {
            this.loading = true;
            try {
                const params = {
                    search: this.searchQuery,
                    sort_by: this.sortBy,
                    sort_order: 'desc',
                    per_page: this.perPage,
                    page: this.currentPage,
                };
                const response = await danhMucService.getData(params);
                if (response.data.success) {
                    this.danhMucs = response.data.data.data || [];
                    this.currentPage = response.data.data.current_page || 1;
                    this.totalPages = response.data.data.last_page || 1;
                    this.totalItems = response.data.data.total || 0;
                }
            } catch (error) {
                console.error('Error loading data:', error);
                alert('Có lỗi xảy ra khi tải dữ liệu');
            } finally {
                this.loading = false;
            }
        },
        openModal(mode, item = null) {
            if (mode === 'create') {
                this.modalTitle = 'Thêm Danh Mục';
                this.formData = {
                    id: null,
                    ten: '',
                    mo_ta: '',
                    trang_thai: true,
                };
            } else {
                this.modalTitle = 'Sửa Danh Mục';
                this.formData = {
                    id: item.id,
                    ten: item.ten,
                    mo_ta: item.mo_ta || '',
                    trang_thai: item.trang_thai,
                };
            }
            this.modal.show();
        },
        async saveData() {
            this.saving = true;
            try {
                if (this.formData.id) {
                    await danhMucService.update(this.formData.id, this.formData);
                } else {
                    await danhMucService.create(this.formData);
                }
                this.modal.hide();
                this.loadData();
                alert('Lưu thành công!');
            } catch (error) {
                console.error('Error saving data:', error);
                alert(error.response?.data?.message || 'Có lỗi xảy ra khi lưu dữ liệu');
            } finally {
                this.saving = false;
            }
        },
        confirmDelete(item) {
            this.selectedItem = item;
            this.deleteModal.show();
        },
        async deleteData() {
            this.deleting = true;
            try {
                await danhMucService.delete(this.selectedItem.id);
                this.deleteModal.hide();
                this.loadData();
                alert('Xóa thành công!');
            } catch (error) {
                console.error('Error deleting data:', error);
                alert(error.response?.data?.message || 'Có lỗi xảy ra khi xóa dữ liệu');
            } finally {
                this.deleting = false;
                this.selectedItem = null;
            }
        },
        async toggleStatus(item) {
            try {
                await danhMucService.changeStatus(item.id);
                this.loadData();
                alert('Đổi trạng thái thành công!');
            } catch (error) {
                console.error('Error changing status:', error);
                alert(error.response?.data?.message || 'Có lỗi xảy ra');
            }
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.loadData();
            }
        },
        formatDate(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleString('vi-VN');
        },
    },
    watch: {
        searchQuery() {
            // Debounce search
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.currentPage = 1;
                this.loadData();
            }, 500);
        },
    },
};
</script>

<style scoped>
/* Chỉ sử dụng Bootstrap 5 - không có CSS tùy chỉnh */
</style>
