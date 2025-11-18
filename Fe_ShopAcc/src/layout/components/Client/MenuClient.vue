<template>
    <div class="menu-client-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <!-- Logo/Brand -->
                <router-link class="navbar-brand logo-section" to="/">
                    <div class="logo-container">
                        <div class="logo-icon-wrapper">
                            <div class="logo-character">🐰</div>
                        </div>
                        <div class="logo-text-wrapper">
                            <div class="logo-main-text">HuuHieu.Shop</div>
                        </div>
                    </div>
                </router-link>

                <!-- Menu items -->
                <div class="navbar-collapse">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/" active-class="active">
                                Trang Chủ
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/client/lich-su-mua-hang" active-class="active">
                                Lịch Sử Mua Nick
                            </router-link>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link " href="#">
                                Dịch Vụ Khác
                            </a>
                            
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/client/profile" active-class="active">
                                Tài Khoản
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/client/nap-tien" active-class="active">
                                Nạp Tiền
                            </router-link>
                        </li>
                    </ul>
                    <!-- Right side: Language & User -->
                    <ul class="navbar-nav ms-auto right-menu">
                        <li class="nav-item">
                            <a class="nav-link language-link" href="#">
                                🌐
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link user-profile-link dropdown-toggle" href="#" @click.prevent>
                                <div class="user-avatar">{{ userInitial }}</div>
                                <div class="user-info">
                                    <div class="user-name">{{ userName }}</div>
                                    <div class="user-balance">{{ formatCurrency(userBalance) }}</div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <template v-if="isLoggedIn">
                                    <li>
                                        <router-link class="dropdown-item" to="/client/profile">
                                            <i class="bx bx-user me-2"></i>
                                            Thông tin tài khoản
                                        </router-link>
                                    </li>
                                    <li>
                                        <router-link class="dropdown-item" to="/client/lich-su-mua-hang">
                                            <i class="bx bx-history me-2"></i>
                                            Lịch sử mua hàng
                                        </router-link>
                                    </li>
                                    <li>
                                        <router-link class="dropdown-item" to="/client/nap-tien">
                                            <i class="bx bx-wallet me-2"></i>
                                            Nạp tiền
                                        </router-link>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" @click.prevent="handleLogout">
                                            <i class="bx bx-log-out me-2"></i>
                                            Đăng xuất
                                        </a>
                                    </li>
                                </template>
                                <template v-else>
                                    <li>
                                        <router-link class="dropdown-item" to="/client/dang-nhap">
                                            <i class="bx bx-log-in me-2"></i>
                                            Đăng Nhập
                                        </router-link>
                                    </li>
                                    <li>
                                        <router-link class="dropdown-item" to="/client/dang-ky">
                                            <i class="bx bx-user-plus me-2"></i>
                                            Đăng Ký
                                        </router-link>
                                    </li>
                                </template>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
import api from '@/services/api';
import { authService } from '@/services/authService';

export default {
    name: 'MenuClient',
    data() {
        return {
            user: null,
            loading: false,
        };
    },
    computed: {
        isLoggedIn() {
            const token = localStorage.getItem('token');
            return !!token && !!this.user;
        },
        userName() {
            if (this.user && this.user.ten) {
                return this.user.ten;
            }
            return 'Khách';
        },
        userInitial() {
            if (this.user && this.user.ten) {
                // Lấy chữ cái đầu tiên của tên
                return this.user.ten.charAt(0).toUpperCase();
            }
            return 'K';
        },
        userBalance() {
            if (this.user && this.user.so_du !== undefined) {
                return this.user.so_du || 0;
            }
            return 0;
        },
    },
    mounted() {
        this.loadUserInfo();
        
        // Lắng nghe sự kiện đăng nhập thành công
        window.addEventListener('user-logged-in', this.loadUserInfo);
        window.addEventListener('user-updated', this.loadUserInfo);
        
        // Kiểm tra định kỳ để cập nhật thông tin user (mỗi 30 giây)
        this.userRefreshInterval = setInterval(() => {
            if (this.isLoggedIn) {
                this.refreshUserInfo();
            }
        }, 30000);
    },
    beforeUnmount() {
        window.removeEventListener('user-logged-in', this.loadUserInfo);
        window.removeEventListener('user-updated', this.loadUserInfo);
        if (this.userRefreshInterval) {
            clearInterval(this.userRefreshInterval);
        }
    },
    methods: {
        loadUserInfo() {
            const userStr = localStorage.getItem('user');
            if (userStr) {
                try {
                    this.user = JSON.parse(userStr);
                } catch (e) {
                    console.error('Error parsing user data:', e);
                    this.user = null;
                }
            } else {
                this.user = null;
            }
        },
        async refreshUserInfo() {
            if (!this.isLoggedIn) return;
            
            try {
                // Get user ID from localStorage
                const userStr = localStorage.getItem('user');
                const user = userStr ? JSON.parse(userStr) : null;
                const clientId = user?.id;

                if (!clientId) {
                    this.user = null;
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
                if (response.data.success && response.data.data) {
                    this.user = response.data.data;
                    localStorage.setItem('user', JSON.stringify(this.user));
                }
            } catch (error) {
                console.error('Error refreshing user info:', error);
                // Không tự động logout ở đây, để interceptor xử lý
                // Chỉ cập nhật state nếu token không còn hợp lệ và đang ở trang yêu cầu auth
                if (error.response?.status === 401) {
                    const currentPath = window.location.pathname;
                    const requiresAuth = currentPath.includes('/client/profile') ||
                                        currentPath.includes('/client/don-hang') ||
                                        currentPath.includes('/client/lich-su-mua-hang') ||
                                        currentPath.includes('/client/nap-tien') ||
                                        currentPath.includes('/client/chi-tiet-don-hang');
                    
                    // Chỉ xóa user info nếu đang ở trang yêu cầu authentication
                    if (requiresAuth) {
                        this.user = null;
                        localStorage.removeItem('user');
                        localStorage.removeItem('token');
                    }
                }
            }
        },
        async handleLogout() {
            if (!confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                return;
            }

            try {
                await authService.logout();
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                // Xóa thông tin user và token
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                this.user = null;
                
                // Chuyển về trang chủ
                this.$router.push('/');
                
                // Reload để cập nhật menu
                window.location.reload();
            }
        },
        formatCurrency(value) {
            if (!value && value !== 0) return '0 ₫';
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        },
    },
    watch: {
        // Watch route changes để refresh user info khi cần
        '$route'() {
            if (this.isLoggedIn) {
                // Refresh user info khi chuyển trang (để cập nhật số dư sau khi nạp tiền, mua hàng, etc.)
                this.refreshUserInfo();
            }
        }
    }
}
</script>

<style scoped>
.menu-client-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    border-bottom: 1px solid #f0f0f0;
}

