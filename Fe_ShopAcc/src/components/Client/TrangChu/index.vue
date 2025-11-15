<template>
    <div class="trang-chu">
        <!-- Notification Modal -->
        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content notification-modal">
                    <div class="modal-header notification-header border-0 pb-2">
                        <h5 class="modal-title notification-title" id="notificationModalLabel">
                            <i class="bx bx-bell text-primary me-2"></i>
                            Thông Báo Mới
                            <i class="bx bx-bell text-primary ms-2"></i>
                        </h5>
                    </div>
                    <div class="modal-body notification-body">
                        <div class="notification-badge mb-3">
                            <i class="bx bx-check-circle"></i>
                        </div>
                        <div class="notification-content">
                            <p class="mb-2">
                                <strong class="text-primary">SHOP HUU HIEU</strong>
                            </p>
                            <p class="mb-2">
                                <strong class="text-primary">Zalo : <a href="#" class="text-decoration-none">BẤM VÀO ĐÂY</a></strong>
                            </p>
                            <p class="mb-2">Chào Mừng Bạn Đến Với : <strong>HuuHieu.Shop</strong></p>
                            <p class="mb-2">Nạp ATM Tự Động ( Nếu Nạp ATM Duyệt Quá 5phút Thì Báo Ngay Admin )</p>
                            <p class="mb-2">Nạp ATM Đúng Hướng Dẫn</p>
                            <p class="mb-2">Shop Chúng Tôi Xin Cảm Ơn Bạn Và Chúc Bạn Một Ngày Tốt Lành !</p>
                            <p class="mb-0">Còn về hoàn tiền anh em liên hệ trực tiếp ZALO hoặc FACEBOOK Admin..!!</p>
                        </div>
                    </div>
                    <div class="modal-footer notification-footer border-0 pt-2">
                        <button type="button" class="btn btn-ok w-100 fw-bold" data-bs-dismiss="modal">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Section -->
        <div class="container-fluid px-3 px-lg-5 py-4">
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- <div class="card banner-main border-0 shadow-lg animate-fade-in position-relative overflow-hidden">
                        <div class="banner-bg-animation"></div>
                        <div class="banner-image-wrapper position-absolute top-0 start-0 w-100 h-100">
                            <img src="https://lh3.googleusercontent.com/gg-dl/ABS2GSnoHVxWRnzx5_GD9j0KDhc1x10kAbuy825cWV0QMRRppaJ8TaUkYhoCwZv7MrT6rDZiZYsPwbf2lvaIZub8yTmadVerxRgYPdeM7D4b7lwCHcd84VGrdbH12f497lKhXLPsF0NcEPm7Uw3ApkijNpTj_zbYZjGiB7QjQ8os2FYTGd_oKg=s1024-rj" 
                                 alt="HUUHIEU.SHOP Banner" 
                                 class="banner-background-image w-100 h-100"
                                 >
                            <div class="banner-image-overlay position-absolute top-0 start-0 w-100 h-100"></div>
                        </div>
                        <div class="card-body p-4 p-lg-5 position-relative z-1">
                            <div class="banner-content text-center mb-4">
                                <h1 class="banner-title animate-fade-in mb-3 text-white">HUUHIEU.SHOP</h1>
                                <p class="banner-subtitle animate-slide-up mb-2 text-white">UY TÍN - AN TOÀN - CHẤT LƯỢNG</p>
                                <p class="banner-warranty animate-pulse-glow mb-0 text-warning">BẢO HÀNH VĨNH VIỄN</p>
                            </div>
                            <div class="banner-overlay d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="admin-info-left animate-slide-left d-flex align-items-center gap-3">
                                    <div class="admin-avatar position-relative">
                                        <div class="avatar-glow">
                                            <img src="https://scontent.fdad3-6.fna.fbcdn.net/v/t39.30808-6/571333128_122213928200283057_6395676828632222698_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeHdVTC3V2DodFpwLN7HMN3N-ADvsNwX4Un4AO-w3BfhSaW7xPM68a6GLb0Kr1xEBlTnHr9dXPu1ROuObbQizklr&_nc_ohc=KB4-C12bshIQ7kNvwHKl92R&_nc_oc=AdnUt5Om9Rnz6sCn4rup79_XUyr4B8LpTi23268wjtkGQtuWjDvGfvJWhtpndvXQAss&_nc_zt=23&_nc_ht=scontent.fdad3-6.fna&_nc_gid=8yif-vxs5U5MXpXO2oUhpA&oh=00_AfgdtaI7rjP7XHXpjrU7FMKl6sF4l91vHJUdn1OmZYR40g&oe=6919C146" 
                                                 alt="avatar" 
                                                 class="admin-avatar-img rounded-circle">
                                        </div>
                                    </div>
                                    <div class="admin-text text-white">
                                        <div class="admin-name fw-bold fs-5 text-shadow-sm">HUU HIEU</div>
                                        <div class="admin-desc small text-white-50">SHOP ACC LIÊN QUÂN UY TÍN</div>
                                    </div>
                                </div>
                                <div class="contact-info-right animate-slide-right d-flex flex-column gap-2 mt-4">
                                    <div class="contact-item d-flex align-items-center gap-2">
                                        <span class="contact-icon fs-5">💬</span>
                                        <span class="fw-semibold text-white">Zalo 03342211914</span>
                                    </div>
                                    <div class="contact-item d-flex align-items-center gap-2">
                                        <span class="contact-icon fs-5">🏦</span>
                                        <span class="fw-semibold text-white">STK: VCB 1039319824</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
               <img src="../../../assets/Gemini_Generated_Image_51mbhv51mbhv51mb.png" alt="" class="img-fluid">
                </div>
                <div class="col-lg-4">
                    <div class="card top-deposit-box animate-fade-in-delay border-0 shadow-lg h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4 deposit-header">
                                <h3 class="card-title text-white mb-0 fw-bold fs-5 deposit-title">TOP Nạp Tháng 11</h3>
                                <div class="crown-icon fs-4">👑</div>
                            </div>
                            <ul class="list-group list-group-flush deposit-list mb-4">
                                <li class="list-group-item deposit-item animate-slide-in bg-transparent text-white border-bottom border-white border-opacity-25 px-0 py-3" style="animation-delay: 0.1s">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="rank fw-bold text-warning me-2">1.</span>
                                            <span class="name">Lep*****</span>
                                        </div>
                                        <span class="amount fw-bold text-success">+350.000 ₫</span>
                                    </div>
                                </li>
                                <li class="list-group-item deposit-item animate-slide-in bg-transparent text-white border-bottom border-white border-opacity-25 px-0 py-3" style="animation-delay: 0.2s">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="rank fw-bold text-warning me-2">2.</span>
                                            <span class="name">Min*****</span>
                                        </div>
                                        <span class="amount fw-bold text-success">+250.000 ₫</span>
                                    </div>
                                </li>
                                <li class="list-group-item deposit-item animate-slide-in bg-transparent text-white border-bottom border-white border-opacity-25 px-0 py-3" style="animation-delay: 0.3s">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="rank fw-bold text-warning me-2">3.</span>
                                            <span class="name">Tha*****</span>
                                        </div>
                                        <span class="amount fw-bold text-success">+200.000 ₫</span>
                                    </div>
                                </li>
                                <li class="list-group-item deposit-item animate-slide-in bg-transparent text-white border-0 px-0 py-3" style="animation-delay: 0.4s">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="rank fw-bold text-warning me-2">4.</span>
                                            <span class="name">Hie*****</span>
                                        </div>
                                        <span class="amount fw-bold text-success">+150.000 ₫</span>
                                    </div>
                                </li>
                            </ul>
                           <router-link to="/nap-tien">
                            <button class="btn btn-light w-100 deposit-btn animate-bounce-in fw-bold py-3 rounded-3 position-relative overflow-hidden">
                                <span class="position-relative">NẠP TIỀN NGAY</span>
                                <div class="btn-shine"></div>
                            </button>
                           </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Information -->
        <div class="container px-3 px-lg-5 pb-4">
            <div class="row">
                <div class="col-12">
                    <div class="card admin-box border-0 shadow-lg animate-fade-in-up">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3 admin-header">
                                <h3 class="text-primary mb-0 fw-bold fs-3">ADMIN: HUU HIEU</h3>
                                <div class="admin-badge">⚡ TỰ ĐỘNG</div>
                            </div>
                            <p class="admin-system text-secondary fs-5 mb-4">HỆ THỐNG NẠP TIỀN VÀ MUA ACC TỰ ĐỘNG</p>
                            <div class="admin-notice mb-4">
                                <div class="alert alert-warning alert-dismissible fade show animate-slide-in mb-3" role="alert" style="animation-delay: 0.1s">
                                    <div class="d-flex align-items-start gap-3">
                                        <span class="fs-4">⚠️</span>
                                        <div class="flex-grow-1">
                                            <strong>Lưu ý:</strong> Nếu nạp tiền không được cộng trong vòng 5 phút, vui lòng liên hệ Zalo admin!
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <div class="alert alert-info alert-dismissible fade show animate-slide-in" role="alert" style="animation-delay: 0.2s">
                                    <div class="d-flex align-items-start gap-3">
                                        <span class="fs-4">📹</span>
                                        <div class="flex-grow-1">
                                            <strong>Lưu ý:</strong> Quý khách vui lòng quay video khi đăng nhập để được hỗ trợ tốt nhất!
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- Product Sections -->
        <div class="container px-3 px-lg-5 pb-5">
            <!-- Sản phẩm Flash Sale -->
            <div v-if="flashSaleProducts.length > 0" class="product-section animate-fade-in-up mb-5">
                <h2 class="section-title d-flex align-items-center justify-content-center gap-3 mb-5">
                    <span class="sparkle sparkle-left fs-2">✨</span>
                    <span class="title-text fw-bold fs-2">FLASH SALE</span>
                    <span class="sparkle sparkle-right fs-2">✨</span>
                </h2>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4 col-xl-3" v-for="(product, index) in flashSaleProducts" :key="product.id" 
                         :style="{ animationDelay: (index * 0.1) + 's' }">
                        <div class="card product-card border-0 shadow h-100 position-relative">
                            <div class="hot-badge position-absolute top-0 end-0 d-flex align-items-center gap-2">
                                <span class="hot-text small fw-bold">FLASH SALE</span>
                                <div class="hot-fire">🔥</div>
                            </div>
                            <div class="card-body p-4">
                                <img :src="product.hinh_anh || 'https://thienlq.shop/uploads/03-11-2025/items/4/16b83bad-32aa-4e3c-81e7-441ee91a68a0.png'" 
                                     :alt="product.ten" 
                                     class="img-fluid" 
                                     style="width: 100%; height: 200px; object-fit: cover;">
                                <h3 class="product-name text-center fw-bold mb-3 mt-2">{{ product.ten }}</h3>
                                <div class="text-center mb-3">
                                    <span class="fw-bold text-danger fs-5">{{ formatCurrency(product.gia) }}</span>
                                </div>
                                <div class="product-stats d-flex justify-content-center align-items-center gap-3 mb-4 flex-wrap">
                                    <div class="stat-item d-flex flex-column align-items-center">
                                        <span class="stat-label small text-secondary text-uppercase">Còn</span>
                                        <span class="stat-value remaining fw-bold text-primary">{{ product.so_luong }} Nick</span>
                                    </div>
                                </div>
                                <router-link :to="`/client/san-pham/${product.id}`">
                                    <button class="btn btn-primary w-100 buy-btn fw-bold py-2">
                                        <span>MUA NGAY</span>
                                    </button>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sản phẩm Nổi Bật -->
            <div v-if="noiBatProducts.length > 0" class="product-section animate-fade-in-up mb-5">
                <h2 class="section-title d-flex align-items-center justify-content-center gap-3 mb-5">
                    <span class="sparkle sparkle-left fs-2">✨</span>
                    <span class="title-text fw-bold fs-2">SẢN PHẨM NỔI BẬT</span>
                    <span class="sparkle sparkle-right fs-2">✨</span>
                </h2>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4 col-xl-3" v-for="(product, index) in noiBatProducts" :key="product.id" 
                         :style="{ animationDelay: (index * 0.1) + 's' }">
                        <div class="card product-card border-0 shadow h-100 position-relative">
                            <div class="card-body p-4">
                                <img :src="product.hinh_anh || 'https://thienlq.shop/uploads/03-11-2025/items/4/16b83bad-32aa-4e3c-81e7-441ee91a68a0.png'" 
                                     :alt="product.ten" 
                                     class="img-fluid" 
                                     style="width: 100%; height: 200px; object-fit: cover;">
                                <h3 class="product-name text-center fw-bold mb-3 mt-2">{{ product.ten }}</h3>
                                <div class="text-center mb-3">
                                    <span class="fw-bold text-primary fs-5">{{ formatCurrency(product.gia) }}</span>
                                </div>
                                <div class="product-stats d-flex justify-content-center align-items-center gap-3 mb-4 flex-wrap">
                                    <div class="stat-item d-flex flex-column align-items-center">
                                        <span class="stat-label small text-secondary text-uppercase">Còn</span>
                                        <span class="stat-value remaining fw-bold text-primary">{{ product.so_luong }} Nick</span>
                                    </div>
                                </div>
                                <router-link :to="`/client/san-pham/${product.id}`">
                                    <button class="btn btn-primary w-100 buy-btn fw-bold py-2">
                                        <span>MUA NGAY</span>
                                    </button>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sản phẩm Mới -->
            <div v-if="newProducts.length > 0" class="product-section animate-fade-in-up mb-5">
                <h2 class="section-title d-flex align-items-center justify-content-center gap-3 mb-5">
                    <span class="sparkle sparkle-left fs-2">✨</span>
                    <span class="title-text fw-bold fs-2">SẢN PHẨM MỚI</span>
                    <span class="sparkle sparkle-right fs-2">✨</span>
                </h2>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4 col-xl-3" v-for="(product, index) in newProducts" :key="product.id" 
                         :style="{ animationDelay: (index * 0.1) + 's' }">
                        <div class="card product-card border-0 shadow h-100 position-relative">
                            <div class="card-body p-4">
                                <img :src="product.hinh_anh || 'https://thienlq.shop/uploads/03-11-2025/items/4/16b83bad-32aa-4e3c-81e7-441ee91a68a0.png'" 
                                     :alt="product.ten" 
                                     class="img-fluid" 
                                     style="width: 100%; height: 200px; object-fit: cover;">
                                <h3 class="product-name text-center fw-bold mb-3 mt-2">{{ product.ten }}</h3>
                                <div class="text-center mb-3">
                                    <span class="fw-bold text-primary fs-5">{{ formatCurrency(product.gia) }}</span>
                                </div>
                                <div class="product-stats d-flex justify-content-center align-items-center gap-3 mb-4 flex-wrap">
                                    <div class="stat-item d-flex flex-column align-items-center">
                                        <span class="stat-label small text-secondary text-uppercase">Còn</span>
                                        <span class="stat-value remaining fw-bold text-primary">{{ product.so_luong }} Nick</span>
                                    </div>
                                </div>
                                <router-link :to="`/client/san-pham/${product.id}`">
                                    <button class="btn btn-primary w-100 buy-btn fw-bold py-2">
                                        <span>MUA NGAY</span>
                                    </button>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh mục và sản phẩm theo danh mục -->
            <div v-for="danhMuc in danhMucs" :key="danhMuc.id" class="product-section animate-fade-in-up mb-5">
                <h2 class="section-title d-flex align-items-center justify-content-center gap-3 mb-5">
                    <span class="sparkle sparkle-left fs-2">✨</span>
                    <span class="title-text fw-bold fs-2">{{ danhMuc.ten }}</span>
                    <span class="sparkle sparkle-right fs-2">✨</span>
                </h2>
                <div class="row g-4" v-if="sanPhamByDanhMuc[danhMuc.id] && sanPhamByDanhMuc[danhMuc.id].length > 0">
                    <div class="col-md-6 col-lg-4 col-xl-3" v-for="(product, index) in sanPhamByDanhMuc[danhMuc.id]" :key="product.id" 
                         :style="{ animationDelay: (index * 0.05) + 's' }">
                        <div class="card product-card border-0 shadow h-100 position-relative">
                            <div class="card-body p-4">
                                <img :src="product.hinh_anh || 'https://thienlq.shop/uploads/03-11-2025/items/4/16b83bad-32aa-4e3c-81e7-441ee91a68a0.png'" 
                                     :alt="product.ten" 
                                     class="img-fluid" 
                                     style="width: 100%; height: 200px; object-fit: cover;">
                                <h3 class="product-name text-center fw-bold mb-3 mt-2">{{ product.ten }}</h3>
                                <div class="text-center mb-3">
                                    <span class="fw-bold text-primary fs-5">{{ formatCurrency(product.gia) }}</span>
                                </div>
                                <div class="product-stats d-flex justify-content-center align-items-center gap-3 mb-4 flex-wrap">
                                    <div class="stat-item d-flex flex-column align-items-center">
                                        <span class="stat-label small text-secondary text-uppercase">Còn</span>
                                        <span class="stat-value remaining fw-bold text-primary">{{ product.so_luong }} Nick</span>
                                    </div>
                                </div>
                                <router-link :to="`/client/san-pham/${product.id}`">
                                    <button class="btn btn-primary w-100 buy-btn fw-bold py-2">
                                        <span>MUA NGAY</span>
                                    </button>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center text-muted py-4">
                    <p>Chưa có sản phẩm nào trong danh mục này</p>
                </div>
            </div>
            <!-- Recent Purchases -->
        <div class="container ">
            <div class="row">
                <div class="col-12">
                    <div class="card recent-box border-0 shadow-lg animate-fade-in-up">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 recent-header pb-3 border-bottom">
                                <h3 class="text-dark mb-0 fw-bold fs-5">[SHOP] CHUYÊN BÁN ACC REG LIÊN QUÂN UY TÍN</h3>
                                
                            </div>
                            <div class="purchase-list d-flex flex-column gap-3">
                                <div class="purchase-item animate-slide-in p-3 rounded" style="animation-delay: 0.1s">
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span class="purchase-time text-secondary small">cách đây 12 giờ trước</span>
                                        <span class="purchase-info">đã mua tài khoản <strong class="text-primary">#25099665</strong> - Giá <strong class="price text-success">294.000 ₫</strong></span>
                                    </div>
                                </div>
                                <div class="purchase-item animate-slide-in p-3 rounded" style="animation-delay: 0.2s">
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span class="purchase-time text-secondary small">cách đây 15 giờ trước</span>
                                        <span class="purchase-info">đã mua tài khoản <strong class="text-primary">#25099664</strong> - Giá <strong class="price text-success">350.000 ₫</strong></span>
                                    </div>
                                </div>
                                <div class="purchase-item animate-slide-in p-3 rounded" style="animation-delay: 0.3s">
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span class="purchase-time text-secondary small">cách đây 18 giờ trước</span>
                                        <span class="purchase-info">đã mua tài khoản <strong class="text-primary">#25099663</strong> - Giá <strong class="price text-success">280.000 ₫</strong></span>
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
import { clientService } from '@/services/clientService';

