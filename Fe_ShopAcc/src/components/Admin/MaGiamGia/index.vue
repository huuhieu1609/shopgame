<template>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold">Quản Lý Mã Giảm Giá</h2>
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
                                placeholder="Tìm kiếm theo mã..."
                                v-model="searchQuery"
                                @keyup.enter="loadData"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Trạng Thái</label>
                        <select class="form-select" v-model="trangThaiFilter" @change="loadData">
                            <option value="">Tất cả</option>
                            <option value="1">Hoạt động</option>
                            <option value="0">Tạm dừng</option>
                        </select>
                    </div>
                    <div class="col-md-3">
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

        <!-- Discount Codes Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Mã Giảm Giá</th>
                                <th class="text-center" style="width: 120px;">Giảm Giá</th>
                                <th class="text-center" style="width: 120px;">Số Lượng</th>
                                <th class="text-center" style="width: 120px;">Đã Dùng</th>
                                <th class="text-center" style="width: 150px;">Ngày Bắt Đầu</th>
                                <th class="text-center" style="width: 150px;">Ngày Kết Thúc</th>
                                <th class="text-center" style="width: 100px;">Trạng Thái</th>
                                <th class="text-center" style="width: 180px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else-if="maGiamGias.length === 0">
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    Không có dữ liệu
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="(item, index) in maGiamGias" :key="item.id">
                                <td class="text-center fw-semibold">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">{{ item.ma }}</div>
                                    <small class="text-muted">{{ item.mo_ta || '' }}</small>
                                </td>
                                <td class="text-center">
                                    <span v-if="item.phan_tram_giam > 0" class="fw-bold text-primary">
                                        {{ item.phan_tram_giam }}%
                                    </span>
                                    <span v-else-if="item.so_tien_giam > 0" class="fw-bold text-success">
                                        {{ formatCurrency(item.so_tien_giam) }}
                                    </span>
                                    <span v-else class="text-muted">N/A</span>
                                </td>
                                <td class="text-center">{{ item.so_luong }}</td>
                                <td class="text-center">{{ item.da_su_dung }}</td>
                                <td class="text-center">
                                    <small>{{ formatDate(item.ngay_bat_dau) }}</small>
                                </td>
                                <td class="text-center">
                                    <small>{{ formatDate(item.ngay_ket_thuc) }}</small>
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
                                    <div class="btn-group" role="group">
                                        <button 
                                            class="btn btn-sm btn-warning" 
                                            title="Sửa"
                                            @click="openModal('edit', item)"
                                        >
                                            <i class="bx bx-edit"></i>
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
        <div class="modal fade" id="maGiamGiaModal" tabindex="-1" aria-hidden="true">
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
                                    <label class="form-label">Mã Giảm Giá <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.ma"
                                        required
                                    >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số Lượng <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.so_luong"
                                        min="1"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phần Trăm Giảm (%)</label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.phan_tram_giam"
                                        min="0"
                                        max="100"
                                        step="1"
                                    >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số Tiền Giảm (VNĐ)</label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.so_tien_giam"
                                        min="0"
                                        step="1000"
                                    >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                                    <input 
                                        type="datetime-local" 
                                        class="form-control" 
                                        v-model="formData.ngay_bat_dau"
                                        required
                                    >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngày Kết Thúc <span class="text-danger">*</span></label>
                                    <input 
                                        type="datetime-local" 
                                        class="form-control" 
                                        v-model="formData.ngay_ket_thuc"
                                        required
                                    >
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
                        <p>Bạn có chắc chắn muốn xóa mã giảm giá <strong>{{ selectedItem?.ma }}</strong>?</p>
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
import { maGiamGiaService } from '@/services/maGiamGiaService';

