import { createRouter, createWebHistory } from "vue-router"; // cài vue-router: npm install vue-router@next --save
import { adminAuthService } from '@/services/adminAuthService';

const routes = [
   //client
    {
        path : '/',
        component: ()=>import('../components/Client/TrangChu/index.vue'),
        meta: {
            layout: 'indexClient'
        }
    },
    {
        path : '/client/dang-nhap',
        component: ()=>import('../components/Client/DangNhap/index.vue'),
        meta: {
            layout: 'indexBlank'
        }
    },
    {
        path : '/client/dang-ky',
        component: ()=>import('../components/Client/DangKy/index.vue'),
        meta: {
            layout: 'indexBlank'
        }
    },
    {
        path : '/client/profile',
        component: ()=>import('../components/Client/Profile/index.vue'),
        meta: {
            layout: 'indexClient'
        }
    },
    {
        path : '/client/nap-tien',
        component: ()=>import('../components/Client/NapTien/index.vue'),
        meta: {
            layout: 'indexClient'
        }
    },
    {
        path : '/client/lich-su-mua-hang',
        component: ()=>import('../components/Client/LichSuMuaHang/index.vue'),
        meta: {
            layout: 'indexClient'
        }
    },
    {
        path : '/client/don-hang',
        component: ()=>import('../components/Client/DonHang/index.vue'),
        meta: {
            layout: 'indexClient'
        }
    },
    {
        path : '/client/quen-mat-khau',
        component: ()=>import('../components/Client/QuenMatKhau/index.vue'),
        meta: {
            layout: 'indexBlank'
        }
    },
    {
        path : '/client/chi-tiet-don-hang/:id',
        component: ()=>import('../components/Client/ChiTietDonHang/index.vue'),
        meta: {
            layout: 'indexClient'
        }
    },
    {
        path : '/client/san-pham/:id',
        component: ()=>import('../components/Client/ChiTietSanPham/index.vue'),
        meta: {
            layout: 'indexClient'
        }
    },

    //Admin
    {
        path : '/admin/dang-nhap',
        component: ()=>import('../components/Admin/DangNhap/index.vue'),
        meta: {
            layout: 'indexBlank',
            requiresAuth: false
        }
    },
    {
        path : '/admin/danh-muc',
        component: ()=>import('../components/Admin/DanhMuc/index.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/admin/khach-hang',
        component: ()=>import('../components/Admin/KhachHang/index.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/admin/san-pham',
        component: ()=>import('../components/Admin/DanhSachSanPham/index.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/admin/don-hang',
        component: ()=>import('../components/Admin/DonHang/index.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/admin/ma-giam-gia',
        component: ()=>import('../components/Admin/MaGiamGia/index.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/admin/nhan-vien',
        component: ()=>import('../components/Admin/NhanVien/index.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/admin/giao-dich-nap-tien',
        component: ()=>import('../components/Admin/GiaoDichNapTien/index.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/admin/chi-tiet-tai-khoan',
        component: ()=>import('../components/Admin/ChiTietTaiKhoan/index.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/admin/cong-tac-vien',
        component: ()=>import('../components/Admin/CongTacVien/index.vue'),
        meta: {
            requiresAuth: true
        }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

// Navigation guard cho admin routes
router.beforeEach((to, from, next) => {
    // Kiểm tra nếu route là admin và yêu cầu authentication
    if (to.path.startsWith('/admin') && to.meta.requiresAuth !== false) {
        if (!adminAuthService.isAuthenticated()) {
            // Chưa đăng nhập, chuyển đến trang đăng nhập
            next({
                path: '/admin/dang-nhap',
                query: { redirect: to.fullPath }
            });
        } else {
            // Đã đăng nhập, cho phép truy cập
            next();
        }
    } else if (to.path === '/admin/dang-nhap' && adminAuthService.isAuthenticated()) {
        // Đã đăng nhập nhưng đang ở trang login, chuyển về trang admin
        next('/admin/danh-muc');
    } else {
        // Các route khác, cho phép truy cập
        next();
    }
});

export default router