export default {
    name: 'TrangChu',
    data() {
        return {
            loading: false,
            danhMucs: [],
            flashSaleProducts: [],
            noiBatProducts: [],
            newProducts: [],
            sanPhamByDanhMuc: {},
        }
    },
    async mounted() {
        // Hiển thị modal thông báo khi vào trang chủ
        // Sử dụng Bootstrap global từ CDN
        if (window.bootstrap) {
            const notificationModal = new window.bootstrap.Modal(document.getElementById('notificationModal'));
            notificationModal.show();
        } else {
            // Fallback nếu bootstrap chưa load
            setTimeout(() => {
                if (window.bootstrap) {
                    const notificationModal = new window.bootstrap.Modal(document.getElementById('notificationModal'));
                    notificationModal.show();
                }
            }, 500);
        }
        
        // Load dữ liệu từ API
        await this.loadData();
    },
    methods: {
        async loadData() {
            this.loading = true;
            try {
                // Load danh mục
                await this.loadDanhMucs();
                
                // Load sản phẩm flash sale, nổi bật, mới
                await Promise.all([
                    this.loadFlashSale(),
                    this.loadNoiBat(),
                    this.loadNew(),
                ]);
                
                // Load sản phẩm theo từng danh mục
                await this.loadSanPhamByDanhMuc();
            } catch (error) {
                console.error('Error loading data:', error);
                console.error('Error details:', error.response?.data || error.message);
                // Hiển thị thông báo lỗi cho user
                alert('Có lỗi xảy ra khi tải dữ liệu. Vui lòng kiểm tra console để biết thêm chi tiết.');
            } finally {
                this.loading = false;
            }
        },
        async loadDanhMucs() {
            try {
                const response = await clientService.getCategories();
                console.log('Danh mucs response:', response);
                console.log('Danh mucs response.data:', response.data);
                
                // Kiểm tra nhiều format response có thể có
                if (response && response.data) {
                    if (response.data.success && response.data.data) {
                        this.danhMucs = Array.isArray(response.data.data) ? response.data.data : [];
                    } else if (Array.isArray(response.data)) {
                        this.danhMucs = response.data;
                    } else if (response.data.data) {
                        this.danhMucs = Array.isArray(response.data.data) ? response.data.data : [];
                    }
                }
                console.log('Danh mucs loaded:', this.danhMucs);
                console.log('Danh mucs count:', this.danhMucs.length);
            } catch (error) {
                console.error('Error loading danh mucs:', error);
                console.error('Error response:', error.response);
                console.error('Error details:', error.response?.data || error.message);
                // Silent fail - không hiển thị alert
            }
        },
        async loadFlashSale() {
            try {
                const response = await clientService.getFlashSale();
                console.log('Flash sale response:', response.data);
                if (response.data) {
                    if (response.data.success && response.data.data) {
                        this.flashSaleProducts = response.data.data;
                    } else if (Array.isArray(response.data)) {
                        this.flashSaleProducts = response.data;
                    } else if (response.data.data) {
                        this.flashSaleProducts = response.data.data;
                    }
                }
                console.log('Flash sale products loaded:', this.flashSaleProducts);
            } catch (error) {
                console.error('Error loading flash sale:', error);
                console.error('Error details:', error.response?.data || error.message);
            }
        },
        async loadNoiBat() {
            try {
                const response = await clientService.getNoiBat();
                console.log('Noi bat response:', response.data);
                if (response.data) {
                    if (response.data.success && response.data.data) {
                        this.noiBatProducts = response.data.data;
                    } else if (Array.isArray(response.data)) {
                        this.noiBatProducts = response.data;
                    } else if (response.data.data) {
                        this.noiBatProducts = response.data.data;
                    }
                }
                console.log('Noi bat products loaded:', this.noiBatProducts);
            } catch (error) {
                console.error('Error loading noi bat:', error);
                console.error('Error details:', error.response?.data || error.message);
            }
        },
        async loadNew() {
            try {
                const response = await clientService.getNew();
                console.log('New products response:', response.data);
                if (response.data) {
                    if (response.data.success && response.data.data) {
                        this.newProducts = response.data.data;
                    } else if (Array.isArray(response.data)) {
                        this.newProducts = response.data;
                    } else if (response.data.data) {
                        this.newProducts = response.data.data;
                    }
                }
                console.log('New products loaded:', this.newProducts);
            } catch (error) {
                console.error('Error loading new products:', error);
                console.error('Error details:', error.response?.data || error.message);
            }
        },
        async loadSanPhamByDanhMuc() {
            // Load sản phẩm cho mỗi danh mục
            if (!this.danhMucs || this.danhMucs.length === 0) {
                console.log('No danh mucs to load products for');
                return;
            }
            
            for (const danhMuc of this.danhMucs) {
                try {
                    // Tạm thời dùng search với danh mục
                    const response = await clientService.search(danhMuc.ten);
                    console.log(`Products for danh muc ${danhMuc.id} (${danhMuc.ten}):`, response.data);
                    
                    let products = [];
                    if (response.data) {
                        if (response.data.success && response.data.data) {
                            products = response.data.data;
                        } else if (Array.isArray(response.data)) {
                            products = response.data;
                        } else if (response.data.data) {
                            products = response.data.data;
                        }
                    }
                    
                    // Lọc sản phẩm theo danh mục
                    const filteredProducts = products.filter(p => p.danh_muc_id === danhMuc.id).slice(0, 8);
                    this.$set(this.sanPhamByDanhMuc, danhMuc.id, filteredProducts);
                    console.log(`Loaded ${filteredProducts.length} products for danh muc ${danhMuc.id}`);
                } catch (error) {
                    console.error(`Error loading products for danh muc ${danhMuc.id}:`, error);
                    console.error('Error details:', error.response?.data || error.message);
                }
            }
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
.trang-chu {
    width: 100%;
    background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
    padding-bottom: 2rem;
    min-height: 100vh;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulseGlow {
    0%, 100% {
        text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    }
    50% {
        text-shadow: 0 0 20px rgba(255, 215, 0, 0.8), 0 0 30px rgba(255, 215, 0, 0.6);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.8);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

@keyframes sparkle {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(1.2);
    }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out;
}

.animate-fade-in-delay {
    animation: fadeIn 1s ease-out 0.3s both;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out 0.2s both;
}

.animate-slide-left {
    animation: slideLeft 0.8s ease-out 0.4s both;
}

.animate-slide-right {
    animation: slideRight 0.8s ease-out 0.4s both;
}

.animate-slide-in {
    animation: slideIn 0.6s ease-out both;
}

.animate-fade-in-up {
    animation: slideUp 0.8s ease-out both;
}

.animate-pulse-glow {
    animation: pulseGlow 2s ease-in-out infinite;
}

.animate-bounce-in {
    animation: bounceIn 0.6s ease-out;
}

/* Banner Section */
.banner-main {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%) !important;
    border-radius: 20px !important;
    position: relative;
    min-height: 400px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4) !important;
}

.banner-image-wrapper {
    z-index: 0;
}

.banner-background-image {
    object-fit: cover;
    object-position: center;
    filter: brightness(0.85) contrast(1.1);
    transition: transform 0.5s ease;
    width: 100%;
    height: 100%;
}

.banner-main:hover .banner-background-image {
    transform: scale(1.05);
}

.banner-image-overlay {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.3) 0%, rgba(102, 126, 234, 0.4) 50%, rgba(118, 75, 162, 0.4) 100%);
    z-index: 1;
}

