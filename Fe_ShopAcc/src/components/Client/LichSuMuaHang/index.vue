<template>
    <div class="lich-su-mua-hang-page">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card border-0 shadow-lg rounded-4">
                        <div class="card-header bg-gradient-primary text-white py-4 rounded-top-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="mb-0 d-flex align-items-center gap-2">
                                    <i class="bx bx-history fs-4"></i>
                                    <span>Lịch Sử Đơn Hàng</span>
                                </h3>
                                <span v-if="orders.length > 0" class="badge bg-light text-primary fs-6">
                                    Tổng: {{ orders.length }} đơn
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Loading State -->
                            <div v-if="loading" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Đang tải...</span>
                                </div>
                                <p class="mt-3 text-muted fs-5">Đang tải danh sách đơn hàng...</p>
                            </div>

                            <!-- Empty State -->
                            <div v-else-if="orders.length === 0" class="text-center py-5">
                                <div class="empty-state-icon mb-4">
                                    <i class="bx bx-package"></i>
                                </div>
                                <h5 class="text-muted mb-3">Bạn chưa có đơn hàng nào</h5>
                                <p class="text-muted mb-4">Hãy bắt đầu mua sắm để xem lịch sử đơn hàng của bạn</p>
                                <router-link to="/" class="btn btn-primary btn-lg px-4">
                                    <i class="bx bx-shopping-bag me-2"></i>
                                    Mua sắm ngay
                                </router-link>
                            </div>

                            <!-- Orders Table -->
                            <div v-else class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-bold text-center" style="width: 60px;">#</th>
                                            <th class="fw-bold">Mã đơn hàng</th>
                                            <th class="fw-bold">Ngày đặt</th>
                                            <th class="fw-bold text-end">Tổng tiền</th>
                                            <th class="fw-bold text-center">Trạng thái</th>
                                            <th class="fw-bold text-center" style="width: 120px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr 
                                            v-for="(order, index) in orders" 
                                            :key="order.id"
                                            class="order-row"
                                        >
                                            <td class="text-center fw-semibold text-muted">{{ index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bx bx-package text-primary"></i>
                                                    <span class="fw-semibold">#{{ order.id }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2 text-muted">
                                                    <i class="bx bx-calendar"></i>
                                                    <span>{{ formatDate(order.created_at) }}</span>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold text-success fs-5">
                                                    {{ formatCurrency(order.tong_tien) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge px-3 py-2" :class="getStatusClass(order.trang_thai)">
                                                    {{ getStatusText(order.trang_thai) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <router-link 
                                                    :to="`/client/chi-tiet-don-hang/${order.id}`"
                                                    class="btn btn-sm btn-primary"
                                                    title="Xem chi tiết"
                                                >
                                                    <i class="bx bx-show me-1"></i>
                                                    Chi tiết
                                                </router-link>
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
                // Silent fail - không hiển thị alert
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
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    padding: 1rem;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #495057;
}

.table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e9ecef;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.table tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

.order-row {
    cursor: pointer;
}

.empty-state-icon {
    font-size: 5rem;
    color: #dee2e6;
}

.empty-state-icon i {
    display: block;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
}

.btn-primary:hover {
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

/* Responsive */
@media (max-width: 768px) {
    .table thead {
        display: none;
    }
    
    .table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1rem;
        background: white;
    }
    
    .table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border: none;
    }
    
    .table tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        color: #6c757d;
        margin-right: 1rem;
    }
    
    .table tbody td:last-child {
        border-top: 1px solid #e9ecef;
        margin-top: 0.5rem;
        padding-top: 0.75rem;
    }
}
</style>