export default {
    name: 'MaGiamGia',
    data() {
        return {
            maGiamGias: [],
            loading: false,
            saving: false,
            deleting: false,
            searchQuery: '',
            trangThaiFilter: '',
            perPage: 10,
            currentPage: 1,
            totalPages: 1,
            modalTitle: 'Thêm Mã Giảm Giá',
            formData: {
                id: null,
                ma: '',
                phan_tram_giam: 0,
                so_tien_giam: 0,
                so_luong: 1,
                ngay_bat_dau: '',
                ngay_ket_thuc: '',
                trang_thai: true,
                mo_ta: '',
            },
            selectedItem: null,
            modal: null,
            deleteModal: null,
        };
    },
    mounted() {
        this.loadData();
        // Sử dụng Bootstrap global từ CDN
        if (window.bootstrap) {
            this.modal = new window.bootstrap.Modal(document.getElementById('maGiamGiaModal'));
            this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
        } else {
            setTimeout(() => {
                if (window.bootstrap) {
                    this.modal = new window.bootstrap.Modal(document.getElementById('maGiamGiaModal'));
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
                    trang_thai: this.trangThaiFilter || undefined,
                    per_page: this.perPage,
                    page: this.currentPage,
                };
                const response = await maGiamGiaService.getData(params);
                if (response.data.success) {
                    this.maGiamGias = response.data.data.data || [];
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
        openModal(mode, item = null) {
            if (mode === 'create') {
                this.modalTitle = 'Thêm Mã Giảm Giá';
                const now = new Date();
                const tomorrow = new Date(now);
                tomorrow.setDate(tomorrow.getDate() + 1);
                this.formData = {
                    id: null,
                    ma: '',
                    phan_tram_giam: 0,
                    so_tien_giam: 0,
                    so_luong: 1,
                    ngay_bat_dau: this.formatDateTimeLocal(now),
                    ngay_ket_thuc: this.formatDateTimeLocal(tomorrow),
                    trang_thai: true,
                    mo_ta: '',
                };
            } else {
                this.modalTitle = 'Sửa Mã Giảm Giá';
                this.formData = {
                    id: item.id,
                    ma: item.ma,
                    phan_tram_giam: item.phan_tram_giam || 0,
                    so_tien_giam: item.so_tien_giam || 0,
                    so_luong: item.so_luong,
                    ngay_bat_dau: this.formatDateTimeLocal(new Date(item.ngay_bat_dau)),
                    ngay_ket_thuc: this.formatDateTimeLocal(new Date(item.ngay_ket_thuc)),
                    trang_thai: item.trang_thai,
                    mo_ta: item.mo_ta || '',
                };
            }
            this.modal.show();
        },
        async saveData() {
            this.saving = true;
            try {
                const data = { ...this.formData };
                if (data.id) {
                    await maGiamGiaService.update(data.id, data);
                } else {
                    await maGiamGiaService.create(data);
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
                await maGiamGiaService.delete(this.selectedItem.id);
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
        formatDateTimeLocal(date) {
            if (!date) return '';
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            const hours = String(d.getHours()).padStart(2, '0');
            const minutes = String(d.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
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


        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold">Quản Lý Mã Giảm Giá</h2>
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
                                placeholder="Tìm kiếm theo mã..."
                                v-model="searchQuery"
                                @keyup.enter="loadData"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Trạng Thái</label>
                        <select class="form-select" v-model="trangThaiFilter" @change="loadData">
                            <option value="">Tất cả</option>
                            <option value="1">Hoạt động</option>
                            <option value="0">Tạm dừng</option>
                        </select>
                    </div>
                    <div class="col-md-3">
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

        <!-- Discount Codes Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Mã Giảm Giá</th>
                                <th class="text-center" style="width: 120px;">Giảm Giá</th>
                                <th class="text-center" style="width: 120px;">Số Lượng</th>
                                <th class="text-center" style="width: 120px;">Đã Dùng</th>
                                <th class="text-center" style="width: 150px;">Ngày Bắt Đầu</th>
                                <th class="text-center" style="width: 150px;">Ngày Kết Thúc</th>
                                <th class="text-center" style="width: 100px;">Trạng Thái</th>
                                <th class="text-center" style="width: 180px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else-if="maGiamGias.length === 0">
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    Không có dữ liệu
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="(item, index) in maGiamGias" :key="item.id">
                                <td class="text-center fw-semibold">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">{{ item.ma }}</div>
                                    <small class="text-muted">{{ item.mo_ta || '' }}</small>
                                </td>
                                <td class="text-center">
                                    <span v-if="item.phan_tram_giam > 0" class="fw-bold text-primary">
                                        {{ item.phan_tram_giam }}%
                                    </span>
                                    <span v-else-if="item.so_tien_giam > 0" class="fw-bold text-success">
                                        {{ formatCurrency(item.so_tien_giam) }}
                                    </span>
                                    <span v-else class="text-muted">N/A</span>
                                </td>
                                <td class="text-center">{{ item.so_luong }}</td>
                                <td class="text-center">{{ item.da_su_dung }}</td>
                                <td class="text-center">
                                    <small>{{ formatDate(item.ngay_bat_dau) }}</small>
                                </td>
                                <td class="text-center">
                                    <small>{{ formatDate(item.ngay_ket_thuc) }}</small>
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
                                    <div class="btn-group" role="group">
                                        <button 
                                            class="btn btn-sm btn-warning" 
                                            title="Sửa"
                                            @click="openModal('edit', item)"
                                        >
                                            <i class="bx bx-edit"></i>
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
        <div class="modal fade" id="maGiamGiaModal" tabindex="-1" aria-hidden="true">
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
                                    <label class="form-label">Mã Giảm Giá <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.ma"
                                        required
                                    >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số Lượng <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.so_luong"
                                        min="1"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phần Trăm Giảm (%)</label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.phan_tram_giam"
                                        min="0"
                                        max="100"
                                        step="1"
                                    >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số Tiền Giảm (VNĐ)</label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.so_tien_giam"
                                        min="0"
                                        step="1000"
                                    >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                                    <input 
                                        type="datetime-local" 
                                        class="form-control" 
                                        v-model="formData.ngay_bat_dau"
                                        required
                                    >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngày Kết Thúc <span class="text-danger">*</span></label>
                                    <input 
                                        type="datetime-local" 
                                        class="form-control" 
                                        v-model="formData.ngay_ket_thuc"
                                        required
                                    >
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
                        <p>Bạn có chắc chắn muốn xóa mã giảm giá <strong>{{ selectedItem?.ma }}</strong>?</p>
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
import { maGiamGiaService } from '@/services/maGiamGiaService';

export default {
    name: 'MaGiamGia',
    data() {
        return {
            maGiamGias: [],
            loading: false,
            saving: false,
            deleting: false,
            searchQuery: '',
            trangThaiFilter: '',
            perPage: 10,
            currentPage: 1,
            totalPages: 1,
            modalTitle: 'Thêm Mã Giảm Giá',
            formData: {
                id: null,
                ma: '',
                phan_tram_giam: 0,
                so_tien_giam: 0,
                so_luong: 1,
                ngay_bat_dau: '',
                ngay_ket_thuc: '',
                trang_thai: true,
                mo_ta: '',
            },
            selectedItem: null,
            modal: null,
            deleteModal: null,
        };
    },
    mounted() {
        this.loadData();
        // Sử dụng Bootstrap global từ CDN
        if (window.bootstrap) {
            this.modal = new window.bootstrap.Modal(document.getElementById('maGiamGiaModal'));
            this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
        } else {
            setTimeout(() => {
                if (window.bootstrap) {
                    this.modal = new window.bootstrap.Modal(document.getElementById('maGiamGiaModal'));
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
                    trang_thai: this.trangThaiFilter || undefined,
                    per_page: this.perPage,
                    page: this.currentPage,
                };
                const response = await maGiamGiaService.getData(params);
                if (response.data.success) {
                    this.maGiamGias = response.data.data.data || [];
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
        openModal(mode, item = null) {
            if (mode === 'create') {
                this.modalTitle = 'Thêm Mã Giảm Giá';
                const now = new Date();
                const tomorrow = new Date(now);
                tomorrow.setDate(tomorrow.getDate() + 1);
                this.formData = {
                    id: null,
                    ma: '',
                    phan_tram_giam: 0,
                    so_tien_giam: 0,
                    so_luong: 1,
                    ngay_bat_dau: this.formatDateTimeLocal(now),
                    ngay_ket_thuc: this.formatDateTimeLocal(tomorrow),
                    trang_thai: true,
                    mo_ta: '',
                };
            } else {
                this.modalTitle = 'Sửa Mã Giảm Giá';
                this.formData = {
                    id: item.id,
                    ma: item.ma,
                    phan_tram_giam: item.phan_tram_giam || 0,
                    so_tien_giam: item.so_tien_giam || 0,
                    so_luong: item.so_luong,
                    ngay_bat_dau: this.formatDateTimeLocal(new Date(item.ngay_bat_dau)),
                    ngay_ket_thuc: this.formatDateTimeLocal(new Date(item.ngay_ket_thuc)),
                    trang_thai: item.trang_thai,
                    mo_ta: item.mo_ta || '',
                };
            }
            this.modal.show();
        },
        async saveData() {
            this.saving = true;
            try {
                const data = { ...this.formData };
                if (data.id) {
                    await maGiamGiaService.update(data.id, data);
                } else {
                    await maGiamGiaService.create(data);
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
                await maGiamGiaService.delete(this.selectedItem.id);
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
        formatDateTimeLocal(date) {
            if (!date) return '';
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            const hours = String(d.getHours()).padStart(2, '0');
            const minutes = String(d.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
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
