<template>
    <div class="section-authentication-signup d-flex align-items-center justify-content-center min-vh-100 py-5 position-relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="animated-bg">
            <div class="floating-shape shape-1"></div>
            <div class="floating-shape shape-2"></div>
            <div class="floating-shape shape-3"></div>
            <div class="floating-shape shape-4"></div>
            <div class="floating-shape shape-5"></div>
            <div class="floating-shape shape-6"></div>
        </div>
        <div class="wave wave-1"></div>
        <div class="wave wave-2"></div>
        <div class="wave wave-3"></div>
        <!-- Side Borders -->
        <div class="side-border side-border-left"></div>
        <div class="side-border side-border-right"></div>
        <div class="container position-relative z-1">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                    <!-- Back to Home Button -->
                    <div class="text-start mb-3">
                        <router-link to="/" class="back-home-link text-decoration-none text-dark">
                            <i class="bx bx-home me-2"></i>
                            <span>Quay về trang chủ</span>
                        </router-link>
                    </div>

                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4 p-md-4 p-lg-4">
                            <div class="text-center mb-4">
                                <h1 class="signup-title mb-2">ĐĂNG KÝ TÀI KHOẢN</h1>
                                <p class="text-muted mb-0">
                                    Bạn đã có tài khoản?
                                    <router-link to="/client/dang-nhap" class="login-link text-decoration-none fw-semibold">
                                        Đăng nhập tại đây
                                    </router-link>
                                </p>
                            </div>

                            <!-- Error Alert -->
                            <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Lỗi!</strong> {{ error }}
                                <button type="button" class="btn-close" @click="error = ''"></button>
                            </div>

                            <!-- Success Alert -->
                            <div v-if="success" class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Thành công!</strong> {{ success }}
                                <button type="button" class="btn-close" @click="success = ''"></button>
                            </div>
                            
                            <form @submit.prevent="handleRegister" class="signup-form">
                                <div class="row g-3">
                                    <div class="col-sm-12">
                                        <label for="ten" class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bx bx-user text-muted"></i>
                                            </span>
                                            <input 
                                                type="text" 
                                                id="ten"
                                                class="form-control border-start-0" 
                                                placeholder="Họ và tên"
                                                v-model="formData.ten"
                                                required
                                            >
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <label for="tai_khoan" class="form-label fw-semibold">Tài khoản <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bx bx-user-circle text-muted"></i>
                                            </span>
                                            <input 
                                                type="text" 
                                                id="tai_khoan"
                                                class="form-control border-start-0" 
                                                placeholder="Tài khoản"
                                                v-model="formData.tai_khoan"
                                                required
                                            >
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bx bx-envelope text-muted"></i>
                                            </span>
                                            <input 
                                                type="email" 
                                                id="email"
                                                class="form-control border-start-0" 
                                                placeholder="Email"
                                                v-model="formData.email"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <label for="sdt" class="form-label fw-semibold">Số điện thoại</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bx bx-phone text-muted"></i>
                                            </span>
                                            <input 
                                                type="text" 
                                                id="sdt"
                                                class="form-control border-start-0" 
                                                placeholder="Số điện thoại"
                                                v-model="formData.sdt"
                                                maxlength="10"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <label for="password" class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bx bx-lock-alt text-muted"></i>
                                            </span>
                                            <input 
                                                type="password" 
                                                id="password"
                                                class="form-control border-start-0" 
                                                placeholder="Mật khẩu"
                                                v-model="formData.password"
                                                required
                                                minlength="6"
                                            >
                                            <button 
                                                type="button" 
                                                class="btn btn-outline-secondary border-start-0"
                                                @click="togglePasswordVisibility('password')"
                                            >
                                                <i :class="showPassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Tối thiểu 6 ký tự</small>
                                    </div>
                                    
                                    <div class="col-6">
                                        <label for="re_password" class="form-label fw-semibold">Nhập Lại Mật khẩu <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bx bx-lock text-muted"></i>
                                            </span>
                                            <input 
                                                type="password" 
                                                id="re_password"
                                                class="form-control border-start-0" 
                                                placeholder="Nhập lại mật khẩu"
                                                v-model="formData.password_confirmation"
                                                required
                                            >
                                            <button 
                                                type="button" 
                                                class="btn btn-outline-secondary border-start-0"
                                                @click="togglePasswordVisibility('re_password')"
                                            >
                                                <i :class="showRePassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                id="flexSwitchCheckChecked"
                                                v-model="formData.agreeTerms"
                                                required
                                            >
                                            <label class="form-check-label" for="flexSwitchCheckChecked">
                                                Bằng việc đăng ký tài khoản, tôi đồng ý với Điều khoản dịch vụ & Chính sách bảo mật của <b class="text-primary">ShopHuuHieu</b>.
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button 
                                                type="submit" 
                                                class="btn btn-success btn-lg signup-btn fw-bold"
                                                :disabled="loading"
                                            >
                                                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                                                <i v-else class="bx bx-user-plus me-2"></i>
                                                {{ loading ? 'Đang đăng ký...' : 'Đăng Ký' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { authService } from '@/services/authService';

export default {
    name: 'DangKy',
    data() {
        return {
            formData: {
                ten: '',
                tai_khoan: '',
                email: '',
                password: '',
                password_confirmation: '',
                sdt: '',
                dia_chi: '',
                agreeTerms: false,
            },
            showPassword: false,
            showRePassword: false,
            loading: false,
            error: '',
            success: '',
        };
    },
    methods: {
        async handleRegister() {
            // Validation
            if (this.formData.password !== this.formData.password_confirmation) {
                this.error = 'Mật khẩu xác nhận không khớp!';
                return;
            }

            if (this.formData.password.length < 6) {
                this.error = 'Mật khẩu phải có ít nhất 6 ký tự!';
                return;
            }

            if (!this.formData.agreeTerms) {
                this.error = 'Vui lòng đồng ý với điều khoản dịch vụ!';
                return;
            }

            this.loading = true;
            this.error = '';
            this.success = '';

            try {
                const data = {
                    ten: this.formData.ten,
                    tai_khoan: this.formData.tai_khoan,
                    email: this.formData.email,
                    password: this.formData.password,
                    sdt: this.formData.sdt || null,
                    dia_chi: this.formData.dia_chi || null,
                };

                const response = await authService.register(data);

                if (response.data.success) {
                    this.success = 'Đăng ký thành công! Đang chuyển đến trang đăng nhập...';
                    
                    // Chuyển đến trang đăng nhập sau 2 giây
                    setTimeout(() => {
                        this.$router.push('/client/dang-nhap');
                    }, 2000);
                } else {
                    this.error = response.data.message || 'Đăng ký thất bại';
                }
            } catch (error) {
                console.error('Register error:', error);
                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    const errorMessages = Object.values(errors).flat();
                    this.error = errorMessages.join(', ');
                } else if (error.response?.data?.message) {
                    this.error = error.response.data.message;
                } else {
                    this.error = 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại!';
                }
            } finally {
                this.loading = false;
            }
        },
        togglePasswordVisibility(field) {
            if (field === 'password') {
                this.showPassword = !this.showPassword;
                const passwordInput = document.getElementById('password');
                if (passwordInput) {
                    passwordInput.type = this.showPassword ? 'text' : 'password';
                }
            } else {
                this.showRePassword = !this.showRePassword;
                const rePasswordInput = document.getElementById('re_password');
                if (rePasswordInput) {
                    rePasswordInput.type = this.showRePassword ? 'text' : 'password';
                }
            }
        },
    },
    mounted() {
        // Kiểm tra nếu đã đăng nhập thì chuyển về trang chủ
        const token = localStorage.getItem('token');
        if (token) {
            this.$router.push('/');
        }
    }
}
</script>

<style scoped>
.section-authentication-signup {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 50%, #d1ecf1 100%);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Floating Shapes - Same as DangNhap */
.animated-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 0;
}

.floating-shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 3px solid rgba(255, 255, 255, 0.6);
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.4),
                0 0 40px rgba(255, 255, 255, 0.2),
                inset 0 0 20px rgba(255, 255, 255, 0.1);
    animation: float 20s infinite ease-in-out, borderPulse 3s ease-in-out infinite;
}

