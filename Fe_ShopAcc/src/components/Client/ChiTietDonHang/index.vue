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
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-3 text-muted">Đang tải chi tiết đơn hàng...</p>
                    </div>

                    <!-- Error State -->
                    <div v-else-if="error" class="alert alert-danger">
                        <i class="bx bx-error-circle me-2"></i>
                        {{ error }}
                    </div>

                    <!-- Order Details -->
                    <div v-else-if="order" class="card border-0 shadow-lg">
                        <div class="card-header bg-primary text-white py-4">
                            <h3 class="mb-0 d-flex align-items-center justify-content-between">
                                <span>
                                    <i class="bx bx-package me-2"></i>
                                    Chi tiết đơn hàng #{{ order.id }}
                                </span>
                                <span class="badge bg-light text-dark fs-6">
                                    {{ getStatusText(order.trang_thai) }}
                                </span>
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <!-- Order Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong><i class="bx bx-calendar me-2"></i>Ngày đặt:</strong>
                                        {{ formatDate(order.created_at) }}
                                    </p>
                                    <p class="mb-2">
                                        <strong><i class="bx bx-money me-2"></i>Tổng tiền:</strong>
                                        <span class="text-primary fw-bold fs-5">{{ formatCurrency(order.tong_tien) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong><i class="bx bx-info-circle me-2"></i>Trạng thái:</strong>
                                        <span class="badge" :class="getStatusClass(order.trang_thai)">
                                            {{ getStatusText(order.trang_thai) }}
                                        </span>
                                    </p>
                                    <p v-if="order.ma_giam_gia" class="mb-2">
                                        <strong><i class="bx bx-tag me-2"></i>Mã giảm giá:</strong>
                                        {{ order.ma_giam_gia }}
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <!-- Products List -->
                            <h5 class="mb-3">
                                <i class="bx bx-list-ul me-2"></i>
                                Sản phẩm đã mua
                            </h5>
                            <div v-if="order.chi_tiet_don_hangs && order.chi_tiet_don_hangs.length > 0">
                                <div 
                                    v-for="(item, index) in order.chi_tiet_don_hangs" 
                                    :key="item.id"
                                    class="card mb-3 border"
                                >
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h6 class="mb-2">
                                                    <i class="bx bx-package me-2 text-primary"></i>
                                                    {{ item.san_pham?.ten || 'Sản phẩm #' + item.san_pham_id }}
                                                </h6>
                                                <p class="text-muted mb-1">
                                                    Số lượng: <strong>{{ item.so_luong }}</strong>
                                                </p>
                                                <p class="mb-0">
                                                    Giá: <strong class="text-primary">{{ formatCurrency(item.gia) }}</strong>
                                                </p>
                                            </div>
                                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                                <button 
                                                    class="btn btn-primary btn-sm"
                                                    @click="loadProductAccounts(order.id, item.san_pham_id, index)"
                                                    :disabled="loadingAccounts[index]"
                                                >
                                                    <i class="bx bx-show me-1"></i>
                                                    {{ loadingAccounts[index] ? 'Đang tải...' : 'Xem tài khoản' }}
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Account Details Modal -->
                                        <div 
                                            v-if="productAccounts[index] && productAccounts[index].length > 0"
                                            class="mt-3 pt-3 border-top"
                                        >
                                            <h6 class="mb-2">
                                                <i class="bx bx-key me-2"></i>
                                                Thông tin tài khoản:
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Tài khoản</th>
                                                            <th>Mật khẩu</th>
                                                            <th>Thông tin bổ sung</th>
                                                            <th>Thao tác</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="account in productAccounts[index]" :key="account.id">
                                                            <td>{{ account.username }}</td>
                                                            <td>
                                                                <span v-if="!showPassword[index]">••••••••</span>
                                                                <span v-else>{{ account.password }}</span>
                                                            </td>
                                                            <td>{{ account.thong_tin_bo_sung || 'N/A' }}</td>
                                                            <td>
                                                                <button 
                                                                    class="btn btn-sm btn-outline-primary me-1"
                                                                    @click="togglePassword(index)"
                                                                >
                                                                    <i :class="showPassword[index] ? 'bx bx-hide' : 'bx bx-show'"></i>
                                                                </button>
                                                                <button 
                                                                    class="btn btn-sm btn-outline-success"
                                                                    @click="copyToClipboard(account.username + '|' + account.password)"
                                                                >
                                                                    <i class="bx bx-copy"></i>
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
                            <div v-else class="text-center text-muted py-4">
                                <p>Không có sản phẩm nào trong đơn hàng này.</p>
                            </div>
                        </div>
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
        };
    },
    mounted() {
        this.loadDonHang();
    },
    methods: {
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
        async loadProductAccounts(orderId, productId, index) {
            this.$set(this.loadingAccounts, index, true);
            try {
                const userStr = localStorage.getItem('user');
                const user = userStr ? JSON.parse(userStr) : null;
                const clientId = user?.id;

                const response = await api.get(
                    `/client/don-hang/${orderId}/san-pham/${productId}/tai-khoan`,
                    {
                        params: { client_id: clientId },
                        headers: { 'X-Client-Id': clientId }
                    }
                );

                if (response.data.success) {
                    this.$set(this.productAccounts, index, response.data.data || []);
                }
            } catch (error) {
                console.error('Error loading product accounts:', error);
                alert('Không thể tải thông tin tài khoản. Vui lòng thử lại sau.');
            } finally {
                this.$set(this.loadingAccounts, index, false);
            }
        },
        togglePassword(index) {
            this.$set(this.showPassword, index, !this.showPassword[index]);
        },
        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                alert('Đã sao chép vào clipboard!');
            } catch (error) {
                console.error('Error copying to clipboard:', error);
                alert('Không thể sao chép. Vui lòng thử lại.');
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
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}
</style>


