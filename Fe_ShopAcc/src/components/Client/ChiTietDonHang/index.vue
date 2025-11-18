<template>
    <div class="chi-tiet-don-hang-page">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <!-- Back Button -->
                    <div class="mb-4">
                        <router-link to="/client/lich-su-mua-hang" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-2"></i>
                            Quay lại
                        </router-link>
                    </div>

                    <!-- Loading State -->
                    <div v-if="loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-3 text-muted fs-5">Đang tải chi tiết đơn hàng...</p>
                    </div>

                    <!-- Error State -->
                    <div v-else-if="error" class="alert alert-danger alert-dismissible fade show">
                        <i class="bx bx-error-circle me-2"></i>
                        {{ error }}
                        <button type="button" class="btn-close" @click="error = null"></button>
                    </div>

                    <!-- Order Details -->
                    <div v-else-if="order" class="card border-0 shadow-lg rounded-4">
                        <div class="card-header bg-gradient-primary text-white py-4 rounded-top-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <h3 class="mb-0 d-flex align-items-center gap-2">
                                    <i class="bx bx-package fs-4"></i>
                                    <span>Chi tiết đơn hàng #{{ order.id }}</span>
                                </h3>
                                <span class="badge bg-light text-dark fs-6 px-3 py-2" :class="getStatusClass(order.trang_thai)">
                                    {{ getStatusText(order.trang_thai) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Order Info -->
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="info-item p-3 rounded-3 bg-light">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="bx bx-calendar text-primary fs-5"></i>
                                            <strong class="text-muted">Ngày đặt:</strong>
                                        </div>
                                        <p class="mb-0 fs-5 fw-semibold">{{ formatDate(order.created_at) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 bg-light">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="bx bx-money text-success fs-5"></i>
                                            <strong class="text-muted">Tổng tiền:</strong>
                                        </div>
                                        <p class="mb-0 fs-4 fw-bold text-success">{{ formatCurrency(order.tong_tien) }}</p>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Products List -->
                            <h5 class="mb-4 d-flex align-items-center gap-2">
                                <i class="bx bx-list-ul text-primary"></i>
                                <span>Sản phẩm đã mua</span>
                            </h5>
                            <div v-if="order.chi_tiet_don_hangs && order.chi_tiet_don_hangs.length > 0">
                                <div 
                                    v-for="(item, index) in order.chi_tiet_don_hangs" 
                                    :key="item.id"
                                    class="product-item card mb-4 border-0 shadow-sm"
                                >
                                    <div class="card-body p-4">
                                        <!-- Product Info -->
                                        <div class="row align-items-center mb-3">
                                            <div class="col-md-8">
                                                <h6 class="mb-2 fw-bold d-flex align-items-center gap-2">
                                                    <i class="bx bx-package text-primary"></i>
                                                    <span>{{ item.san_pham?.ten || 'Sản phẩm #' + item.san_pham_id }}</span>
                                                </h6>
                                                <div class="d-flex flex-wrap gap-4 text-muted">
                                                    <div>
                                                        <i class="bx bx-cart me-1"></i>
                                                        Số lượng: <strong class="text-dark">{{ item.so_luong }}</strong>
                                                    </div>
                                                    <div>
                                                        <i class="bx bx-dollar me-1"></i>
                                                        Giá: <strong class="text-primary">{{ formatCurrency(item.gia) }}</strong>
                                                    </div>
                                                    <div>
                                                        <i class="bx bx-calculator me-1"></i>
                                                        Thành tiền: <strong class="text-success">{{ formatCurrency(item.thanh_tien || item.gia * item.so_luong) }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-3">

                                        <!-- Accounts Section -->
                                        <div class="accounts-section">
                                            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                                <i class="bx bx-user text-success"></i>
                                                <span>Thông tin tài khoản đã mua ({{ item.so_luong }} tài khoản)</span>
                                            </h6>

                                            <!-- Loading State -->
                                            <div v-if="loadingAccounts[index]" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Đang tải...</span>
                                                </div>
                                                <p class="mt-3 text-muted">Đang tải thông tin tài khoản...</p>
                                            </div>

                                            <!-- Accounts Cards -->
                                            <div v-else-if="productAccounts[index] && productAccounts[index].length > 0">
                                                <div 
                                                    v-for="(account, accIndex) in productAccounts[index]" 
                                                    :key="account.id || accIndex"
                                                    class="account-card mb-3"
                                                >
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body p-4">
                                                            <h6 class="fw-bold mb-3 text-dark">
                                                                Thông Tin Tài Khoản 
                                                                <span class="text-success">{{ account.id || (accIndex + 1) }}</span>
                                                            </h6>
                                                            
                                                            <!-- Tài Khoản -->
                                                            <div class="account-field mb-3">
                                                                <label class="form-label fw-semibold text-muted mb-2">Tài Khoản</label>
                                                                <div class="input-group">
                                                                    <input 
                                                                        type="text" 
                                                                        class="form-control" 
                                                                        :value="account.username || 'N/A'"
                                                                        readonly
                                                                    >
                                                                    <button 
                                                                        class="btn btn-outline-secondary"
                                                                        type="button"
                                                                        @click="copyToClipboard(account.username || '')"
                                                                        title="Copy tài khoản"
                                                                    >
                                                                        <i class="bx bx-copy"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <!-- Mật Khẩu -->
                                                            <div class="account-field mb-3">
                                                                <label class="form-label fw-semibold text-muted mb-2">Mật Khẩu</label>
                                                                <div class="input-group">
                                                                    <input 
                                                                        :type="showPassword[account.id] ? 'text' : 'password'"
                                                                        class="form-control" 
                                                                        :value="account.password || 'N/A'"
                                                                        readonly
                                                                    >
                                                                    <button 
                                                                        class="btn btn-outline-secondary"
                                                                        type="button"
                                                                        @click="togglePassword(account.id)"
                                                                        :title="showPassword[account.id] ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'"
                                                                    >
                                                                        <i :class="showPassword[account.id] ? 'bx bx-hide' : 'bx bx-show'"></i>
                                                                    </button>
                                                                    <button 
                                                                        class="btn btn-outline-secondary"
                                                                        type="button"
                                                                        @click="copyToClipboard(account.password || '')"
                                                                        title="Copy mật khẩu"
                                                                    >
                                                                        <i class="bx bx-copy"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <!-- Cookie / 2FA -->
                                                            <div class="account-field mb-0" v-if="account.thong_tin_bo_sung">
                                                                <label class="form-label fw-semibold text-muted mb-2">Cookie / 2FA</label>
                                                                <div class="input-group">
                                                                    <input 
                                                                        type="text" 
                                                                        class="form-control" 
                                                                        :value="account.thong_tin_bo_sung"
                                                                        readonly
                                                                    >
                                                                    <button 
                                                                        class="btn btn-outline-secondary"
                                                                        type="button"
                                                                        @click="copyToClipboard(account.thong_tin_bo_sung || '')"
                                                                        title="Copy Cookie/2FA"
                                                                    >
                                                                        <i class="bx bx-copy"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Empty State -->
                                            <div v-else class="alert alert-warning mb-0">
                                                <i class="bx bx-info-circle me-2"></i>
                                                Chưa có thông tin tài khoản cho sản phẩm này.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center text-muted py-5">
                                <i class="bx bx-package fs-1 mb-3"></i>
                                <p>Không có sản phẩm nào trong đơn hàng này.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Modal Overlay -->
        <div 
            v-if="showModal" 
            class="modal-overlay"
            @click="closeModal"
        >
            <div class="modal-container" @click.stop>
                <div class="modal-content-custom">
                    <div class="modal-header-custom bg-primary text-white">
                        <h5 class="modal-title-custom d-flex align-items-center gap-2">
                            <i class="bx bx-key fs-4"></i>
                            <span>Thông tin tài khoản - {{ currentProductName }}</span>
                        </h5>
                        <button type="button" class="btn-close-custom" @click="closeModal" aria-label="Close">
                            <i class="bx bx-x fs-3"></i>
                        </button>
                    </div>
                    <div class="modal-body-custom p-4">
                        <div v-if="loadingAccounts[currentProductIndex]" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Đang tải...</span>
                            </div>
                            <p class="mt-3 text-muted fs-5">Đang tải thông tin tài khoản...</p>
                        </div>
                        <div v-else-if="currentAccounts.length > 0">
                            <div class="alert alert-info mb-4">
                                <i class="bx bx-info-circle me-2"></i>
                                Bạn đã mua <strong>{{ currentAccounts.length }}</strong> tài khoản cho sản phẩm này.
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 60px;">#</th>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th v-if="hasEmail">Email</th>
                                            <th class="text-center" style="width: 150px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(account, index) in currentAccounts" :key="account.id">
                                            <td class="text-center fw-semibold text-muted">{{ index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bx bx-user text-primary"></i>
                                                    <strong>{{ account.username || 'N/A' }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bx bx-lock text-warning"></i>
                                                    <span v-if="!showPassword[account.id]" class="text-muted">••••••••</span>
                                                    <strong v-else class="text-primary">{{ account.password || 'N/A' }}</strong>
                                                </div>
                                            </td>
                                            <td v-if="hasEmail">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bx bx-envelope text-info"></i>
                                                    <span>{{ account.email || account.thong_tin_bo_sung || 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button 
                                                        class="btn btn-sm btn-outline-primary"
                                                        @click="togglePassword(account.id)"
                                                        :title="showPassword[account.id] ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'"
                                                    >
                                                        <i :class="showPassword[account.id] ? 'bx bx-hide' : 'bx bx-show'"></i>
                                                    </button>
                                                    <button 
                                                        class="btn btn-sm btn-outline-success"
                                                        @click="copyAccount(account)"
                                                        title="Copy tài khoản"
                                                    >
                                                        <i class="bx bx-copy"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 text-end">
                                <button 
                                    class="btn btn-primary"
                                    @click="copyAllAccounts"
                                >
                                    <i class="bx bx-copy me-2"></i>
                                    Copy tất cả tài khoản
                                </button>
                            </div>
                        </div>
                        <div v-else class="text-center py-4">
                            <i class="bx bx-info-circle fs-1 text-muted mb-3"></i>
                            <p class="text-muted">Chưa có thông tin tài khoản cho sản phẩm này.</p>
                        </div>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { clientOrderService } from '@/services/clientOrderService';
import api from '@/services/api';

export default {
    name: 'ChiTietDonHang',
    data() {
        return {
            order: null,
            loading: false,
            error: null,
            productAccounts: {},
            loadingAccounts: {},
            showPassword: {},
            currentAccounts: [],
            currentProductIndex: null,
            currentProductName: '',
            showModal: false,
        };
    },
    computed: {
        hasEmail() {
            return this.currentAccounts.some(acc => {
                return acc.email || (acc.thong_tin_bo_sung && acc.thong_tin_bo_sung.toLowerCase().includes('email'));
            });
        }
    },
    mounted() {
        this.loadDonHang();
    },
    methods: {
        hasEmailForProduct(index) {
            const accounts = this.productAccounts[index] || [];
            return accounts.some(acc => {
                return acc.email || (acc.thong_tin_bo_sung && acc.thong_tin_bo_sung.toLowerCase().includes('email'));
            });
        },
        async loadDonHang() {
            this.loading = true;
            this.error = null;
            try {
                const orderId = this.$route.params.id;
                const userStr = localStorage.getItem('user');
                const user = userStr ? JSON.parse(userStr) : null;
                const clientId = user?.id;

                if (!clientId) {
                    this.$router.push('/client/dang-nhap');
                    return;
                }

                const response = await clientOrderService.getMyOrder(orderId, clientId);
                if (response.data.success) {
                    this.order = response.data.data;
                    // Tự động load tài khoản cho tất cả sản phẩm trong đơn hàng
                    if (this.order.chi_tiet_don_hangs && this.order.chi_tiet_don_hangs.length > 0) {
                        this.loadAllProductAccounts(orderId);
                    }
                } else {
                    this.error = response.data.message || 'Không thể tải chi tiết đơn hàng';
                }
            } catch (error) {
                console.error('Error loading order:', error);
                this.error = error.response?.data?.message || 'Có lỗi xảy ra khi tải chi tiết đơn hàng';
            } finally {
                this.loading = false;
            }
        },
        async loadAllProductAccounts(orderId) {
            if (!this.order || !this.order.chi_tiet_don_hangs) return;

            const userStr = localStorage.getItem('user');
            const user = userStr ? JSON.parse(userStr) : null;
            const clientId = user?.id;

            if (!clientId) return;

            // Load tài khoản cho từng sản phẩm
            for (let index = 0; index < this.order.chi_tiet_don_hangs.length; index++) {
                const item = this.order.chi_tiet_don_hangs[index];
                this.$set(this.loadingAccounts, index, true);
                
                try {
                    const response = await api.get(
                        `/client/don-hang/${orderId}/san-pham/${item.san_pham_id}/tai-khoan`,
                        {
                            params: { client_id: clientId },
                            headers: { 'X-Client-Id': clientId }
                        }
                    );

                    if (response.data.success && response.data.data) {
                        const accounts = Array.isArray(response.data.data) ? response.data.data : [];
                        this.$set(this.productAccounts, index, accounts);
                    } else {
                        this.$set(this.productAccounts, index, []);
                    }
                } catch (error) {
                    console.error(`Error loading accounts for product ${item.san_pham_id}:`, error);
                    this.$set(this.productAccounts, index, []);
                } finally {
                    this.$set(this.loadingAccounts, index, false);
                }
            }
        },
        async openAccountModal(orderId, productId, index, productName) {
            this.currentProductIndex = index;
            this.currentProductName = productName || 'Sản phẩm';
            this.currentAccounts = [];
            this.showModal = true;
            
            // Load accounts
            this.$set(this.loadingAccounts, index, true);
            try {
                const userStr = localStorage.getItem('user');
                const user = userStr ? JSON.parse(userStr) : null;
                const clientId = user?.id;

                if (!clientId) {
                    this.error = 'Vui lòng đăng nhập để xem tài khoản';
                    this.closeModal();
                    return;
                }

                const response = await api.get(
                    `/client/don-hang/${orderId}/san-pham/${productId}/tai-khoan`,
                    {
                        params: { client_id: clientId },
                        headers: { 'X-Client-Id': clientId }
                    }
                );

                if (response.data.success && response.data.data) {
                    const accounts = Array.isArray(response.data.data) ? response.data.data : [];
                    this.$set(this.productAccounts, index, accounts);
                    this.currentAccounts = accounts;
                } else {
                    this.currentAccounts = [];
                }
            } catch (error) {
                console.error('Error loading product accounts:', error);
                this.currentAccounts = [];
                // Không hiển thị alert, chỉ log lỗi
            } finally {
                this.$set(this.loadingAccounts, index, false);
            }
        },
        closeModal() {
            this.showModal = false;
        },
        togglePassword(accountId) {
            this.$set(this.showPassword, accountId, !this.showPassword[accountId]);
        },
        copyAccount(account) {
            const text = `${account.username}|${account.password}`;
            this.copyToClipboard(text);
        },
        copyAllAccounts() {
            if (this.currentAccounts.length === 0) return;
            
            const text = this.currentAccounts
                .map(acc => `${acc.username}|${acc.password}`)
                .join('\n');
            this.copyToClipboard(text);
        },
        copyAllAccountsForProduct(index) {
            const accounts = this.productAccounts[index] || [];
            if (accounts.length === 0) return;
            
            const text = accounts
                .map(acc => `${acc.username}|${acc.password}`)
                .join('\n');
            this.copyToClipboard(text);
        },
        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                // Show toast notification
                this.showToast('Đã sao chép vào clipboard!', 'success');
            } catch (error) {
                console.error('Error copying to clipboard:', error);
                // Fallback
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                this.showToast('Đã sao chép vào clipboard!', 'success');
            }
        },
        showToast(message, type = 'info') {
            // Simple alert for now, can be replaced with toast library
            alert(message);
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
                'cho_xu_ly': 'bg-warning text-dark',
                'dang_xu_ly': 'bg-info text-white',
                'da_xac_nhan': 'bg-primary text-white',
                'dang_giao': 'bg-info text-white',
                'hoan_thanh': 'bg-success text-white',
                'da_huy': 'bg-danger text-white',
                'that_bai': 'bg-secondary text-white',
            };
            return classes[status] || 'bg-secondary text-white';
        },
        getStatusText(status) {
            const texts = {
                'cho_xu_ly': 'Chờ xử lý',
                'dang_xu_ly': 'Đang xử lý',
                'da_xac_nhan': 'Đã xác nhận',
                'dang_giao': 'Đang giao',
                'hoan_thanh': 'Hoàn thành',
                'da_huy': 'Đã hủy',
                'that_bai': 'Thất bại',
            };
            return texts[status] || status;
        },
    },
};
</script>

<style scoped>
.chi-tiet-don-hang-page {
    min-height: calc(100vh - 70px);
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    padding: 2rem 0;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
}

.rounded-top-4 {
    border-top-left-radius: 1.5rem !important;
    border-top-right-radius: 1.5rem !important;
}

.rounded-4 {
    border-radius: 1.5rem !important;
}

.card {
    border: none;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.info-item {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.info-item:hover {
    background: #ffffff !important;
    border-color: #0d6efd;
    transform: translateX(5px);
}

.product-item {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef !important;
}

.product-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
    border-color: #0d6efd !important;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
}

.badge {
    padding: 0.5rem 1rem;
    font-weight: 600;
    font-size: 0.85rem;
    border-radius: 0.5rem;
}

.table {
    border-radius: 8px;
    overflow: hidden;
}

.table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
}

.modal-header {
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

.modal-content {
    border-radius: 1rem;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

.modal-container {
    max-width: 90%;
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

.modal-content-custom {
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.modal-header-custom {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

.modal-title-custom {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.btn-close-custom {
    background: none;
    border: none;
    color: #ffffff;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.25rem;
    line-height: 1;
    transition: all 0.3s ease;
    border-radius: 0.25rem;
}

.btn-close-custom:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
}

.modal-body-custom {
    max-height: 60vh;
    overflow-y: auto;
}

.modal-footer-custom {
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .info-item {
        margin-bottom: 1rem;
    }
    
    .product-item .card-body {
        padding: 1.5rem !important;
    }
    
    .btn-lg {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    
    .modal-container {
        max-width: 95%;
        margin: 1rem;
    }
    
    .modal-header-custom,
    .modal-body-custom,
    .modal-footer-custom {
        padding: 1rem;
    }
    
    .table {
        font-size: 0.85rem;
    }
}

/* Account Card Styles */
.account-card {
    transition: all 0.3s ease;
}

.account-card:hover {
    transform: translateY(-2px);
}

.account-card .card {
    background: #ffffff;
    border-radius: 12px;
}

.account-field label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.account-field .input-group .form-control {
    border-right: none;
    font-family: 'Courier New', monospace;
    font-size: 0.95rem;
    background-color: #f8f9fa;
}

.account-field .input-group .btn {
    border-left: none;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
}

.account-field .input-group .btn:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #0d6efd;
}

.account-field .input-group .btn i {
    font-size: 1rem;
}
</style>