.shape-1 {
    width: 200px;
    height: 200px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
    background: rgba(13, 110, 253, 0.1);
    border-color: rgba(255, 255, 255, 0.7);
    animation-delay: 0s, 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
    background: rgba(40, 167, 69, 0.1);
    animation-duration: 25s;
    border-color: rgba(255, 255, 255, 0.65);
    animation-delay: 2s, 0.5s;
}

.shape-3 {
    width: 100px;
    height: 100px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
    background: rgba(255, 193, 7, 0.1);
    animation-duration: 18s;
    border-color: rgba(255, 255, 255, 0.6);
    animation-delay: 4s, 1s;
}

.shape-4 {
    width: 120px;
    height: 120px;
    top: 30%;
    right: 30%;
    animation-delay: 1s;
    background: rgba(220, 53, 69, 0.1);
    animation-duration: 22s;
    border-color: rgba(255, 255, 255, 0.68);
    animation-delay: 1s, 1.5s;
}

.shape-5 {
    width: 80px;
    height: 80px;
    bottom: 40%;
    right: 10%;
    animation-delay: 3s;
    background: rgba(108, 117, 125, 0.1);
    animation-duration: 20s;
    border-color: rgba(255, 255, 255, 0.62);
    animation-delay: 3s, 2s;
}

