<template>
    <div class="profile-page">
        <div class="container py-5">
            <div v-if="loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div v-else class="row g-4">
                <!-- Profile Card Left -->
                <div class="col-lg-4">
                    <div class="card profile-card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="profile-avatar-wrapper position-relative d-inline-block">
                                    <img 
                                        :src="userInfo?.avatar || 'https://cellphones.com.vn/sforum/wp-content/uploads/2024/02/anh-avatar-cute-58.jpg'"
                                        alt="Avatar" 
                                        class="profile-avatar rounded-circle"
                                    >
                                    <div class="avatar-badge">
                                        <i class="bx bx-check"></i>
                                    </div>
                                </div>
                                <h3 class="profile-name mt-3 mb-1 fw-bold">{{ userInfo?.ten || 'Chưa cập nhật' }}</h3>
                                <p class="text-muted mb-0">{{ userInfo?.tai_khoan || '' }}</p>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="profile-stats">
                                <div class="stat-item d-flex align-items-center gap-3 p-3 rounded-3 mb-3">
                                    <div class="stat-icon-wrapper bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bx-receipt text-primary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="text-muted small mb-1">Tổng đơn hàng</div>
                                        <div class="fw-bold fs-4 text-primary">{{ totalOrders }}</div>
                                    </div>
                                </div>
                                
                                <div class="stat-item d-flex align-items-center gap-3 p-3 rounded-3 mb-3">
                                    <div class="stat-icon-wrapper bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bx-wallet text-success fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="text-muted small mb-1">Số dư</div>
                                        <div class="fw-bold fs-4 text-success">{{ formatCurrency(userInfo?.so_du || 0) }}</div>
                                    </div>
                                </div>

                                <div class="stat-item d-flex align-items-center gap-3 p-3 rounded-3">
                                    <div class="stat-icon-wrapper bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bx-money text-warning fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="text-muted small mb-1">Tổng chi tiêu</div>
                                        <div class="fw-bold fs-4 text-warning">{{ formatCurrency(userInfo?.tong_chi || 0) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Content Right -->
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs nav-fill border-0 mb-4" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button 
                                        class="nav-link d-flex align-items-center justify-content-center gap-2 py-3" 
                                        :class="{ active: activeTab === 'profile' }"
                                        @click="activeTab = 'profile'"
                                        type="button" 
                                        role="tab"
                                    >
                                        <i class="bx bx-user fs-5"></i>
                                        <span class="fw-semibold">Thông Tin Cá Nhân</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button 
                                        class="nav-link d-flex align-items-center justify-content-center gap-2 py-3"
                                        :class="{ active: activeTab === 'password' }"
                                        @click="activeTab = 'password'"
                                        type="button" 
                                        role="tab"
                                    >
                                        <i class="bx bx-lock-alt fs-5"></i>
                                        <span class="fw-semibold">Đổi Mật Khẩu</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button 
                                        class="nav-link d-flex align-items-center justify-content-center gap-2 py-3"
                                        :class="{ active: activeTab === 'history' }"
                                        @click="activeTab = 'history'"
                                        type="button" 
                                        role="tab"
                                    >
                                        <i class="bx bx-receipt fs-5"></i>
                                        <span class="fw-semibold">Lịch Sử Đơn Hàng</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button 
                                        class="nav-link d-flex align-items-center justify-content-center gap-2 py-3"
                                        :class="{ active: activeTab === 'transactions' }"
                                        @click="activeTab = 'transactions'"
                                        type="button" 
                                        role="tab"
                                    >
                                        <i class="bx bx-history fs-5"></i>
                                        <span class="fw-semibold">Lịch Sử Giao Dịch</span>
                                    </button>
                                </li>
                            </ul>
                            
                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- Profile Tab -->
                                <div v-show="activeTab === 'profile'" class="tab-pane show active" role="tabpanel">
                                    <!-- Error/Success Alert -->
                                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Lỗi!</strong> {{ error }}
                                        <button type="button" class="btn-close" @click="error = ''"></button>
                                    </div>
                                    <div v-if="success" class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Thành công!</strong> {{ success }}
                                        <button type="button" class="btn-close" @click="success = ''"></button>
                                    </div>

                                    <form @submit.prevent="updateProfile">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label for="ten" class="form-label fw-semibold mb-2">Họ và tên <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="bx bx-user text-muted"></i>
                                                    </span>
                                                    <input 
                                                        type="text" 
                                                        id="ten"
                                                        class="form-control border-start-0" 
                                                        placeholder="Họ và tên"
                                                        v-model="profileData.ten"
                                                        required
                                                    >
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="email" class="form-label fw-semibold mb-2">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="bx bx-envelope text-muted"></i>
                                                    </span>
                                                    <input 
                                                        type="email" 
                                                        id="email"
                                                        class="form-control border-start-0" 
                                                        :value="userInfo?.email || ''"
                                                        disabled
                                                    >
                                                </div>
                                                <small class="text-muted">Email không thể thay đổi</small>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="sdt" class="form-label fw-semibold mb-2">Số điện thoại</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="bx bx-phone text-muted"></i>
                                                    </span>
                                                    <input 
                                                        type="text" 
                                                        id="sdt"
                                                        class="form-control border-start-0" 
                                                        maxlength="10"
                                                        placeholder="Số điện thoại"
                                                        v-model="profileData.sdt"
                                                    >
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="tai_khoan" class="form-label fw-semibold mb-2">Tài khoản</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="bx bx-user-circle text-muted"></i>
                                                    </span>
                                                    <input 
                                                        type="text" 
                                                        id="tai_khoan"
                                                        class="form-control border-start-0" 
                                                        :value="userInfo?.tai_khoan || ''"
                                                        disabled
                                                    >
                                                </div>
                                                <small class="text-muted">Tài khoản không thể thay đổi</small>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="dia_chi" class="form-label fw-semibold mb-2">Địa chỉ</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="bx bx-map text-muted"></i>
                                                    </span>
                                                    <textarea 
                                                        id="dia_chi"
                                                        class="form-control border-start-0" 
                                                        rows="2"
                                                        placeholder="Địa chỉ"
                                                        v-model="profileData.dia_chi"
                                                    ></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="d-flex justify-content-end gap-3 mt-3">
                                                    <button 
                                                        type="submit" 
                                                        class="btn btn-success btn-lg px-4 fw-bold"
                                                        :disabled="saving"
                                                    >
                                                        <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
                                                        <i v-else class="bx bx-check me-2"></i>
                                                        {{ saving ? 'Đang cập nhật...' : 'Cập nhật' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Change Password Tab -->
                                <div v-show="activeTab === 'password'" class="tab-pane show active" role="tabpanel">
                                    <!-- Error/Success Alert -->
                                    <div v-if="passwordError" class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Lỗi!</strong> {{ passwordError }}
                                        <button type="button" class="btn-close" @click="passwordError = ''"></button>
                                    </div>
                                    <div v-if="passwordSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Thành công!</strong> {{ passwordSuccess }}
                                        <button type="button" class="btn-close" @click="passwordSuccess = ''"></button>
                                    </div>

                                    <form @submit.prevent="changePassword">
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label for="current_password" class="form-label fw-semibold mb-2">Mật Khẩu Hiện Tại <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-end-0">
                                                            <i class="bx bx-lock-alt text-muted"></i>
                                                        </span>
                                                        <input 
                                                            type="password" 
                                                            id="current_password"
                                                            class="form-control border-start-0" 
                                                            placeholder="Nhập mật khẩu cũ"
                                                            v-model="passwordData.current_password"
                                                            required
                                                        >
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-outline-secondary border-start-0"
                                                            @click="togglePasswordVisibility('current')"
                                                        >
                                                            <i :class="showCurrentPassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-4">
                                                    <label for="new_password" class="form-label fw-semibold mb-2">Mật Khẩu Mới <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-end-0">
                                                            <i class="bx bx-lock text-muted"></i>
                                                        </span>
                                                        <input 
                                                            type="password"
                                                            id="new_password"
                                                            class="form-control border-start-0" 
                                                            placeholder="Nhập mật khẩu mới"
                                                            v-model="passwordData.new_password"
                                                            required
                                                            minlength="6"
                                                        >
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-outline-secondary border-start-0"
                                                            @click="togglePasswordVisibility('new')"
                                                        >
                                                            <i :class="showNewPassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                                                        </button>
                                                    </div>
                                                    <small class="text-muted">Tối thiểu 6 ký tự</small>
                                                </div>
                                                
                                                <div class="mb-4">
                                                    <label for="confirm_password" class="form-label fw-semibold mb-2">Xác Nhận Mật Khẩu <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-end-0">
                                                            <i class="bx bx-lock text-muted"></i>
                                                        </span>
                                                        <input 
                                                            type="password"
                                                            id="confirm_password"
                                                            class="form-control border-start-0" 
                                                            placeholder="Nhập xác nhận mật khẩu mới"
                                                            v-model="passwordData.password_confirmation"
                                                            required
                                                        >
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-outline-secondary border-start-0"
                                                            @click="togglePasswordVisibility('confirm')"
                                                        >
                                                            <i :class="showConfirmPassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-4 text-end">
                                                    <router-link to="/client/quen-mat-khau" class="text-decoration-none">
                                                        Quên mật khẩu?
                                                    </router-link>
                                                </div>
                                                
                                                <div class="d-grid">
                                                    <button 
                                                        type="submit" 
                                                        class="btn btn-primary btn-lg fw-bold"
                                                        :disabled="changingPassword"
                                                    >
                                                        <span v-if="changingPassword" class="spinner-border spinner-border-sm me-2"></span>
                                                        <i v-else class="bx bx-lock-open me-2"></i>
                                                        {{ changingPassword ? 'Đang đổi...' : 'Đổi Mật Khẩu' }}
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="password-info-card h-100 rounded-4 p-4 d-flex flex-column justify-content-center">
                                                    <div class="text-center mb-4">
                                                        <i class="bx bx-shield-alt-2 text-primary" style="font-size: 4rem;"></i>
                                                    </div>
                                                    <h5 class="text-center fw-bold mb-3">Bảo mật tài khoản</h5>
                                                    <ul class="list-unstyled">
                                                        <li class="mb-3 d-flex align-items-start gap-2">
                                                            <i class="bx bx-check-circle text-success fs-5"></i>
                                                            <span class="small">Mật khẩu phải có ít nhất 6 ký tự</span>
                                                        </li>
                                                        <li class="mb-3 d-flex align-items-start gap-2">
                                                            <i class="bx bx-check-circle text-success fs-5"></i>
                                                            <span class="small">Nên kết hợp chữ hoa, chữ thường và số</span>
                                                        </li>
                                                        <li class="d-flex align-items-start gap-2">
                                                            <i class="bx bx-check-circle text-success fs-5"></i>
                                                            <span class="small">Không sử dụng thông tin cá nhân</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- History Tab (Orders) -->
                                <div v-show="activeTab === 'history'" class="tab-pane show active" role="tabpanel">
                                    <div v-if="loadingHistory" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle table-bordered">
                                                <thead class="table-light">
                                                    <tr class="text-center">
                                                        <th class="fw-bold">#</th>
                                                        <th class="fw-bold">Mã đơn hàng</th>
                                                        <th class="fw-bold">Ngày đặt</th>
                                                        <th class="fw-bold">Tiền</th>
                                                        <th class="fw-bold">Trạng thái</th>
                                                        <th class="fw-bold">Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="orders.length === 0">
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4 text-muted">
                                                            Chưa có đơn hàng nào
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr v-for="(order, index) in orders" :key="order.id" class="text-center">
                                                        <td class="fw-semibold">{{ index + 1 }}</td>
                                                        <td>
                                                            <span class="badge bg-opacity-10 text-primary fw-semibold">
                                                                #{{ order.id }}
                                                            </span>
                                                        </td>
                                                        <td>{{ formatDate(order.created_at) }}</td>
                                                        <td class="fw-bold text-success">{{ formatCurrency(order.tong_tien) }}</td>
                                                        <td>
                                                            <span class="badge" :class="getStatusClass(order.trang_thai)">
                                                                {{ getStatusText(order.trang_thai) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <router-link 
                                                                :to="`/client/chi-tiet-don-hang/${order.id}`"
                                                                class="btn btn-sm btn-info text-white"
                                                            >
                                                                <i class="bx bx-show"></i>
                                                            </router-link>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transactions Tab (Deposit History) -->
                                <div v-show="activeTab === 'transactions'" class="tab-pane show active" role="tabpanel">
                                    <div v-if="loadingTransactions" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle table-bordered">
                                                <thead class="table-light">
                                                    <tr class="text-center">
                                                        <th class="fw-bold">#</th>
                                                        <th class="fw-bold">Mã giao dịch</th>
                                                        <th class="fw-bold">Số tiền</th>
                                                        <th class="fw-bold">Phương thức</th>
                                                        <th class="fw-bold">Trạng thái</th>
                                                        <th class="fw-bold">Ngày tạo</th>
                                                        <th class="fw-bold">Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="transactions.length === 0">
                                                    <tr>
                                                        <td colspan="7" class="text-center py-4 text-muted">
                                                            Chưa có giao dịch nạp tiền nào
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr v-for="(transaction, index) in transactions" :key="transaction.id" class="text-center">
                                                        <td class="fw-semibold">{{ index + 1 }}</td>
                                                        <td>
                                                            <span class="badge bg-opacity-10 text-primary fw-semibold">
                                                                {{ transaction.ma_giao_dich }}
                                                            </span>
                                                        </td>
                                                        <td class="fw-bold text-success">{{ formatCurrency(transaction.so_tien) }}</td>
                                                        <td>{{ transaction.phuong_thuc || 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge" :class="getTransactionStatusClass(transaction.trang_thai)">
                                                                {{ getTransactionStatusText(transaction.trang_thai) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ formatDate(transaction.created_at) }}</td>
                                                        <td>
                                                            <button 
                                                                class="btn btn-sm btn-info text-white"
                                                                @click="viewTransactionDetail(transaction)"
                                                                title="Xem chi tiết"
                                                            >
                                                                <i class="bx bx-show"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '@/services/api';
import { clientOrderService } from '@/services/clientOrderService';

export default {
    name: 'Profile',
    data() {
        return {
            loading: true,
            loadingHistory: false,
            loadingTransactions: false,
            saving: false,
            changingPassword: false,
            activeTab: 'profile',
            userInfo: null,
            orders: [],
            transactions: [],
            profileData: {
                ten: '',
                sdt: '',
                dia_chi: '',
            },
            passwordData: {
                current_password: '',
                new_password: '',
                password_confirmation: '',
            },
            showCurrentPassword: false,
            showNewPassword: false,
            showConfirmPassword: false,
            error: '',
            success: '',
            passwordError: '',
            passwordSuccess: '',
            totalOrders: 0,
        };
    },
    async mounted() {
        // Kiểm tra đăng nhập
        const token = localStorage.getItem('token');
        if (!token) {
            this.$router.push('/client/dang-nhap');
            return;
        }

        await this.loadProfile();
        if (this.activeTab === 'history') {
            await this.loadOrders();
        }
    },
    watch: {
        activeTab(newTab) {
            if (newTab === 'history' && this.orders.length === 0) {
                this.loadOrders();
            } else if (newTab === 'transactions' && this.transactions.length === 0) {
                this.loadTransactions();
            }
        }
    },
    methods: {
        async loadProfile() {
            this.loading = true;
            try {
                // Get user ID from localStorage
                const userStr = localStorage.getItem('user');
                const user = userStr ? JSON.parse(userStr) : null;
                const clientId = user?.id;

                if (!clientId) {
                    this.$router.push('/client/dang-nhap');
                    return;
                }

                const response = await api.get('/client/khach-hang/profile', {
                    headers: {
                        'X-Client-Id': clientId
                    },
                    params: {
                        client_id: clientId
                    }
                });
                if (response.data.success) {
                    this.userInfo = response.data.data;
                    this.profileData = {
                        ten: this.userInfo.ten || '',
                        sdt: this.userInfo.sdt || '',
                        dia_chi: this.userInfo.dia_chi || '',
                    };
                }
            } catch (error) {
                console.error('Error loading profile:', error);
                if (error.response?.status === 401 || error.response?.status === 404) {
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    this.$router.push('/client/dang-nhap');
                } else {
                    this.error = 'Không thể tải thông tin. Vui lòng thử lại!';
                }
            } finally {
                this.loading = false;
            }
        },
        async updateProfile() {
            this.saving = true;
            this.error = '';
            this.success = '';

            try {
                // Get user ID from localStorage
                const userStr = localStorage.getItem('user');
                const user = userStr ? JSON.parse(userStr) : null;
                const clientId = user?.id;

                if (!clientId) {
                    this.error = 'Vui lòng đăng nhập lại';
                    this.$router.push('/client/dang-nhap');
                    return;
                }

                const response = await api.put('/client/khach-hang/profile', this.profileData, {
                    headers: {
                        'X-Client-Id': clientId
                    },
                    params: {
                        client_id: clientId
                    }
                });
                if (response.data.success) {
                    this.success = 'Cập nhật thông tin thành công!';
                    this.userInfo = { ...this.userInfo, ...this.profileData };
                    // Cập nhật localStorage
                    const user = JSON.parse(localStorage.getItem('user') || '{}');
                    const updatedUser = { ...user, ...this.profileData };
                    localStorage.setItem('user', JSON.stringify(updatedUser));
                    
                    // Dispatch event để menu cập nhật
                    window.dispatchEvent(new Event('user-updated'));
                } else {
                    this.error = response.data.message || 'Cập nhật thất bại';
                }
            } catch (error) {
                console.error('Error updating profile:', error);
                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    this.error = Object.values(errors).flat().join(', ');
                } else {
                    this.error = error.response?.data?.message || 'Có lỗi xảy ra khi cập nhật';
                }
            } finally {
                this.saving = false;
            }
        },
        async changePassword() {
            // Validation
            if (this.passwordData.new_password !== this.passwordData.password_confirmation) {
                this.passwordError = 'Mật khẩu xác nhận không khớp!';
                return;
            }

            if (this.passwordData.new_password.length < 6) {
                this.passwordError = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
                return;
            }

            this.changingPassword = true;
            this.passwordError = '';
            this.passwordSuccess = '';

            try {
                // Get user ID from localStorage
                const userStr = localStorage.getItem('user');
                const user = userStr ? JSON.parse(userStr) : null;
                const clientId = user?.id;

                if (!clientId) {
                    this.passwordError = 'Vui lòng đăng nhập lại';
                    this.$router.push('/client/dang-nhap');
                    return;
                }

                const response = await api.put('/client/khach-hang/profile', {
                    password: this.passwordData.new_password,
                    current_password: this.passwordData.current_password,
                }, {
                    headers: {
                        'X-Client-Id': clientId
                    },
                    params: {
                        client_id: clientId
                    }
                });

                if (response.data.success) {
                    this.passwordSuccess = 'Đổi mật khẩu thành công!';
                    // Reset form
                    this.passwordData = {
                        current_password: '',
                        new_password: '',
                        password_confirmation: '',
                    };
                } else {
                    this.passwordError = response.data.message || 'Đổi mật khẩu thất bại';
                }
            } catch (error) {
                console.error('Error changing password:', error);
                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    this.passwordError = Object.values(errors).flat().join(', ');
                } else {
                    this.passwordError = error.response?.data?.message || 'Có lỗi xảy ra khi đổi mật khẩu';
                }
            } finally {
                this.changingPassword = false;
            }
        },
        async loadOrders() {
            this.loadingHistory = true;
            try {
                // Get user ID from localStorage
                const userStr = localStorage.getItem('user');
                const user = userStr ? JSON.parse(userStr) : null;
                const clientId = user?.id;

                if (!clientId) {
                    this.$router.push('/client/dang-nhap');
                    return;
                }

                const response = await clientOrderService.getMyOrders({}, clientId);
                if (response.data.success) {
                    this.orders = response.data.data.data || response.data.data || [];
                    this.totalOrders = response.data.data.total || this.orders.length;
                }
            } catch (error) {
                console.error('Error loading orders:', error);
            } finally {
                this.loadingHistory = false;
            }
        },
        async loadTransactions() {
            this.loadingTransactions = true;
            try {
                const response = await clientOrderService.getMyTransactions();
                if (response.data.success) {
                    this.transactions = response.data.data.data || response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading transactions:', error);
            } finally {
                this.loadingTransactions = false;
            }
        },
        viewTransactionDetail(transaction) {
            alert(`Chi tiết giao dịch:\nMã: ${transaction.ma_giao_dich}\nSố tiền: ${this.formatCurrency(transaction.so_tien)}\nTrạng thái: ${this.getTransactionStatusText(transaction.trang_thai)}`);
        },
        getTransactionStatusClass(status) {
            const classes = {
                'cho_xu_ly': 'bg-warning',
                'dang_xu_ly': 'bg-info',
                'thanh_cong': 'bg-success',
                'that_bai': 'bg-danger',
                'da_huy': 'bg-secondary',
            };
            return classes[status] || 'bg-secondary';
        },
        getTransactionStatusText(status) {
            const texts = {
                'cho_xu_ly': 'Chờ xử lý',
                'dang_xu_ly': 'Đang xử lý',
                'thanh_cong': 'Thành công',
                'that_bai': 'Thất bại',
                'da_huy': 'Đã hủy',
            };
            return texts[status] || status;
        },
        togglePasswordVisibility(field) {
            if (field === 'current') {
                this.showCurrentPassword = !this.showCurrentPassword;
                const input = document.getElementById('current_password');
                if (input) input.type = this.showCurrentPassword ? 'text' : 'password';
            } else if (field === 'new') {
                this.showNewPassword = !this.showNewPassword;
                const input = document.getElementById('new_password');
                if (input) input.type = this.showNewPassword ? 'text' : 'password';
            } else {
                this.showConfirmPassword = !this.showConfirmPassword;
                const input = document.getElementById('confirm_password');
                if (input) input.type = this.showConfirmPassword ? 'text' : 'password';
            }
        },
        formatCurrency(value) {
            if (!value) return '0 ₫';
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        },
        formatDate(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleString('vi-VN');
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
    }
}
</script>

<style scoped>
.profile-page {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: calc(100vh - 70px);
    padding: 2rem 0;
}

.profile-card {
    border-radius: 20px !important;
    overflow: hidden;
    transition: all 0.3s ease;
}

.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15) !important;
}

.profile-avatar-wrapper {
    position: relative;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 4px solid #ffffff;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.profile-avatar:hover {
    transform: scale(1.05);
}

.avatar-badge {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    border: 3px solid #ffffff;
    box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
}

.profile-name {
    font-size: 1.5rem;
    color: #2c3e50;
}

.stat-item {
    background: #f8f9fa;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.stat-item:hover {
    background: #ffffff;
    border-color: #e9ecef;
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon-wrapper {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
}

.card {
    border-radius: 20px !important;
    border: none;
}

.nav-tabs {
    border-bottom: 2px solid #e9ecef;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    transition: all 0.3s ease;
    border-radius: 0;
    cursor: pointer;
}

.nav-tabs .nav-link:hover {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background: rgba(13, 110, 253, 0.05);
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background: rgba(13, 110, 253, 0.05);
    font-weight: 600;
}

.tab-content {
    min-height: 400px;
}

/* Đảm bảo tab-pane hiển thị khi v-show = true */
.tab-pane.show {
    display: block !important;
}

/* Override Bootstrap nếu cần */
.tab-content > [v-show] {
    display: block !important;
}

.form-label {
    color: #495057;
    font-size: 0.95rem;
}

.input-group-text {
    border-radius: 8px 0 0 8px;
    border: 1px solid #dee2e6;
    border-right: none;
}

.form-control {
    border-radius: 0 8px 8px 0;
    border: 1px solid #dee2e6;
    border-left: none;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
    z-index: 1;
}

.form-control:focus + .input-group-text,
.input-group:focus-within .input-group-text {
    border-color: #0d6efd;
    z-index: 1;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-success:hover:not(:disabled) {
    background: linear-gradient(135deg, #218838 0%, #1ea080 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
}

.password-info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px dashed #dee2e6;
}

.table {
    border-radius: 12px;
    overflow: hidden;
}

.table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    padding: 1rem;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background: #f8f9fa;
    transform: scale(1.01);
}

.badge {
    padding: 0.5rem 1rem;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-avatar {
        width: 100px;
        height: 100px;
    }
    
    .stat-icon-wrapper {
        width: 40px;
        height: 40px;
    }
    
    .nav-tabs .nav-link {
        font-size: 0.85rem;
        padding: 0.75rem 0.5rem;
    }
}
</style>