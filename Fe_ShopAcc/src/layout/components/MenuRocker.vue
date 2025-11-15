<template>
    <div>
        <div class="nav-container primary-menu">
            <div class="mobile-topbar-header">
                <div>
                    <img src="../../assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">Rukada</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class="bx bx-arrow-to-left"></i>
                </div>
            </div>
            <nav class="navbar navbar-expand-xl w-100">
                <ul class="navbar-nav justify-content-start flex-grow-1 gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/danh-muc">
                            <div class="parent-icon"><i class="bx bx-cookie"></i>
                            </div>
                            <div class="menu-title">Danh Mục</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/san-pham">
                            <div class="parent-icon"><i class="bx bx-shopping-bag"></i>
                            </div>
                            <div class="menu-title">Sản Phẩm</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/chi-tiet-tai-khoan">
                            <div class="parent-icon"><i class="bx bx-key"></i>
                            </div>
                            <div class="menu-title">Chi Tiết Tài Khoản</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/khach-hang">
                            <div class="parent-icon"><i class="bx bx-user"></i>
                            </div>
                            <div class="menu-title">Khách Hàng</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/don-hang">
                            <div class="parent-icon"><i class="bx bx-receipt"></i>
                            </div>
                            <div class="menu-title">Đơn Hàng</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/giao-dich-nap-tien">
                            <div class="parent-icon"><i class="bx bx-money"></i>
                            </div>
                            <div class="menu-title">Giao Dịch Nạp Tiền</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/ma-giam-gia">
                            <div class="parent-icon"><i class="bx bx-purchase-tag"></i>
                            </div>
                            <div class="menu-title">Mã Giảm Giá</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/nhan-vien">
                            <div class="parent-icon"><i class="bx bx-user-circle"></i>
                            </div>
                            <div class="menu-title">Nhân Viên</div>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
export default {
    name: 'MenuRocker'
}
</script>

<style scoped>
/* Styles for MenuRocker */
</style>