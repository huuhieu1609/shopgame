<template>
    <div class="thanh-toan-page">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <!-- Header Messages -->
                    <div class="payment-header mb-4">
                        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                            <strong>HUUHIEU.SHOP</strong> - SHOP CHUYÊN BÁN ACC REG + ACC RANDOM
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Left: Order Summary -->
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-lg h-100">
                                <div class="card-body p-4">
                                    <h4 class="fw-bold mb-4">Thông tin đơn hàng</h4>
                                    
                                    <div v-if="loadingProduct" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>

                                    <div v-else-if="sanPham">
                                        <div class="d-flex gap-3 mb-4">
                                            <img 
                                                :src="sanPham.hinh_anh || 'https://thienlq.shop/uploads/03-11-2025/items/4/16b83bad-32aa-4e3c-81e7-441ee91a68a0.png'" 
                                                :alt="sanPham.ten"
                                                class="product-thumbnail rounded"
                                                style="width: 80px; height: 80px; object-fit: cover;"
                                            >
                                            <div class="flex-grow-1">
                                                <h5 class="fw-bold mb-1">{{ sanPham.ten }}</h5>
                                                <p class="text-muted small mb-0">{{ sanPham.danh_muc?.ten || '' }}</p>
                                            </div>
                                        </div>

                                        <div class="order-details">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Đơn giá:</span>
                                                <strong>{{ formatCurrency(sanPham.gia) }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Số lượng:</span>
                                                <strong>{{ soLuong }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2" v-if="maGiamGia">
                                                <span>Giảm giá:</span>
                                                <strong class="text-success">-{{ formatCurrency(giamGia) }}</strong>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold fs-5">Tổng tiền:</span>
                                                <strong class="text-danger fs-4">{{ formatCurrency(tongTien) }}</strong>
                                            </div>
                                        </div>

                                        <!-- Mã giảm giá -->
                                        <div class="mt-4">
                                            <label class="form-label fw-semibold">Mã giảm giá (nếu có)</label>
                                            <div class="input-group">
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    placeholder="Nhập mã giảm giá"
                                                    v-model="maGiamGiaInput"
                                                >
                                                <button 
                                                    class="btn btn-outline-primary"
                                                    @click="applyDiscountCode"
                                                    :disabled="applyingDiscount"
                                                >
                                                    <span v-if="applyingDiscount" class="spinner-border spinner-border-sm me-1"></span>
                                                    Áp dụng
                                                </button>
                                            </div>
                                            <div v-if="discountError" class="text-danger small mt-1">{{ discountError }}</div>
                                            <div v-if="discountSuccess" class="text-success small mt-1">{{ discountSuccess }}</div>
                                        </div>
                                    </div>

                                    <div v-else class="text-center text-muted py-4">
                                        <p>Không tìm thấy sản phẩm</p>
                                        <router-link to="/" class="btn btn-primary">Về trang chủ</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Order Details and Accounts -->
                        <div class="col-lg-7">
                            <!-- Order Success: Show Order Details and Accounts -->
                            <div v-if="orderSuccess" class="order-success-section">
                                <div class="card border-0 shadow-lg">
                                    <div class="card-header bg-success text-white py-4">
                                        <h4 class="mb-0 d-flex align-items-center gap-2">
                                            <i class="bx bx-check-circle fs-4"></i>
                                            <span>Đặt hàng thành công!</span>
                                        </h4>
                                    </div>
                                    <div class="card-body p-4">
                                        <!-- Order Details -->
                                        <div class="order-details-section mb-4">
                                            <h5 class="fw-bold mb-3">
                                                <i class="bx bx-package me-2 text-primary"></i>
                                                Chi tiết đơn hàng
                                            </h5>
                                            <div class="row g-3 mb-3">
                                                <div class="col-6">
                                                    <div class="info-box p-3 bg-light rounded">
                                                        <div class="text-muted small mb-1">Mã đơn hàng</div>
                                                        <div class="fw-bold fs-5">#{{ createdOrderId }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="info-box p-3 bg-light rounded">
                                                        <div class="text-muted small mb-1">Tổng tiền</div>
                                                        <div class="fw-bold fs-5 text-success">{{ formatCurrency(tongTien) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-box p-3 bg-light rounded">
                                                <div class="text-muted small mb-1">Ngày đặt hàng</div>
                                                <div class="fw-semibold">{{ formatDate(new Date()) }}</div>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <!-- Accounts Section -->
                                        <div class="accounts-section">
                                            <h5 class="fw-bold mb-3">
                                                <i class="bx bx-user me-2 text-primary"></i>
                                                Thông tin tài khoản đã mua
                                            </h5>

                                            <div v-if="loadingAccounts" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Đang tải...</span>
                                                </div>
                                                <p class="mt-3 text-muted">Đang tải thông tin tài khoản...</p>
                                            </div>

                                            <div v-else-if="accounts.length > 0">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="text-center" style="width: 60px;">#</th>
                                                                <th>Username</th>
                                                                <th>Password</th>
                                                                <th v-if="hasEmail">Email/Thông tin</th>
                                                                <th class="text-center" style="width: 100px;">Thao tác</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(account, index) in accounts" :key="account.id || index">
                                                                <td class="text-center fw-semibold">{{ index + 1 }}</td>
                                                                <td>
                                                                    <strong class="text-primary">{{ account.username || 'N/A' }}</strong>
                                                                </td>
                                                                <td>
                                                                    <strong class="text-success">{{ account.password || 'N/A' }}</strong>
                                                                </td>
                                                                <td v-if="hasEmail">
                                                                    <span class="text-muted">{{ account.email || account.thong_tin_bo_sung || 'N/A' }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button 
                                                                        class="btn btn-sm btn-outline-primary"
                                                                        @click="copyAccount(account)"
                                                                        title="Copy tài khoản"
                                                                    >
                                                                        <i class="bx bx-copy"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="mt-3">
                                                    <button 
                                                        class="btn btn-primary w-100"
                                                        @click="copyAllAccounts"
                                                    >
                                                        <i class="bx bx-copy me-2"></i>
                                                        Copy tất cả tài khoản
                                                    </button>
                                                </div>
                                            </div>

                                            <div v-else class="alert alert-warning">
                                                <i class="bx bx-info-circle me-2"></i>
                                                Chưa có thông tin tài khoản. Vui lòng liên hệ admin để được hỗ trợ.
                                            </div>
                                        </div>

                                        <div class="d-flex gap-3 mt-4">
                                            <router-link to="/client/lich-su-mua-hang" class="btn btn-outline-primary flex-grow-1">
                                                <i class="bx bx-history me-2"></i>
                                                Xem lịch sử đơn hàng
                                            </router-link>
                                            <router-link to="/" class="btn btn-primary flex-grow-1">
                                                <i class="bx bx-home me-2"></i>
                                                Về trang chủ
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Before Order: Show Order Summary and Buy Button -->
                            <div v-else>
                                <div class="card border-0 shadow-lg">
                                    <div class="card-body p-4">
                                        <h4 class="fw-bold mb-4">
                                            <i class="bx bx-shopping-bag me-2 text-primary"></i>
                                            Xác nhận đơn hàng
                                        </h4>
                                        
                                        <div class="alert alert-info mb-4">
                                            <i class="bx bx-info-circle me-2"></i>
                                            <strong>Lưu ý:</strong> Khi đặt hàng, tiền sẽ được trừ trực tiếp từ số dư tài khoản của bạn. 
                                            Tài khoản sẽ được hiển thị ngay sau khi đặt hàng thành công.
                                        </div>

                                        <!-- Order Summary -->
                                        <div class="order-summary p-3 bg-light rounded mb-4">
                                            <h6 class="fw-bold mb-3">Tóm tắt đơn hàng</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Sản phẩm:</span>
                                                <strong>{{ sanPham?.ten || 'N/A' }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Số lượng:</span>
                                                <strong>{{ soLuong }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Đơn giá:</span>
                                                <strong>{{ formatCurrency(sanPham?.gia || 0) }}</strong>
                                            </div>
                                            <div v-if="maGiamGia" class="d-flex justify-content-between mb-2">
                                                <span>Giảm giá:</span>
                                                <strong class="text-success">-{{ formatCurrency(giamGia) }}</strong>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold fs-5">Tổng tiền:</span>
                                                <strong class="text-danger fs-4">{{ formatCurrency(tongTien) }}</strong>
                                            </div>
                                        </div>

                                        <!-- Buy Button -->
                                        <button 
                                            class="btn btn-success btn-lg w-100 fw-bold py-3"
                                            @click="confirmPayment"
                                            :disabled="!sanPham || confirming"
                                        >
                                            <span v-if="confirming" class="spinner-border spinner-border-sm me-2"></span>
                                            <i v-else class="bx bx-check-circle me-2"></i>
                                            {{ confirming ? 'Đang xử lý...' : 'ĐẶT HÀNG NGAY' }}
                                        </button>
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
import { clientService } from '@/services/clientService';
import { clientOrderService } from '@/services/clientOrderService';
import api from '@/services/api';

export default {
    name: 'ThanhToan',
    data() {
        return {
            sanPham: null,
            loadingProduct: true,
            soLuong: 1,
            maGiamGia: null,
            maGiamGiaInput: '',
            applyingDiscount: false,
            discountError: '',
            discountSuccess: '',
            confirming: false,
            orderSuccess: false,
            createdOrderId: null,
            accounts: [],
            loadingAccounts: false,
        };
    },
    computed: {
        tongTien() {
            if (!this.sanPham) return 0;
            let total = this.sanPham.gia * this.soLuong;
            if (this.maGiamGia && this.giamGia > 0) {
                total -= this.giamGia;
            }
            return Math.max(0, total);
        },
        giamGia() {
            if (!this.maGiamGia || !this.sanPham) return 0;
            let discount = 0;
            if (this.maGiamGia.phan_tram_giam > 0) {
                discount = (this.sanPham.gia * this.soLuong * this.maGiamGia.phan_tram_giam) / 100;
            } else if (this.maGiamGia.so_tien_giam > 0) {
                discount = this.maGiamGia.so_tien_giam;
            }
            return discount;
        },
        hasEmail() {
            return this.accounts.some(acc => {
                return acc.email || (acc.thong_tin_bo_sung && acc.thong_tin_bo_sung.toLowerCase().includes('email'));
            });
        }
    },
    async mounted() {
        // Kiểm tra đăng nhập
        const token = localStorage.getItem('token');
        if (!token) {
            alert('Vui lòng đăng nhập để thanh toán!');
            this.$router.push('/client/dang-nhap?redirect=' + encodeURIComponent(this.$route.fullPath));
            return;
        }

        // Lấy sản phẩm từ query hoặc route
        const sanPhamId = this.$route.query.san_pham_id;
        if (sanPhamId) {
            await this.loadSanPham(sanPhamId);
            this.soLuong = parseInt(this.$route.query.so_luong) || 1;
        } else {
            this.loadingProduct = false;
        }
    },
    methods: {
        async loadSanPham(id) {
            this.loadingProduct = true;
            try {
                const response = await clientService.getProductById(id);
                if (response.data.success && response.data.data) {
                    this.sanPham = response.data.data;
                } else if (response.data.data) {
                    this.sanPham = response.data.data;
                }
            } catch (error) {
                console.error('Error loading product:', error);
            } finally {
                this.loadingProduct = false;
            }
        },
        async applyDiscountCode() {
            if (!this.maGiamGiaInput.trim()) {
                this.discountError = 'Vui lòng nhập mã giảm giá';
                return;
            }

            this.applyingDiscount = true;
            this.discountError = '';
            this.discountSuccess = '';

            try {
                const response = await api.post('/client/ma-giam-gia/validate', {
                    ma: this.maGiamGiaInput.trim()
                });

                if (response.data.success && response.data.data) {
                    this.maGiamGia = response.data.data;
                    this.discountSuccess = 'Áp dụng mã giảm giá thành công!';
                } else {
                    this.discountError = response.data.message || 'Mã giảm giá không hợp lệ';
                }
            } catch (error) {
                console.error('Error applying discount:', error);
                if (error.response?.data?.message) {
                    this.discountError = error.response.data.message;
                } else {
                    this.discountError = 'Mã giảm giá không hợp lệ hoặc đã hết hạn';
                }
            } finally {
                this.applyingDiscount = false;
            }
        },
        async confirmPayment() {
            if (!this.sanPham) {
                alert('Không tìm thấy sản phẩm!');
                return;
            }

            // Get user ID from localStorage
            const userStr = localStorage.getItem('user');
            const user = userStr ? JSON.parse(userStr) : null;
            const clientId = user?.id;

            if (!clientId) {
                alert('Vui lòng đăng nhập lại!');
                this.$router.push('/client/dang-nhap');
                return;
            }

            this.confirming = true;

            try {
                // Tạo đơn hàng với format đúng cho backend
                const orderData = {
                    khach_hang_id: clientId,
                    tong_tien: this.tongTien,
                    trang_thai: 'cho_xu_ly',
                    ma_giam_gia_id: this.maGiamGia?.id || null,
                    ghi_chu: `Đặt hàng sản phẩm: ${this.sanPham.ten}`,
                    chi_tiet: [{
                        san_pham_id: this.sanPham.id,
                        so_luong: this.soLuong,
                        gia: this.sanPham.gia,
                    }]
                };

                const response = await api.post('/client/don-hang', orderData, {
                    headers: {
                        'X-Client-Id': clientId
                    },
                    params: {
                        client_id: clientId
                    }
                });

                if (response.data.success) {
                    const order = response.data.data;
                    this.createdOrderId = order.id;
                    
                    // Dispatch event để menu cập nhật số dư
                    window.dispatchEvent(new Event('user-updated'));
                    
                    // Lấy thông tin tài khoản từ đơn hàng
                    await this.loadOrderAccounts(order.id, this.sanPham.id);
                    
                    this.orderSuccess = true;
                } else {
                    alert(response.data.message || 'Có lỗi xảy ra khi đặt hàng');
                }
            } catch (error) {
                console.error('Error creating order:', error);
                if (error.response?.data?.message) {
                    alert(error.response.data.message);
                } else if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    const errorMsg = Object.values(errors).flat().join(', ');
                    alert(errorMsg);
                } else {
                    alert('Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!');
                }
            } finally {
                this.confirming = false;
            }
        },
        async loadOrderAccounts(orderId, productId) {
            this.loadingAccounts = true;
            try {
                const userStr = localStorage.getItem('user');
                const user = userStr ? JSON.parse(userStr) : null;
                const clientId = user?.id;

                const response = await api.get(`/client/don-hang/${orderId}/san-pham/${productId}/tai-khoan`, {
                    headers: {
                        'X-Client-Id': clientId
                    },
                    params: {
                        client_id: clientId
                    }
                });

                if (response.data.success) {
                    this.accounts = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading accounts:', error);
            } finally {
                this.loadingAccounts = false;
            }
        },
        copyAccount(account) {
            const text = `${account.username}|${account.password}`;
            this.copyToClipboard(text);
        },
        copyAllAccounts() {
            if (this.accounts.length === 0) return;
            
            const text = this.accounts
                .map(acc => `${acc.username}|${acc.password}`)
                .join('\n');
            this.copyToClipboard(text);
        },
        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show toast notification
                alert('Đã copy vào clipboard!');
            }).catch(() => {
                // Fallback
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('Đã copy vào clipboard!');
            });
        },
        formatDate(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleString('vi-VN', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        },
        formatCurrency(value) {
            if (!value) return '0 ₫';
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        },
    }
}
</script>

<style scoped>
.thanh-toan-page {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: calc(100vh - 70px);
    padding: 2rem 0;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

/* Payment Header */
.payment-header .alert {
    border-radius: 12px;
    border-left: 4px solid;
    font-weight: 500;
}

.alert-success {
    background: linear-gradient(135deg, #d1f2eb 0%, #a8e6cf 100%);
    border-left-color: #28a745;
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
    border-left-color: #dc3545;
    color: #721c24;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    border-left-color: #17a2b8;
    color: #0c5460;
}

/* Bank Logo */
.bank-logo-wrapper {
    display: inline-block;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
}

.bank-logo-vcb {
    background: linear-gradient(135deg, #1a8b16 0%);
    box-shadow: 0 4px 15px rgba(196, 30, 58, 0.3);
}

.bank-logo-text {
    font-size: 3rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: 2px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

/* Payment Card */
.payment-card {
    background: linear-gradient(135deg, #1a8b16  0%);
    border-radius: 20px;
    color: #ffffff;
    position: relative;
    overflow: hidden;
}

.payment-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
    pointer-events: none;
}

@keyframes float {
    0%, 100% {
        transform: translate(0, 0) rotate(0deg);
    }
    50% {
        transform: translate(-30px, -30px) rotate(180deg);
    }
}

.bank-info {
    position: relative;
    z-index: 1;
}

.bank-item {
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.bank-item:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateX(5px);
}

.bank-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.bank-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #ffffff;
}

.content-code {
    font-size: 1.3rem;
    font-weight: 700;
    letter-spacing: 2px;
    color: #ffd700;
    text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
}

.copy-btn {
    border-color: rgba(255, 255, 255, 0.5);
    color: #ffffff;
    font-size: 0.85rem;
    padding: 0.25rem 0.75rem;
    transition: all 0.3s ease;
}

.copy-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.8);
    color: #ffffff;
    transform: scale(1.05);
}

.payment-note {
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    border-left: 3px solid #ffd700;
}

/* QR Code Section */
.qr-section {
    position: relative;
    z-index: 1;
    margin: 2rem 0;
}

.qr-code-wrapper {
    display: inline-block;
    padding: 1.5rem;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
}

.qr-code-image {
    width: 250px;
    height: 250px;
    border: 2px solid #000000;
    border-radius: 8px;
    display: block;
    margin: 0 auto;
}

/* Payment Footer */
.payment-footer {
    position: relative;
    z-index: 1;
}

.footer-logo {
    display: flex;
    align-items: center;
}

.napas-logo {
    font-size: 1rem;
    font-weight: 600;
    color: #ffffff;
    background: #0066cc;
    padding: 0.5rem 1rem;
    border-radius: 6px;
}

.vcb-logo-footer {
    font-size: 1.2rem;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: 2px;
}

.footer-divider {
    width: 2px;
    height: 30px;
    background: rgba(255, 255, 255, 0.3);
}

/* Instructions */
.payment-instructions .card {
    border-radius: 15px;
    background: #ffffff;
}

.payment-instructions ol {
    color: #495057;
    line-height: 1.8;
}

.payment-instructions li {
    margin-bottom: 0.5rem;
}

.product-thumbnail {
    border: 2px solid #e9ecef;
}

.order-details {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .bank-logo-text {
        font-size: 2rem;
    }

    .qr-code-image {
        width: 200px;
        height: 200px;
    }

    .bank-value {
        font-size: 1rem;
    }

    .content-code {
        font-size: 1.1rem;
    }
}

/* Account Info Section */
.account-info-section {
    animation: fadeIn 0.6s ease-out;
}

.account-item {
    transition: all 0.3s ease;
}

.account-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.account-item .card-body {
    background: #f8f9fa;
}

.account-item strong {
    color: #0d6efd;
    font-size: 1.1rem;
}
</style>