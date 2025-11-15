<template>
    <div class="lich-su-mua-hang-page">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-primary text-white py-4">
                            <h3 class="mb-0 d-flex align-items-center gap-2">
                                <i class="bx bx-history fs-4"></i>
                                <span>Lịch Sử Đơn Hàng</span>
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <!-- Loading State -->
                            <div v-if="loading" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Đang tải...</span>
                                </div>
                                <p class="mt-3 text-muted">Đang tải danh sách đơn hàng...</p>
                            </div>

                            <!-- Empty State -->
                            <div v-else-if="orders.length === 0" class="text-center py-5">
                                <i class="bx bx-package fs-1 text-muted mb-3"></i>
                                <p class="text-muted fs-5">Bạn chưa có đơn hàng nào</p>
                                <router-link to="/" class="btn btn-primary">
                                    <i class="bx bx-shopping-bag me-2"></i>
                                    Mua sắm ngay
                                </router-link>
                            </div>

                            <!-- Orders List -->
                            <div v-else class="orders-list">
                                <div 
                                    v-for="order in orders" 
                                    :key="order.id" 
                                    class="order-item card mb-3 border"
                                >
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h5 class="mb-2">
                                                    <i class="bx bx-package me-2 text-primary"></i>
                                                    Đơn hàng #{{ order.id }}
                                                </h5>
                                                <p class="text-muted mb-1">
                                                    <i class="bx bx-calendar me-2"></i>
                                                    {{ formatDate(order.created_at) }}
                                                </p>
                                                <p class="mb-0">
                                                    <span class="badge" :class="getStatusClass(order.trang_thai)">
                                                        {{ getStatusText(order.trang_thai) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <p class="mb-1">
                                                    <strong>Tổng tiền:</strong>
                                                </p>
                                                <p class="fs-5 fw-bold text-primary mb-0">
                                                    {{ formatCurrency(order.tong_tien) }}
                                                </p>
                                            </div>
                                            <div class="col-md-2 text-md-end mt-3 mt-md-0">
                                                <router-link 
                                                    :to="`/client/chi-tiet-don-hang/${order.id}`"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    <i class="bx bx-show me-1"></i>
                                                    Chi tiết
                                                </router-link>
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
    </div>
</template>

<script>
import { clientOrderService } from '@/services/clientOrderService';

export default {
    name: 'LichSuMuaHang',
    data() {
        return {
            orders: [],
            loading: false,
        };
    },
    mounted() {
        this.loadData();
    },
    methods: {
        async loadData() {
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

                const response = await clientOrderService.getMyOrders({}, clientId);
                if (response.data.success) {
                    this.orders = response.data.data.data || response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                alert('Có lỗi xảy ra khi tải danh sách đơn hàng. Vui lòng thử lại sau.');
            } finally {
                this.loading = false;
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
.lich-su-mua-hang-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.order-item {
    transition: transform 0.2s, box-shadow 0.2s;
}

.order-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}
</style>