.shape-6 {
    width: 180px;
    height: 180px;
    top: 70%;
    left: 5%;
    animation-delay: 5s;
    background: rgba(13, 110, 253, 0.08);
    animation-duration: 28s;
    border-color: rgba(255, 255, 255, 0.7);
    animation-delay: 5s, 2.5s;
}

@keyframes float {
    0%, 100% {
        transform: translate(0, 0) rotate(0deg);
        opacity: 0.5;
    }
    25% {
        transform: translate(50px, -50px) rotate(90deg);
        opacity: 0.8;
    }
    50% {
        transform: translate(-30px, -100px) rotate(180deg);
        opacity: 0.6;
    }
    75% {
        transform: translate(-50px, 50px) rotate(270deg);
        opacity: 0.7;
    }
}

@keyframes borderPulse {
    0%, 100% {
        border-color: rgba(255, 255, 255, 0.6);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.4),
                    0 0 40px rgba(255, 255, 255, 0.2),
                    inset 0 0 20px rgba(255, 255, 255, 0.1);
    }
    50% {
        border-color: rgba(255, 255, 255, 0.9);
        box-shadow: 0 0 30px rgba(255, 255, 255, 0.6),
                    0 0 60px rgba(255, 255, 255, 0.4),
                    inset 0 0 30px rgba(255, 255, 255, 0.2);
    }
}

/* Wave Effects - Same as DangNhap */
.wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.2), 
        transparent);
    border-top: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 -5px 20px rgba(255, 255, 255, 0.3),
                0 -10px 40px rgba(255, 255, 255, 0.2);
    animation: waveMove 10s linear infinite, waveGlow 4s ease-in-out infinite;
    z-index: 0;
}

.wave-1 {
    animation-duration: 10s;
    opacity: 0.3;
}

.wave-2 {
    animation-duration: 15s;
    animation-delay: -5s;
    opacity: 0.2;
    height: 150px;
}

.wave-3 {
    animation-duration: 20s;
    animation-delay: -10s;
    opacity: 0.15;
    height: 200px;
}

@keyframes waveMove {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

@keyframes waveGlow {
    0%, 100% {
        border-top-color: rgba(255, 255, 255, 0.5);
        box-shadow: 0 -5px 20px rgba(255, 255, 255, 0.3),
                    0 -10px 40px rgba(255, 255, 255, 0.2);
    }
    50% {
        border-top-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 -8px 30px rgba(255, 255, 255, 0.5),
                    0 -15px 60px rgba(255, 255, 255, 0.3);
    }
}

/* Side Borders - Same as DangNhap */
.side-border {
    position: absolute;
    top: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, 
        transparent 0%,
        rgba(255, 255, 255, 0.3) 20%,
        rgba(255, 255, 255, 0.6) 50%,
        rgba(255, 255, 255, 0.3) 80%,
        transparent 100%);
    border: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.4),
                0 0 40px rgba(255, 255, 255, 0.2),
                inset 0 0 20px rgba(255, 255, 255, 0.3);
    animation: sideBorderGlow 3s ease-in-out infinite;
    z-index: 0;
}

