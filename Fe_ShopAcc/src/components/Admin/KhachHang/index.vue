<template>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold">Quản Lý Khách Hàng</h2>
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
                                placeholder="Tìm kiếm theo tên, email, số điện thoại..."
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
                            <option value="0">Tạm khóa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sắp Xếp</label>
                        <select class="form-select" v-model="sortBy" @change="loadData">
                            <option value="id">Theo ID</option>
                            <option value="ten">Theo tên</option>
                            <option value="so_du">Số dư</option>
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

        <!-- Customers Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th style="width: 150px;">Khách Hàng</th>
                                <th style="width: 120px;">Tài Khoản</th>
                                <th style="width: 200px;">Thông Tin Liên Hệ</th>
                                <th class="text-center" style="width: 120px;">Số Dư</th>
                                <th class="text-center" style="width: 120px;">Tổng Chi</th>
                                <th class="text-center" style="width: 100px;">Trạng Thái</th>
                                <th class="text-center" style="width: 150px;">Ngày Đăng Ký</th>
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
                        <tbody v-else-if="khachHangs.length === 0">
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    Không có dữ liệu
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="(item, index) in khachHangs" :key="item.id">
                                <td class="text-center fw-semibold">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">{{ item.ten }}</div>
                                    <small class="text-muted">ID: {{ item.id }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ item.tai_khoan }}</div>
                                </td>
                                <td>
                                    <div>
                                        <i class="bx bx-envelope me-1 text-muted"></i>
                                        <small>{{ item.email }}</small>
                                    </div>
                                    <div v-if="item.sdt">
                                        <i class="bx bx-phone me-1 text-muted"></i>
                                        <small>{{ item.sdt }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-success">{{ formatCurrency(item.so_du) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary">{{ formatCurrency(item.tong_chi) }}</span>
                                </td>
                                <td class="text-center">
                                    <span 
                                        class="badge" 
                                        :class="item.trang_thai ? 'bg-success' : 'bg-secondary'"
                                    >
                                        {{ item.trang_thai ? 'Hoạt động' : 'Tạm khóa' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <small>{{ formatDate(item.created_at) }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button 
                                            class="btn btn-sm btn-info text-white" 
                                            title="Xem chi tiết"
                                            @click="viewDetail(item)"
                                        >
                                            <i class="bx bx-show"></i>
                                        </button>
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
        <div class="modal fade" id="khachHangModal" tabindex="-1" aria-hidden="true">
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
                                    <label class="form-label">Tên Khách Hàng <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.ten"
                                        required
                                    >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tài Khoản <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.tai_khoan"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        v-model="formData.email"
                                        required
                                    >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số Điện Thoại</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.sdt"
                                    >
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa Chỉ</label>
                                <textarea 
                                    class="form-control" 
                                    rows="2"
                                    v-model="formData.dia_chi"
                                ></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mật Khẩu <span class="text-danger" v-if="!formData.id">*</span></label>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        v-model="formData.password"
                                        :required="!formData.id"
                                    >
                                    <small class="text-muted" v-if="formData.id">Để trống nếu không đổi mật khẩu</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số Dư</label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.so_du"
                                        min="0"
                                        step="1000"
                                    >
                                </div>
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
                        <p>Bạn có chắc chắn muốn xóa khách hàng <strong>{{ selectedItem?.ten }}</strong>?</p>
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
import { khachHangService } from '@/services/khachHangService';

export default {
    name: 'KhachHang',
    data() {
        return {
            khachHangs: [],
            loading: false,
            saving: false,
            deleting: false,
            searchQuery: '',
            trangThaiFilter: '',
            sortBy: 'id',
            perPage: 10,
            currentPage: 1,
            totalPages: 1,
            modalTitle: 'Thêm Khách Hàng',
            formData: {
                id: null,
                ten: '',
                tai_khoan: '',
                email: '',
                password: '',
                sdt: '',
                dia_chi: '',
                so_du: 0,
                tong_chi: 0,
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
            this.modal = new window.bootstrap.Modal(document.getElementById('khachHangModal'));
            this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
        } else {
            setTimeout(() => {
                if (window.bootstrap) {
                    this.modal = new window.bootstrap.Modal(document.getElementById('khachHangModal'));
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
                    sort_by: this.sortBy,
                    sort_order: 'desc',
                    per_page: this.perPage,
                    page: this.currentPage,
                };
                const response = await khachHangService.getData(params);
                if (response.data.success) {
                    this.khachHangs = response.data.data.data || [];
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
                this.modalTitle = 'Thêm Khách Hàng';
                this.formData = {
                    id: null,
                    ten: '',
                    tai_khoan: '',
                    email: '',
                    password: '',
                    sdt: '',
                    dia_chi: '',
                    so_du: 0,
                    tong_chi: 0,
                    trang_thai: true,
                };
            } else {
                this.modalTitle = 'Sửa Khách Hàng';
                this.formData = {
                    id: item.id,
                    ten: item.ten,
                    tai_khoan: item.tai_khoan,
                    email: item.email,
                    password: '',
                    sdt: item.sdt || '',
                    dia_chi: item.dia_chi || '',
                    so_du: item.so_du || 0,
                    tong_chi: item.tong_chi || 0,
                    trang_thai: item.trang_thai,
                };
            }
            this.modal.show();
        },
        async saveData() {
            this.saving = true;
            try {
                const data = { ...this.formData };
                if (!data.password && data.id) {
                    delete data.password;
                }
                if (data.id) {
                    await khachHangService.update(data.id, data);
                } else {
                    await khachHangService.create(data);
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
        viewDetail(item) {
            // TODO: Navigate to detail page or show modal
            alert(`Chi tiết khách hàng: ${item.ten}`);
        },
        confirmDelete(item) {
            this.selectedItem = item;
            this.deleteModal.show();
        },
        async deleteData() {
            this.deleting = true;
            try {
                await khachHangService.delete(this.selectedItem.id);
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