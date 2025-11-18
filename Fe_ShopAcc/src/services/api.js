import axios from 'axios';

// Cấu hình base URL cho API
const API_BASE_URL = 'http://localhost:8000/api'; // Thay đổi theo URL backend của bạn

// Tạo axios instance
const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Request interceptor để thêm token nếu có
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor để xử lý lỗi
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Xử lý khi không có quyền truy cập
            const currentPath = window.location.pathname;
            const requestUrl = error.config?.url || '';
            
            // Danh sách các route công khai (không cần authentication)
            // Bao gồm cả /san-pham và /client/san-pham, /danh-muc và /client/danh-muc
            const publicRoutePatterns = [
                '/san-pham',
                '/client/san-pham',
                '/danh-muc',
                '/client/danh-muc',
                '/khach-hang/dang-nhap',
                '/khach-hang/dang-ky',
                '/admin/dang-nhap'
            ];
            
            // Kiểm tra xem request có phải là route công khai không
            const isPublicRoute = publicRoutePatterns.some(pattern => 
                requestUrl.includes(pattern) || requestUrl.startsWith(pattern)
            );
            
            // Nếu là route công khai, KHÔNG làm gì cả - chỉ reject error để component tự xử lý
            if (isPublicRoute) {
                return Promise.reject(error);
            }
            
            // Nếu KHÔNG phải route công khai, kiểm tra xem có đang ở trang yêu cầu authentication không
            const requiresAuth = currentPath.includes('/client/profile') ||
                                currentPath.includes('/client/don-hang') ||
                                currentPath.includes('/client/lich-su-mua-hang') ||
                                currentPath.includes('/client/nap-tien') ||
                                currentPath.includes('/client/chi-tiet-don-hang') ||
                                currentPath.startsWith('/admin');
            
            // Chỉ xóa token và redirect nếu đang ở trang yêu cầu authentication
            if (requiresAuth && !currentPath.includes('/dang-nhap') && !currentPath.includes('/dang-ky')) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                
                if (currentPath.includes('/admin')) {
                    window.location.href = '/admin/dang-nhap';
                } else {
                    window.location.href = '/client/dang-nhap';
                }
            }
        }
        return Promise.reject(error);
    }
);

export default api;