.banner-bg-animation {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
    background-size: 200% 200%;
    animation: shimmer 3s infinite;
    pointer-events: none;
    z-index: 2;
}

.banner-content {
    position: relative;
    z-index: 1;
}

.banner-title {
    font-size: clamp(1.8rem, 5vw, 2.8rem);
    font-weight: 800;
    color: #ffffff;
    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
    letter-spacing: 2px;
}

.banner-subtitle {
    font-size: clamp(1rem, 2vw, 1.3rem);
    color: #ffffff;
    font-weight: 600;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.banner-warranty {
    font-size: clamp(0.9rem, 1.5vw, 1.2rem);
    color: #ffd700;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.banner-overlay {
    position: relative;
    z-index: 1;
}

.admin-info-left {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 1.2rem;
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.admin-info-left:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.admin-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    border: 3px solid rgba(255, 255, 255, 0.5);
}

.admin-avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.admin-avatar:hover .admin-avatar-img {
    transform: scale(1.1);
}

.avatar-glow {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255, 215, 0, 0.4) 0%, transparent 70%);
    animation: pulseGlow 2s ease-in-out infinite;
    z-index: 1;
    pointer-events: none;
}

.contact-item {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    color: #ffffff;
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.contact-item:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateX(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
}

/* Top Deposit Box */
.top-deposit-box {
    background: linear-gradient(135deg, #1459da 0%, #9da0a3 100%) !important;
    border-radius: 20px !important;
    position: relative;
    overflow: hidden;
}

.top-deposit-box::before {
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

.deposit-header {
    position: relative;
    z-index: 1;
}

.deposit-title {
    font-size: 1.3rem;
}

.crown-icon {
    animation: float 3s ease-in-out infinite;
}

.deposit-list {
    position: relative;
    z-index: 1;
}

.deposit-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.deposit-item:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    padding-left: 0.5rem !important;
    border-radius: 6px;
    transform: translateX(5px);
}

.deposit-btn {
    position: relative;
    z-index: 1;
    background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%) !important;
    color: #0d6efd !important;
    transition: all 0.3s ease;
    border: none !important;
}

.btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    transition: left 0.5s;
}

