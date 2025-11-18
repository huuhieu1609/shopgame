<template>
    <div class="nap-tien-page">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <!-- Header Messages -->
                    <div class="payment-header mb-4">
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <strong>HUUHIEU.SHOP</strong> - SHOP CHUYÊN BÁN ACC REG + ACC RANDOM
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <strong>⚠️ LƯU Ý:</strong> NẠP GHI ĐÚNG NỘI DUNG, NHẬP SAI NỘI DUNG SẼ KHÔNG ĐƯỢC HOÀN LẠI
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                            <strong>ℹ️ THÔNG BÁO:</strong> HỆ THỐNG NẠP TIỀN TỰ ĐỘNG, NẾU QUÁ 5 PHÚT KHÔNG CỘNG TIỀN LIÊN HỆ ADMIN
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>

                    <!-- Payment Card -->
                    <div class="card payment-card border-0 shadow-lg animate-fade-in">
                        <div class="card-body p-4 p-lg-5">
                            <!-- Bank Logo -->
                            <div class="text-center mb-4">
                                <div class="bank-logo-wrapper bank-logo-vcb">
                                    <div class="bank-logo-text">VCB</div>
                                </div>
                            </div>

                            <!-- Bank Info -->
                            <div class="bank-info mb-4">
                                <div class="bank-item mb-3">
                                    <div class="bank-label">Số tài khoản:</div>
                                    <div class="bank-value d-flex align-items-center justify-content-between">
                                        <span>VCB: 1039319824</span>
                                        <button 
                                            class="btn btn-sm btn-outline-light copy-btn"
                                            @click="copyToClipboard('1039319824')"
                                        >
                                            <i class="bx bx-copy me-1"></i>
                                            Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="bank-item mb-3">
                                    <div class="bank-label">Chủ TK:</div>
                                    <div class="bank-value">Trần Hữu Hiếu</div>
                                </div>
                                <div class="bank-item mb-4">
                                    <div class="bank-label">Nội Dung:</div>
                                    <div class="bank-value d-flex align-items-center justify-content-between">
                                        <span class="content-code">{{ noiDungChuyenKhoan }}</span>
                                        <button 
                                            class="btn btn-sm btn-outline-light copy-btn"
                                            @click="copyToClipboard(noiDungChuyenKhoan)"
                                        >
                                            <i class="bx bx-copy me-1"></i>
                                            Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="payment-note">
                                    <i class="bx bx-info-circle me-2"></i>
                                    Nhập đúng nội dung tiền tự động cộng trong vài phút
                                </div>
                            </div>

                            <!-- QR Code Section -->
                            <div class="qr-section text-center">
                                <div class="qr-code-wrapper">
                                    <img 
                                        :src="qrCodeUrl" 
                                        alt="QR Code" 
                                        class="qr-code-image"
                                    >
                                </div>
                            </div>

                            <!-- Bank Logos Footer -->
                            <div class="payment-footer mt-4 pt-4 border-top border-light border-opacity-25">
                                <div class="d-flex align-items-center justify-content-center gap-4">
                                    <div class="footer-logo">
                                        <span class="napas-logo">napas 247</span>
                                    </div>
                                    <div class="footer-divider"></div>
                                    <div class="footer-logo">
                                        <span class="vcb-logo-footer">VCB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="payment-instructions mt-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3">
                                    <i class="bx bx-info-circle text-primary me-2"></i>
                                    Hướng dẫn thanh toán
                                </h5>
                                <ol class="mb-0 ps-3">
                                    <li class="mb-2">Mở ứng dụng ngân hàng trên điện thoại</li>
                                    <li class="mb-2">Chọn tính năng quét QR code</li>
                                    <li class="mb-2">Quét mã QR code ở trên</li>
                                    <li class="mb-2">Kiểm tra thông tin và nhập đúng nội dung: <strong class="text-danger">{{ noiDungChuyenKhoan }}</strong></li>
                                    <li class="mb-0">Xác nhận thanh toán và chờ hệ thống tự động cộng tiền</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Input -->
                    <div class="amount-input-section mt-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3">
                                    <i class="bx bx-money text-success me-2"></i>
                                    Nhập số tiền nạp
                                </h5>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">₫</span>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="soTienNap"
                                        placeholder="Nhập số tiền muốn nạp"
                                        min="10000"
                                        step="1000"
                                    >
                                </div>
                                <div class="mt-3">
                                    <button 
                                        class="btn btn-primary btn-lg w-100"
                                        @click="generateQRCode"
                                        :disabled="!soTienNap || soTienNap < 10000"
                                    >
                                        <i class="bx bx-qr-scan me-2"></i>
                                        Tạo mã QR
                                    </button>
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
export default {
    name: 'NapTien',
    data() {
        return {
            noiDungChuyenKhoan: 'MT61',
            soTienNap: null,
        };
    },
    computed: {
        qrCodeUrl() {
            const amount = this.soTienNap || 0;
            const content = this.noiDungChuyenKhoan;
            return `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=00020101021238570010A00000072701270006970416010910393198240208QRIBFTTA53037045406${amount}5802VN62080703${content}6304`;
        }
    },
    mounted() {
        // Kiểm tra đăng nhập
        const token = localStorage.getItem('token');
        if (!token) {
            alert('Vui lòng đăng nhập để nạp tiền!');
            this.$router.push('/client/dang-nhap?redirect=' + encodeURIComponent(this.$route.fullPath));
            return;
        }

        // Tạo mã nội dung chuyển khoản ngẫu nhiên
        this.generateNoiDung();
    },
    methods: {
        generateNoiDung() {
            // Tạo mã ngẫu nhiên cho nội dung chuyển khoản
            const random = Math.floor(Math.random() * 10000);
            this.noiDungChuyenKhoan = `MT${random.toString().padStart(4, '0')}`;
        },
        generateQRCode() {
            if (!this.soTienNap || this.soTienNap < 10000) {
                alert('Vui lòng nhập số tiền tối thiểu 10,000 ₫');
                return;
            }
            // QR code sẽ tự động cập nhật khi soTienNap thay đổi
            this.generateNoiDung();
        },
        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Đã copy: ' + text);
            }).catch(() => {
                // Fallback
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('Đã copy: ' + text);
            });
        },
    }
}
</script>

<style scoped>
.nap-tien-page {
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

/* Amount Input */
.amount-input-section .card {
    border-radius: 15px;
    background: #ffffff;
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
</style>

