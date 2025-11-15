import api from './api';

export const adminAuthService = {
    // Đăng nhập admin
    async login(email, password) {
        try {
            const response = await api.post('/admin/dang-nhap', {
                email,
                password
            });

            if (response.data.success && response.data.data) {
                // Lưu token và thông tin admin vào localStorage
                localStorage.setItem('admin_token', response.data.data.token || response.data.data.access_token);
                localStorage.setItem('admin_user', JSON.stringify(response.data.data.user || response.data.data));
                
                return response.data;
            } else {
                throw new Error(response.data.message || 'Đăng nhập thất bại');
            }
        } catch (error) {
            throw error;
        }
    },

    // Đăng xuất
    logout() {
        localStorage.removeItem('admin_token');
        localStorage.removeItem('admin_user');
    },

    // Lấy thông tin admin hiện tại
    getCurrentAdmin() {
        const adminStr = localStorage.getItem('admin_user');
        if (adminStr) {
            try {
                return JSON.parse(adminStr);
            } catch (e) {
                console.error('Error parsing admin data:', e);
                return null;
            }
        }
        return null;
    },

    // Kiểm tra đã đăng nhập chưa
    isAuthenticated() {
        const token = localStorage.getItem('admin_token');
        const admin = this.getCurrentAdmin();
        return !!token && !!admin;
    },

    // Lấy token
    getToken() {
        return localStorage.getItem('admin_token');
    },
};