.deposit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.deposit-btn:hover .btn-shine {
    left: 100%;
}

/* Admin Section */
.admin-box {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    border-radius: 20px !important;
    border: 1px solid rgba(13, 110, 253, 0.1) !important;
}

.admin-badge {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    color: #ffffff;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
}

.admin-system {
    color: #666;
    font-size: 1.1rem;
    font-weight: 600;
}

/* Bootstrap Alert Customization */
.alert {
    border-radius: 12px;
    border-left: 4px solid;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.alert::before {
    content: '';
    position: absolute;
    left: -100%;
    top: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
}

.alert:hover::before {
    left: 100%;
}

.alert:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left-color: #ffc107;
    color: #856404;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    border-left-color: #17a2b8;
    color: #0c5460;
}

.alert .btn-close {
    opacity: 0.5;
    transition: opacity 0.3s ease;
}

.alert .btn-close:hover {
    opacity: 1;
}

.admin-link-btn {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    color: #ffffff;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
}

.admin-link-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
    color: #ffffff;
}

.btn-notification {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%) !important;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3) !important;
}

.btn-zalo {
    background: linear-gradient(135deg, #0068ff 0%, #0052cc 100%) !important;
}

.btn-facebook {
    background: linear-gradient(135deg, #1877f2 0%, #1565c0 100%) !important;
    box-shadow: 0 4px 15px rgba(24, 119, 242, 0.3) !important;
}

/* Recent Purchases */
.recent-box {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    border-radius: 20px !important;
    border: 1px solid rgba(13, 110, 253, 0.1) !important;
}

.live-indicator {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    color: #ffffff;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.live-dot {
    width: 8px;
    height: 8px;
    background: #ffffff;
    border-radius: 50%;
    animation: pulseGlow 1.5s ease-in-out infinite;
}

.purchase-item {
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.purchase-item:hover {
    background: #e9ecef;
    border-left-color: #0d6efd;
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.price {
    color: #28a745 !important;
    font-weight: 700;
}

/* Product Sections */
.product-section {
    margin-bottom: 4rem;
}

.section-title {
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 800;
    color: #333;
}

.title-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.sparkle {
    animation: sparkle 2s ease-in-out infinite;
}

.sparkle-left {
    animation-delay: 0s;
}

.sparkle-right {
    animation-delay: 1s;
}

.product-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    border-radius: 20px !important;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    animation: slideUp 0.6s ease-out both;
}

.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
    z-index: 1;
}

.product-card:hover::before {
    left: 100%;
}

.product-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 50px rgba(102, 126, 234, 0.3) !important;
}

.card-premium {
    border: 2px solid #ffd700 !important;
    background: linear-gradient(135deg, #fffef0 0%, #ffffff 100%) !important;
}

.card-premium::after {
    content: '👑';
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 1.5rem;
    animation: float 3s ease-in-out infinite;
    z-index: 10;
}

.product-image {
    width: 100%;
    height: 180px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.image-glow {
    position: absolute;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
    animation: pulseGlow 3s ease-in-out infinite;
}

.product-icon {
    font-size: 5rem;
    position: relative;
    z-index: 1;
    filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.3));
}

.product-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
}