.navbar {
    padding: 0.75rem 2rem;
    background: #ffffff !important;
}

/* Logo Section */
.logo-section {
    padding: 0 !important;
    margin-right: 3rem;
    text-decoration: none;
    transition: opacity 0.2s ease;
}

.logo-section:hover {
    opacity: 0.8;
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.logo-icon-wrapper {
    width: 42px;
    height: 42px;
    background: #000000;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.logo-character {
    font-size: 22px;
}

.logo-text-wrapper {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.logo-main-text {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.2;
    letter-spacing: -0.3px;
}

/* Navigation Menu */
.navbar-nav {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-item {
    position: relative;
}

.nav-link {
    color: #333 !important;
    font-size: 15px;
    font-weight: 500;
    padding: 0.6rem 1.2rem !important;
    text-decoration: none;
    transition: all 0.2s ease;
    border-radius: 6px;
    white-space: nowrap;
    position: relative;
}

.nav-link:hover {
    color: #000 !important;
    background-color: #f8f9fa;
}

.nav-link.active {
    color: #0d6efd !important;
    background-color: transparent;
}

.nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 30px;
    height: 2px;
    background: #0d6efd;
    border-radius: 2px;
}

/* Dropdown */
.dropdown {
    position: relative;
}

.dropdown-toggle {
    cursor: pointer;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    display: none;
    border: none;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-top: 0;
    padding: 0.5rem 0;
    min-width: 200px;
    background: #ffffff;
    z-index: 1000;
}

.dropdown:hover .dropdown-menu,
.dropdown-menu:hover {
    display: block;
}

/* Tạo vùng kết nối giữa toggle và menu để giữ hover state */
.dropdown::before {
    content: '';
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    height: 0.5rem;
    background: transparent;
    z-index: 999;
}

.dropdown:hover::before {
    display: block;
}

.dropdown-item {
    padding: 0.65rem 1.25rem;
    color: #333;
    text-decoration: none;
    display: block;
    transition: all 0.2s ease;
    font-size: 14px;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: #0d6efd;
}

.dropdown-menu-end {
    right: 0;
    left: auto;
}

.dropdown-menu-end::before {
    left: auto;
    right: 0;
}

/* Right Menu */
.right-menu {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-left: auto;
}

.language-link {
    color: #666 !important;
    font-size: 18px;
    padding: 0.5rem 0.75rem !important;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.language-link:hover {
    background-color: #f8f9fa;
    color: #333 !important;
}

.user-profile-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.4rem 0.8rem !important;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.user-profile-link:hover {
    background-color: #f8f9fa;
}

.user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.user-name {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    line-height: 1.2;
}

.user-balance {
    font-size: 12px;
    color: #666;
    line-height: 1.2;
}

/* Responsive */
@media (max-width: 991px) {
    .navbar-collapse {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e4e4e4;
    }

    .nav-item {
        margin: 0.25rem 0;
    }

    .nav-link {
        width: 100%;
        justify-content: flex-start;
    }

    .right-menu {
        margin-left: 0;
        width: 100%;
        justify-content: space-between;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e4e4e4;
    }

    .dropdown-menu {
        position: static !important;
        float: none;
        width: 100%;
        margin-top: 0;
        border: none;
        box-shadow: none;
        background-color: #f8f9fa;
        display: block;
    }
}
</style>
