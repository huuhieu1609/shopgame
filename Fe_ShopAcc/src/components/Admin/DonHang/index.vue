<template>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold">Quản Lý Đơn Hàng</h2>
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
                                placeholder="Tìm kiếm theo mã đơn hàng, khách hàng..."
                                v-model="searchQuery"
                                @keyup.enter="loadData"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Trạng Thái</label>
                        <select class="form-select" v-model="trangThaiFilter" @change="loadData">
                            <option value="">Tất cả</option>
                            <option value="cho_xu_ly">Chờ xử lý</option>
                            <option value="dang_xu_ly">Đang xử lý</option>
                            <option value="dang_giao">Đang giao</option>
                            <option value="hoan_thanh">Hoàn thành</option>
                            <option value="da_huy">Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sắp Xếp</label>
                        <select class="form-select" v-model="sortBy" @change="loadData">
                            <option value="id">Theo ID</option>
                            <option value="tong_tien">Theo tổng tiền</option>
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

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Mã Đơn Hàng</th>
                                <th>Khách Hàng</th>
                                <th class="text-center" style="width: 150px;">Tổng Tiền</th>
                                <th class="text-center" style="width: 150px;">Trạng Thái</th>
                                <th class="text-center" style="width: 150px;">Ngày Tạo</th>
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
                        <tbody v-else-if="donHangs.length === 0">
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Không có dữ liệu
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="(item, index) in donHangs" :key="item.id">
                                <td class="text-center fw-semibold">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">#{{ item.id }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ item.khach_hang?.ten || 'N/A' }}</div>
                                    <small class="text-muted">{{ item.khach_hang?.email || '' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary">{{ formatCurrency(item.tong_tien) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge" :class="getStatusClass(item.trang_thai)">
                                        {{ getStatusText(item.trang_thai) }}
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

        <!-- Modal Sửa -->
        <div class="modal fade" id="donHangModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa Đơn Hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveData">
                            <div class="mb-3">
                                <label class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                                <select class="form-select" v-model="formData.trang_thai" required>
                                    <option value="cho_xu_ly">Chờ xử lý</option>
                                    <option value="dang_xu_ly">Đang xử lý</option>
                                    <option value="dang_giao">Đang giao</option>
                                    <option value="hoan_thanh">Hoàn thành</option>
                                    <option value="da_huy">Đã hủy</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi Chú</label>
                                <textarea 
                                    class="form-control" 
                                    rows="3"
                                    v-model="formData.ghi_chu"
                                ></textarea>
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
                        <p>Bạn có chắc chắn muốn xóa đơn hàng <strong>#{{ selectedItem?.id }}</strong>?</p>
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
import { donHangService } from '@/services/donHangService';

export default {
    name: 'DonHang',
    data() {
        return {
            donHangs: [],
            loading: false,
            saving: false,
            deleting: false,
            searchQuery: '',
            trangThaiFilter: '',
            sortBy: 'id',
            perPage: 10,
            currentPage: 1,
            totalPages: 1,
            formData: {
                id: null,
                trang_thai: 'cho_xu_ly',
                ghi_chu: '',
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
            this.modal = new window.bootstrap.Modal(document.getElementById('donHangModal'));
            this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
        } else {
            setTimeout(() => {
                if (window.bootstrap) {
                    this.modal = new window.bootstrap.Modal(document.getElementById('donHangModal'));
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
                    trang_thai: this.trangThaiFilter || undefined,
                    sort_by: this.sortBy,
                    sort_order: 'desc',
                    per_page: this.perPage,
                    page: this.currentPage,
                };
                const response = await donHangService.getData(params);
                if (response.data.success) {
                    this.donHangs = response.data.data.data || [];
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
            this.formData = {
                id: item.id,
                trang_thai: item.trang_thai,
                ghi_chu: item.ghi_chu || '',
            };
            this.modal.show();
        },
        async saveData() {
            this.saving = true;
            try {
                await donHangService.update(this.formData.id, this.formData);
                this.modal.hide();
                this.loadData();
                alert('Cập nhật thành công!');
            } catch (error) {
                console.error('Error saving data:', error);
                alert(error.response?.data?.message || 'Có lỗi xảy ra khi lưu dữ liệu');
            } finally {
                this.saving = false;
            }
        },
        viewDetail(item) {
            // TODO: Navigate to detail page or show modal
            alert(`Chi tiết đơn hàng #${item.id}`);
        },
        confirmDelete(item) {
            this.selectedItem = item;
            this.deleteModal.show();
        },
        async deleteData() {
            this.deleting = true;
            try {
                await donHangService.delete(this.selectedItem.id);
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
        getStatusClass(status) {
            const classes = {
                'cho_xu_ly': 'bg-warning',
                'dang_xu_ly': 'bg-info',
                'dang_giao': 'bg-primary',
                'hoan_thanh': 'bg-success',
                'da_huy': 'bg-danger',
            };
            return classes[status] || 'bg-secondary';
        },
        getStatusText(status) {
            const texts = {
                'cho_xu_ly': 'Chờ xử lý',
                'dang_xu_ly': 'Đang xử lý',
                'dang_giao': 'Đang giao',
                'hoan_thanh': 'Hoàn thành',
                'da_huy': 'Đã hủy',
            };
            return texts[status] || status;
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
            <h2 class="mb-0 fw-bold">Quản Lý Đơn Hàng</h2>
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
                                placeholder="Tìm kiếm theo mã đơn hàng, khách hàng..."
                                v-model="searchQuery"
                                @keyup.enter="loadData"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Trạng Thái</label>
                        <select class="form-select" v-model="trangThaiFilter" @change="loadData">
                            <option value="">Tất cả</option>
                            <option value="cho_xu_ly">Chờ xử lý</option>
                            <option value="dang_xu_ly">Đang xử lý</option>
                            <option value="dang_giao">Đang giao</option>
                            <option value="hoan_thanh">Hoàn thành</option>
                            <option value="da_huy">Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sắp Xếp</label>
                        <select class="form-select" v-model="sortBy" @change="loadData">
                            <option value="id">Theo ID</option>
                            <option value="tong_tien">Theo tổng tiền</option>
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

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Mã Đơn Hàng</th>
                                <th>Khách Hàng</th>
                                <th class="text-center" style="width: 150px;">Tổng Tiền</th>
                                <th class="text-center" style="width: 150px;">Trạng Thái</th>
                                <th class="text-center" style="width: 150px;">Ngày Tạo</th>
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
                        <tbody v-else-if="donHangs.length === 0">
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Không có dữ liệu
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="(item, index) in donHangs" :key="item.id">
                                <td class="text-center fw-semibold">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">#{{ item.id }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ item.khach_hang?.ten || 'N/A' }}</div>
                                    <small class="text-muted">{{ item.khach_hang?.email || '' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary">{{ formatCurrency(item.tong_tien) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge" :class="getStatusClass(item.trang_thai)">
                                        {{ getStatusText(item.trang_thai) }}
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

        <!-- Modal Sửa -->
        <div class="modal fade" id="donHangModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa Đơn Hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveData">
                            <div class="mb-3">
                                <label class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                                <select class="form-select" v-model="formData.trang_thai" required>
                                    <option value="cho_xu_ly">Chờ xử lý</option>
                                    <option value="dang_xu_ly">Đang xử lý</option>
                                    <option value="dang_giao">Đang giao</option>
                                    <option value="hoan_thanh">Hoàn thành</option>
                                    <option value="da_huy">Đã hủy</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi Chú</label>
                                <textarea 
                                    class="form-control" 
                                    rows="3"
                                    v-model="formData.ghi_chu"
                                ></textarea>
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
                        <p>Bạn có chắc chắn muốn xóa đơn hàng <strong>#{{ selectedItem?.id }}</strong>?</p>
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
import { donHangService } from '@/services/donHangService';

export default {
    name: 'DonHang',
    data() {
        return {
            donHangs: [],
            loading: false,
            saving: false,
            deleting: false,
            searchQuery: '',
            trangThaiFilter: '',
            sortBy: 'id',
            perPage: 10,
            currentPage: 1,
            totalPages: 1,
            formData: {
                id: null,
                trang_thai: 'cho_xu_ly',
                ghi_chu: '',
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
            this.modal = new window.bootstrap.Modal(document.getElementById('donHangModal'));
            this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
        } else {
            setTimeout(() => {
                if (window.bootstrap) {
                    this.modal = new window.bootstrap.Modal(document.getElementById('donHangModal'));
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
                    trang_thai: this.trangThaiFilter || undefined,
                    sort_by: this.sortBy,
                    sort_order: 'desc',
                    per_page: this.perPage,
                    page: this.currentPage,
                };
                const response = await donHangService.getData(params);
                if (response.data.success) {
                    this.donHangs = response.data.data.data || [];
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
            this.formData = {
                id: item.id,
                trang_thai: item.trang_thai,
                ghi_chu: item.ghi_chu || '',
            };
            this.modal.show();
        },
        async saveData() {
            this.saving = true;
            try {
                await donHangService.update(this.formData.id, this.formData);
                this.modal.hide();
                this.loadData();
                alert('Cập nhật thành công!');
            } catch (error) {
                console.error('Error saving data:', error);
                alert(error.response?.data?.message || 'Có lỗi xảy ra khi lưu dữ liệu');
            } finally {
                this.saving = false;
            }
        },
        viewDetail(item) {
            // TODO: Navigate to detail page or show modal
            alert(`Chi tiết đơn hàng #${item.id}`);
        },
        confirmDelete(item) {
            this.selectedItem = item;
            this.deleteModal.show();
        },
        async deleteData() {
            this.deleting = true;
            try {
                await donHangService.delete(this.selectedItem.id);
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
        getStatusClass(status) {
            const classes = {
                'cho_xu_ly': 'bg-warning',
                'dang_xu_ly': 'bg-info',
                'dang_giao': 'bg-primary',
                'hoan_thanh': 'bg-success',
                'da_huy': 'bg-danger',
            };
            return classes[status] || 'bg-secondary';
        },
        getStatusText(status) {
            const texts = {
                'cho_xu_ly': 'Chờ xử lý',
                'dang_xu_ly': 'Đang xử lý',
                'dang_giao': 'Đang giao',
                'hoan_thanh': 'Hoàn thành',
                'da_huy': 'Đã hủy',
            };
            return texts[status] || status;
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