.product-stats {
    gap: 0.75rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #666;
    text-transform: uppercase;
}

.stat-value {
    font-size: 0.95rem;
    font-weight: 700;
}

.stat-value.sold {
    color: #28a745;
}

.stat-value.remaining {
    color: #0d6efd;
}

.stat-divider {
    color: #dee2e6;
    font-weight: 300;
}

.buy-btn {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
    color: #ffffff !important;
    border: none !important;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    position: relative;
    overflow: hidden;
}

.buy-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.buy-btn:hover::before {
    width: 300px;
    height: 300px;
}

.buy-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
}

.buy-btn span {
    position: relative;
    z-index: 1;
}

.view-all-btn {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    color: #333 !important;
    border: 2px solid #dee2e6 !important;
    transition: all 0.3s ease;
}

.view-all-btn:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%) !important;
    border-color: #0d6efd !important;
    color: #0d6efd !important;
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.btn-arrow {
    transition: transform 0.3s ease;
}

.view-all-btn:hover .btn-arrow {
    transform: translateX(5px);
}

.hot-sale {
    border: 2px solid #ff6b6b !important;
    background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%) !important;
}

.hot-badge {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    color: #ffffff;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
    z-index: 10;
    animation: pulseGlow 2s ease-in-out infinite;
}

.hot-fire {
    animation: float 2s ease-in-out infinite;
}

