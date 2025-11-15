<template>
    <div class="section-authentication-signin">
        <!-- Animated Background -->
        <div class="animated-bg">
            <div class="floating-shape" style="width: 300px; height: 300px; top: 10%; left: 5%; animation-delay: 0s;"></div>
            <div class="floating-shape" style="width: 200px; height: 200px; top: 60%; right: 10%; animation-delay: 2s;"></div>
            <div class="floating-shape" style="width: 150px; height: 150px; bottom: 20%; left: 20%; animation-delay: 4s;"></div>
        </div>

        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-5">
                            <!-- Logo/Header -->
                            <div class="text-center mb-4">
                                <div class="logo-wrapper mb-3">
                                    <i class="bx bx-shield-quarter fs-1 text-primary"></i>
                                </div>
                                <h2 class="fw-bold mb-2">Đăng Nhập Admin</h2>
                                <p class="text-muted">Vui lòng đăng nhập để tiếp tục</p>
                            </div>

                            <!-- Error Message -->
                            <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bx bx-error-circle me-2"></i>
                                {{ error }}
                                <button type="button" class="btn-close" @click="error = ''"></button>
                            </div>

                            <!-- Success Message -->
                            <div v-if="success" class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bx bx-check-circle me-2"></i>
                                {{ success }}
                                <button type="button" class="btn-close" @click="success = ''"></button>
                            </div>

                            <!-- Login Form -->
                            <form @submit.prevent="handleLogin">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="bx bx-envelope me-2"></i>Email
                                    </label>
                                    <input
                                        type="email"
                                        class="form-control form-control-lg"
                                        id="email"
                                        v-model="formData.email"
                                        placeholder="Nhập email của bạn"
                                        required
                                        :disabled="loading"
                                    >
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="bx bx-lock me-2"></i>Mật khẩu
                                    </label>
                                    <div class="input-group">
                                        <input
                                            :type="showPassword ? 'text' : 'password'"
                                            class="form-control form-control-lg"
                                            id="password"
                                            v-model="formData.password"
                                            placeholder="Nhập mật khẩu của bạn"
                                            required
                                            :disabled="loading"
                                        >
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            @click="togglePasswordVisibility"
                                            :disabled="loading"
                                        >
                                            <i :class="showPassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-4 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            id="remember"
                                            v-model="formData.remember"
                                            :disabled="loading"
                                        >
                                        <label class="form-check-label" for="remember">
                                            Ghi nhớ đăng nhập
                                        </label>
                                    </div>
                                    <a href="#" class="text-decoration-none small">Quên mật khẩu?</a>
                                </div>

                                <div class="d-grid">
                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-lg fw-bold"
                                        :disabled="loading"
                                    >
                                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                                        <i v-else class="bx bx-log-in me-2"></i>
                                        {{ loading ? 'Đang đăng nhập...' : 'Đăng Nhập' }}
                                    </button>
                                </div>
                            </form>

                            <!-- Footer -->
                            <div class="text-center mt-4">
                                <p class="text-muted small mb-0">
                                    <i class="bx bx-shield-alt-2 me-1"></i>
                                    Bảo mật và an toàn
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { adminAuthService } from '@/services/adminAuthService';

export default {
    name: 'AdminDangNhap',
    data() {
        return {
            formData: {
                email: '',
                password: '',
                remember: false
            },
            loading: false,
            error: '',
            success: '',
            showPassword: false
        };
    },
    methods: {
        togglePasswordVisibility() {
            this.showPassword = !this.showPassword;
        },
        async handleLogin() {
            this.loading = true;
            this.error = '';
            this.success = '';

            try {
                const response = await adminAuthService.login(
                    this.formData.email,
                    this.formData.password
                );

                if (response.success) {
                    this.success = 'Đăng nhập thành công!';
                    
                    // Dispatch event để các component khác biết admin đã đăng nhập
                    window.dispatchEvent(new Event('admin-logged-in'));
                    
                    // Chuyển đến trang admin sau 1 giây
                    setTimeout(() => {
                        const redirect = this.$route.query.redirect || '/admin/danh-muc';
                        this.$router.push(redirect);
                    }, 1000);
                }
            } catch (error) {
                console.error('Login error:', error);
                this.error = error.response?.data?.message || error.message || 'Đăng nhập thất bại. Vui lòng thử lại.';
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

<style scoped>
.section-authentication-signin {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
}

.animated-bg {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 0;
}

.floating-shape {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 20s infinite ease-in-out;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

.card {
    position: relative;
    z-index: 1;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
}

.logo-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
}
</style>
