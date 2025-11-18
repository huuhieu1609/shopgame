<template>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold">Quản Lý Sản Phẩm</h2>
            <button class="btn btn-primary" @click="openModal('create')">
                <i class="bx bx-plus me-2"></i>
                Thêm Mới
            </button>
        </div>

        <!-- Search Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tìm Kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bx bx-search"></i>
                            </span>
                            <input 
                                type="text" 
                                class="form-control" 
                                placeholder="Tìm kiếm theo tên, username..."
                                v-model="searchQuery"
                                @keyup.enter="loadData"
                            >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Danh Mục</label>
                        <select class="form-select" v-model="danhMucFilter" @change="loadData">
                            <option value="">Tất cả</option>
                            <option v-for="dm in danhMucs" :key="dm.id" :value="dm.id">{{ dm.ten }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Trạng Thái</label>
                        <select class="form-select" v-model="trangThaiFilter" @change="loadData">
                            <option value="">Tất cả</option>
                            <option value="1">Hoạt động</option>
                            <option value="0">Tạm dừng</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sắp Xếp</label>
                        <select class="form-select" v-model="sortBy" @change="loadData">
                            <option value="id">Theo ID</option>
                            <option value="ten">Theo tên</option>
                            <option value="gia">Theo giá</option>
                            <option value="created_at">Mới nhất</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Hiển Thị</label>
                        <select class="form-select" v-model="perPage" @change="loadData">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Danh Mục</th>
                                <th class="text-center" style="width: 120px;">Giá</th>
                                <th class="text-center" style="width: 100px;">Số Lượng</th>
                                <th class="text-center" style="width: 100px;">Trạng Thái</th>
                                <th class="text-center" style="width: 200px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else-if="sanPhams.length === 0">
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Không có dữ liệu
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="(item, index) in sanPhams" :key="item.id">
                                <td class="text-center fw-semibold">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">{{ item.ten }}</div>
                                    <small class="text-muted">ID: {{ item.id }}</small>
                                </td>
                                <td>{{ item.danh_muc?.ten || 'N/A' }}</td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary">{{ formatCurrency(item.gia) }}</span>
                                </td>
                                <td class="text-center">{{ item.so_luong }}</td>
                                <td class="text-center">
                                    <button type="button"    
                                        class="btn btn-sm" 
                                        :class="item.trang_thai ? 'btn-success' : 'btn-secondary'"
                                    >
                                        {{ item.trang_thai ? 'Hoạt động' : 'Tạm dừng' }}
                                </button>
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
        <div class="modal fade" id="sanPhamModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ modalTitle }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveData">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tên Sản Phẩm <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.ten"
                                        required
                                    >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.username"
                                    >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Danh Mục <span class="text-danger">*</span></label>
                                    <select class="form-select" v-model="formData.danh_muc_id" required>
                                        <option value="">Chọn danh mục</option>
                                        <option v-for="dm in danhMucs" :key="dm.id" :value="dm.id">{{ dm.ten }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Giá <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.gia"
                                        min="0"
                                        step="1000"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Số Lượng <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.so_luong"
                                        min="0"
                                        required
                                    >
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Cờ</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.co"
                                        placeholder="VN"
                                    >
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Check Live</label>
                                    <div class="form-check form-switch mt-3">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            v-model="formData.check_live"
                                        >
                                        <label class="form-check-label">Check Live</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô Tả</label>
                                <textarea 
                                    class="form-control" 
                                    rows="2"
                                    v-model="formData.mo_ta"
                                ></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nội Dung</label>
                                <textarea 
                                    class="form-control" 
                                    rows="3"
                                    v-model="formData.noi_dung"
                                ></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        v-model="formData.trang_thai"
                                    >
                                    <label class="form-check-label">Trạng thái hoạt động</label>
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
                        <p>Bạn có chắc chắn muốn xóa sản phẩm <strong>{{ selectedItem?.ten }}</strong>?</p>
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
import { sanPhamService } from '@/services/sanPhamService';
import { danhMucService } from '@/services/danhMucService';

export default {
    name: 'SanPham',
    data() {
        return {
            sanPhams: [],
            danhMucs: [],
            loading: false,
            saving: false,
            deleting: false,
            searchQuery: '',
            danhMucFilter: '',
            trangThaiFilter: '',
            sortBy: 'id',
            perPage: 10,
            currentPage: 1,
            totalPages: 1,
            modalTitle: 'Thêm Sản Phẩm',
            formData: {
                id: null,
                ten: '',
                username: '',
                danh_muc_id: '',
                mo_ta: '',
                noi_dung: '',
                gia: 0,
                so_luong: 0,
                co: 'VN',
                check_live: false,
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
        this.loadDanhMucs();
        // Sử dụng Bootstrap global từ CDN
        if (window.bootstrap) {
            this.modal = new window.bootstrap.Modal(document.getElementById('sanPhamModal'));
            this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
        } else {
            setTimeout(() => {
                if (window.bootstrap) {
                    this.modal = new window.bootstrap.Modal(document.getElementById('sanPhamModal'));
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
                    danh_muc_id: this.danhMucFilter || undefined,
                    trang_thai: this.trangThaiFilter || undefined,
                    sort_by: this.sortBy,
                    sort_order: 'desc',
                    per_page: this.perPage,
                    page: this.currentPage,
                };
                const response = await sanPhamService.getData(params);
                if (response.data.success) {
                    this.sanPhams = response.data.data.data || [];
                    this.currentPage = response.data.data.current_page || 1;
                    this.totalPages = response.data.data.last_page || 1;
                }
            } catch (error) {
                console.error('Error loading data:', error);
                alert('Có lỗi xảy ra khi tải dữ liệu');
            } finally {
                this.loading = false;
            }
        },
        async loadDanhMucs() {
            try {
                const response = await danhMucService.getDataOpen();
                if (response.data.success) {
                    this.danhMucs = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading danh mucs:', error);
            }
        },
        openModal(mode, item = null) {
            if (mode === 'create') {
                this.modalTitle = 'Thêm Sản Phẩm';
                this.formData = {
                    id: null,
                    ten: '',
                    username: '',
                    danh_muc_id: '',
                    mo_ta: '',
                    noi_dung: '',
                    gia: 0,
                    so_luong: 0,
                    co: 'VN',
                    check_live: false,
                    trang_thai: true,
                };
            } else {
                this.modalTitle = 'Sửa Sản Phẩm';
                this.formData = {
                    id: item.id,
                    ten: item.ten,
                    username: item.username || '',
                    danh_muc_id: item.danh_muc_id,
                    mo_ta: item.mo_ta || '',
                    noi_dung: item.noi_dung || '',
                    gia: item.gia,
                    so_luong: item.so_luong,
                    co: item.co || 'VN',
                    check_live: item.check_live || false,
                    trang_thai: item.trang_thai,
                };
            }
            this.modal.show();
        },
        async saveData() {
            this.saving = true;
            try {
                if (this.formData.id) {
                    await sanPhamService.update(this.formData.id, this.formData);
                } else {
                    await sanPhamService.create(this.formData);
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
                await sanPhamService.delete(this.selectedItem.id);
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
                await sanPhamService.changeTrangThaiBan(item.id);
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
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        },
    },
    watch: {
        searchQuery() {
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
</style>