/* Notification Modal */
.notification-modal {
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.notification-header {
    background: #ffffff;
    padding: 1.5rem 1.5rem 0.5rem;
}

.notification-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2c3e50;
    width: 100%;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-title i {
    font-size: 1.5rem;
    animation: ring 2s ease-in-out infinite;
}

@keyframes ring {
    0%, 100% {
        transform: rotate(0deg);
    }
    10%, 30% {
        transform: rotate(-10deg);
    }
    20%, 40% {
        transform: rotate(10deg);
    }
    50% {
        transform: rotate(0deg);
    }
}

.notification-body {
    background: #ffffff;
    padding: 1rem 1.5rem;
}

.notification-badge {
    text-align: center;
}

.notification-badge i {
    font-size: 3rem;
    color: #0d6efd;
    background: rgba(13, 110, 253, 0.1);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
    }
}

.notification-content {
    color: #2c3e50;
    font-size: 0.95rem;
    line-height: 1.8;
}

.notification-content strong {
    color: #0d6efd;
}

.notification-content a {
    color: #0d6efd;
    transition: all 0.3s ease;
}

.notification-content a:hover {
    color: #0b5ed7;
    text-decoration: underline !important;
}

.notification-footer {
    background: #ffffff;
    padding: 0.5rem 1.5rem 1.5rem;
}

.btn-ok {
    background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
    color: #ffffff;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-size: 1.1rem;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
}

.btn-ok:hover {
    background: linear-gradient(135deg, #5a32a3 0%, #4a2a8a 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(111, 66, 193, 0.4);
    color: #ffffff;
}

.btn-ok:active {
    transform: translateY(0);
}

/* Modal Backdrop */
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

/* Responsive */
@media (max-width: 991px) {
    .top-deposit-box {
        width: 100% !important;
    }
    
    .notification-title {
        font-size: 1.1rem;
    }
    
    .notification-content {
        font-size: 0.85rem;
    }
    
    .btn-ok {
        font-size: 1rem;
        padding: 0.65rem 1.5rem;
    }
}
</style>