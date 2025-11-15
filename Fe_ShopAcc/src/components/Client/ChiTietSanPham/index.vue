<template>
    <div class="container-fluid py-4">
        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Đang tải thông tin sản phẩm...</p>
        </div>

        <div v-else-if="error" class="alert alert-danger">
            <h4>Lỗi!</h4>
            <p>{{ error }}</p>
            <router-link to="/" class="btn btn-primary">Về trang chủ</router-link>
        </div>

        <div v-else-if="sanPham" class="row g-4">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-md-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <img 
                            :src="sanPham.hinh_anh || 'https://thienlq.shop/uploads/03-11-2025/items/4/16b83bad-32aa-4e3c-81e7-441ee91a68a0.png'" 
                            :alt="sanPham.ten" 
                            class="img-fluid rounded"
                            style="width: 100%; height: 400px; object-fit: cover;"
                        >
                    </div>
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-7">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-4">
                        <!-- Badge Flash Sale -->
                        <div v-if="sanPham.flash_sale" class="mb-3">
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                🔥 FLASH SALE
                            </span>
                        </div>

                        <!-- Tên sản phẩm -->
                        <h1 class="fw-bold mb-3 text-primary">{{ sanPham.ten }}</h1>

                        <!-- Danh mục -->
                        <div class="mb-3">
                            <span class="badge bg-info fs-6">
                                <i class="bx bx-category me-1"></i>
                                {{ sanPham.danh_muc?.ten || 'Chưa phân loại' }}
                            </span>
                        </div>

                        <!-- Giá -->
                        <div class="mb-4">
                            <h2 class="text-danger fw-bold mb-2">
                                {{ formatCurrency(sanPham.gia) }}
                            </h2>
                            <small class="text-muted">Giá đã bao gồm VAT</small>
                        </div>

                        <!-- Thông tin sản phẩm -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Thông tin sản phẩm</h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-package me-2 text-primary fs-5"></i>
                                        <div>
                                            <small class="text-muted d-block">Số lượng còn lại</small>
                                            <strong class="text-primary fs-5">{{ sanPham.so_luong }} Nick</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" v-if="sanPham.username">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-user me-2 text-primary fs-5"></i>
                                        <div>
                                            <small class="text-muted d-block">Username</small>
                                            <strong>{{ sanPham.username }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" v-if="sanPham.co">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-globe me-2 text-primary fs-5"></i>
                                        <div>
                                            <small class="text-muted d-block">Quốc gia</small>
                                            <strong>{{ sanPham.co }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" v-if="sanPham.check_live">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-check-circle me-2 text-success fs-5"></i>
                                        <div>
                                            <small class="text-muted d-block">Trạng thái</small>
                                            <strong class="text-success">Đã check live</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-4" v-if="sanPham.mo_ta">
                            <h5 class="fw-bold mb-3">Mô tả</h5>
                            <p class="text-muted">{{ sanPham.mo_ta }}</p>
                        </div>

                       

                        <!-- Nút mua hàng -->
                        <div class="d-grid gap-2">
                            <button 
                                class="btn btn-primary btn-lg fw-bold py-3"
                                @click="muaNgay"
                                :disabled="sanPham.so_luong === 0 || !sanPham.trang_thai"
                            >
                                <i class="bx bx-cart me-2"></i>
                                {{ sanPham.so_luong === 0 ? 'HẾT HÀNG' : 'MUA NGAY' }}
                            </button>
                            <button 
                                class="btn btn-outline-secondary btn-lg fw-bold py-3"
                                @click="themVaoGio"
                                :disabled="sanPham.so_luong === 0 || !sanPham.trang_thai"
                            >
                                <i class="bx bx-heart me-2"></i>
                                THÊM VÀO YÊU THÍCH
                            </button>
                        </div>

                        <!-- Thông báo hết hàng -->
                        <div v-if="sanPham.so_luong === 0" class="alert alert-warning mt-3">
                            <i class="bx bx-info-circle me-2"></i>
                            Sản phẩm đã hết hàng. Vui lòng quay lại sau!
                        </div>

                        <!-- Thông báo tạm dừng -->
                        <div v-if="!sanPham.trang_thai" class="alert alert-secondary mt-3">
                            <i class="bx bx-info-circle me-2"></i>
                            Sản phẩm đang tạm dừng bán.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin bổ sung -->
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Thông tin bổ sung</h4>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bx bx-shield-check me-3 text-success fs-4"></i>
                                    <div>
                                        <h6 class="fw-bold">Bảo hành vĩnh viễn</h6>
                                        <p class="text-muted mb-0">Chúng tôi cam kết bảo hành sản phẩm vĩnh viễn</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bx bx-support me-3 text-primary fs-4"></i>
                                    <div>
                                        <h6 class="fw-bold">Hỗ trợ 24/7</h6>
                                        <p class="text-muted mb-0">Liên hệ Zalo: 03342211914 để được hỗ trợ</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bx bx-time me-3 text-warning fs-4"></i>
                                    <div>
                                        <h6 class="fw-bold">Giao hàng tự động</h6>
                                        <p class="text-muted mb-0">Nhận tài khoản ngay sau khi thanh toán</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bx bx-lock me-3 text-danger fs-4"></i>
                                    <div>
                                        <h6 class="fw-bold">An toàn & Bảo mật</h6>
                                        <p class="text-muted mb-0">Thông tin được mã hóa và bảo mật tuyệt đối</p>
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

export default {
    name: 'ChiTietSanPham',
    data() {
        return {
            sanPham: null,
            loading: true,
            error: null,
        };
    },
    async mounted() {
        await this.loadSanPham();
    },
    watch: {
        '$route'(to, from) {
            // Reload khi route thay đổi (ID khác)
            if (to.params.id !== from.params.id) {
                this.loadSanPham();
            }
        }
    },
    methods: {
        async loadSanPham() {
            this.loading = true;
            this.error = null;
            
            try {
                const productId = this.$route.params.id;
                console.log('Loading product ID:', productId);
                
                const response = await clientService.getProductById(productId);
                console.log('Product response:', response);
                
                if (response && response.data) {
                    if (response.data.success && response.data.data) {
                        this.sanPham = response.data.data;
                    } else if (response.data.data) {
                        this.sanPham = response.data.data;
                    } else if (!response.data.success) {
                        this.error = response.data.message || 'Không tìm thấy sản phẩm';
                    }
                } else {
                    this.error = 'Không tìm thấy sản phẩm';
                }
                
                console.log('Product loaded:', this.sanPham);
            } catch (error) {
                console.error('Error loading product:', error);
                if (error.response?.status === 404) {
                    this.error = 'Sản phẩm không tồn tại';
                } else {
                    this.error = error.response?.data?.message || 'Có lỗi xảy ra khi tải thông tin sản phẩm';
                }
            } finally {
                this.loading = false;
            }
        },
        muaNgay() {
            if (!this.sanPham) return;
            
            // Kiểm tra đăng nhập
            const token = localStorage.getItem('token');
            if (!token) {
                alert('Vui lòng đăng nhập để mua hàng!');
                this.$router.push('/client/dang-nhap');
                return;
            }
            
            // Chuyển đến trang thanh toán với sản phẩm đã chọn
            this.$router.push({
                path: '/client/thanh-toan',
                query: {
                    san_pham_id: this.sanPham.id,
                    so_luong: 1
                }
            });
        },
        themVaoGio() {
            // TODO: Implement add to wishlist/cart
            alert('Tính năng đang được phát triển!');
        },
        formatCurrency(value) {
            if (!value) return '0 ₫';
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        },
    }
};
</script>

<style scoped>
.card {
    border-radius: 15px;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.btn-lg {
    font-size: 1.1rem;
    padding: 0.75rem 2rem;
}

.badge {
    font-weight: 600;
    padding: 0.5rem 1rem;
}
</style>