.side-border-left {
    left: 0;
    border-radius: 0 20px 20px 0;
}

.side-border-right {
    right: 0;
    border-radius: 20px 0 0 20px;
}

@keyframes sideBorderGlow {
    0%, 100% {
        border-color: rgba(255, 255, 255, 0.5);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.4),
                    0 0 40px rgba(255, 255, 255, 0.2),
                    inset 0 0 20px rgba(255, 255, 255, 0.3);
        background: linear-gradient(180deg, 
            transparent 0%,
            rgba(255, 255, 255, 0.3) 20%,
            rgba(255, 255, 255, 0.6) 50%,
            rgba(255, 255, 255, 0.3) 80%,
            transparent 100%);
    }
    50% {
        border-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 30px rgba(255, 255, 255, 0.6),
                    0 0 60px rgba(255, 255, 255, 0.4),
                    inset 0 0 30px rgba(255, 255, 255, 0.5);
        background: linear-gradient(180deg, 
            transparent 0%,
            rgba(255, 255, 255, 0.5) 20%,
            rgba(255, 255, 255, 0.8) 50%,
            rgba(255, 255, 255, 0.5) 80%,
            transparent 100%);
    }
}

.position-relative.z-1 {
    position: relative;
    z-index: 1;
}

.card {
    border-radius: 20px !important;
    overflow: hidden;
    border: 3px solid #ffffff;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.3), 
                0 10px 40px rgba(0, 0, 0, 0.1),
                0 0 60px rgba(255, 255, 255, 0.1);
    position: relative;
    animation: borderGlow 3s ease-in-out infinite;
    transition: all 0.3s ease;
}

.card::before {
    content: '';
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    border-radius: 20px;
    background: linear-gradient(45deg, 
        rgba(255, 255, 255, 0.8), 
        rgba(255, 255, 255, 0.4), 
        rgba(255, 255, 255, 0.8));
    background-size: 200% 200%;
    animation: shimmer 3s linear infinite;
    z-index: -1;
    opacity: 0.6;
}

.card:hover {
    border-color: #ffffff;
    box-shadow: 0 0 30px rgba(255, 255, 255, 0.5), 
                0 15px 50px rgba(0, 0, 0, 0.15),
                0 0 80px rgba(255, 255, 255, 0.2);
    transform: translateY(-5px);
}

@keyframes borderGlow {
    0%, 100% {
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.3), 
                    0 10px 40px rgba(0, 0, 0, 0.1),
                    0 0 60px rgba(255, 255, 255, 0.1);
    }
    50% {
        box-shadow: 0 0 30px rgba(255, 255, 255, 0.5), 
                    0 15px 50px rgba(0, 0, 0, 0.15),
                    0 0 80px rgba(255, 255, 255, 0.2);
    }
}

@keyframes shimmer {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

.signup-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2c3e50;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin: 0;
}

.login-link {
    color: #0d6efd;
    transition: all 0.3s ease;
}

.login-link:hover {
    color: #0b5ed7;
    text-decoration: underline !important;
}

.form-label {
    color: #495057;
    margin-bottom: 0.75rem;
    font-size: 1.1rem;
    font-weight: 600;
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
    padding: 1rem 1.25rem;
    font-size: 1.05rem;
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

.signup-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 10px;
    padding: 0.875rem 1.5rem;
    font-size: 1.1rem;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.signup-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #218838 0%, #1ea080 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.signup-btn:active:not(:disabled) {
    transform: translateY(0);
}

.signup-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.signup-btn i {
    font-size: 1.2rem;
}

.form-check-label {
    color: #495057;
    font-size: 0.95rem;
}

.back-home-link {
    color: #ffffff;
    font-size: 1rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.back-home-link:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateX(-5px);
    text-decoration: none;
}

.back-home-link i {
    font-size: 1.2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .signup-title {
        font-size: 1.5rem;
    }
    
    .card-body {
        padding: 2rem !important;
    }
}
</style>