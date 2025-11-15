import api from './api';

export const authService = {
    // Đăng ký
    register(data) {
        return api.post('/khach-hang/dang-ky', data);
    },

    // Đăng nhập (tạm thời dùng store endpoint, cần tạo API đăng nhập riêng)
    login(email, password) {
        // TODO: Tạo API đăng nhập riêng trong backend
        // Tạm thời return error để thông báo cần tạo API
        return Promise.reject({ 
            response: { 
                data: { 
                    message: 'API đăng nhập chưa được tạo. Vui lòng liên hệ admin.' 
                } 
            } 
        });
    },

    // Đăng xuất
    logout() {
        return api.post('/client/khach-hang/dang-xuat');
    },

    // Đăng xuất tất cả
    logoutAll() {
        return api.post('/client/khach-hang/dang-xuat-all');
    },

    // Quên mật khẩu
    forgotPassword(email) {
        return api.post('/khach-hang/quen-mat-khau', { email });
    },

    // Reset mật khẩu
    resetPassword(token, password, password_confirmation) {
        return api.post('/khach-hang/reset-mat-khau', {
            token,
            password,
            password_confirmation
        });
    },
};
