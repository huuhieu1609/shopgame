<template>
    <div class="bot-client-footer">
        <div class="footer-container">
            <div class="footer-content">
                <!-- Footer Info -->
                 
                <div class="footer-section">
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <div class="footer-logo-icon">🐰</div>
                            <div class="footer-logo-text">HuuHieu.Shop</div>
                        </div>
                        <p class="footer-description">
                            Cửa hàng mua bán tài khoản game uy tín, chất lượng hàng đầu
                        </p>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h4 class="footer-title">Liên kết nhanh</h4>
                    <ul class="footer-links">
                        <li><router-link to="/">Trang chủ</router-link></li>
                        <li><router-link to="/tin-tuc">Tin tức</router-link></li>
                        <li><router-link to="/tai-khoan">Tài khoản</router-link></li>
                        <li><router-link to="/dong-tien">Dòng tiền</router-link></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="footer-section">
                    <h4 class="footer-title">Hỗ trợ</h4>
                    <ul class="footer-links">
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                        <li><a href="#">Câu hỏi thường gặp</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="footer-section">
                    <h4 class="footer-title">Liên hệ</h4>
                    <ul class="footer-contact">
                        <li>
                            <span class="contact-icon">📧</span>
                            <span>Email: support@huuhieu.shop</span>
                        </li>
                        <li>
                            <span class="contact-icon">💬</span>
                            <span>Zalo: 0123456789</span>
                        </li>
                        <li>
                            <span class="contact-icon">⏰</span>
                            <span>Giờ làm việc: 24/7</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-copyright">
                    <p>&copy; 2024 HuuHieu.Shop. Tất cả quyền được bảo lưu.</p>
                </div>
                <div class="footer-social">
                    <a href="#" class="social-link">Facebook</a>
                    <a href="#" class="social-link">Zalo</a>
                    <a href="#" class="social-link">Telegram</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BotClient'
}
</script>

<style scoped>
.bot-client-footer {
    background: #1a1a1a;
    color: #ffffff;
    margin-top: auto;
    padding-top: 3rem;
    padding-bottom: 1.5rem;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2.5rem;
    margin-bottom: 2.5rem;
}

.footer-section {
    display: flex;
    flex-direction: column;
}

.footer-brand {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.footer-logo-icon {
    width: 40px;
    height: 40px;
    background: #000000;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.footer-logo-text {
    font-size: 18px;
    font-weight: 700;
    color: #ffffff;
}

.footer-description {
    color: #b0b0b0;
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
}

.footer-title {
    font-size: 16px;
    font-weight: 600;
    color: #ffffff;
    margin: 0 0 1rem 0;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.footer-links li a {
    color: #b0b0b0;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s ease;
}

.footer-links li a:hover {
    color: #ffffff;
}

.footer-contact {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.footer-contact li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #b0b0b0;
    font-size: 14px;
}

.contact-icon {
    font-size: 16px;
}

.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 2rem;
    border-top: 1px solid #333;
    flex-wrap: wrap;
    gap: 1rem;
}

.footer-copyright {
    color: #b0b0b0;
    font-size: 14px;
    margin: 0;
}

.footer-social {
    display: flex;
    gap: 1.5rem;
}

.social-link {
    color: #b0b0b0;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s ease;
}

.social-link:hover {
    color: #ffffff;
}

/* Responsive */
@media (max-width: 768px) {
    .bot-client-footer {
        padding-top: 2rem;
        padding-bottom: 1rem;
    }

    .footer-container {
        padding: 0 1rem;
    }

    .footer-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .footer-bottom {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }

    .footer-social {
        justify-content: center;
    }
}
</style>
