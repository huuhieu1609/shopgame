import api from './api';

export const chiTietTaiKhoanService = {
    // Lấy danh sách với phân trang và tìm kiếm
    getData(params = {}) {
        return api.get('/admin/chi-tiet-tai-khoan', { params });
    },

    // Lấy chi tiết theo ID
    getById(id) {
        return api.get(`/admin/chi-tiet-tai-khoan/${id}`);
    },

    // Tạo mới
    create(data) {
        return api.post('/admin/chi-tiet-tai-khoan', data);
    },

    // Cập nhật
    update(id, data) {
        return api.put(`/admin/chi-tiet-tai-khoan/${id}`, data);
    },

    // Xóa
    delete(id) {
        return api.delete(`/admin/chi-tiet-tai-khoan/${id}`);
    },

    // Thêm nhiều tài khoản cùng lúc
    storeMultiple(data) {
        return api.post('/admin/chi-tiet-tai-khoan/them-nhieu', data);
    },
};


export const chiTietTaiKhoanService = {
    // Lấy danh sách với phân trang và tìm kiếm
    getData(params = {}) {
        return api.get('/admin/chi-tiet-tai-khoan', { params });
    },

    // Lấy chi tiết theo ID
    getById(id) {
        return api.get(`/admin/chi-tiet-tai-khoan/${id}`);
    },

    // Tạo mới
    create(data) {
        return api.post('/admin/chi-tiet-tai-khoan', data);
    },

    // Cập nhật
    update(id, data) {
        return api.put(`/admin/chi-tiet-tai-khoan/${id}`, data);
    },

    // Xóa
    delete(id) {
        return api.delete(`/admin/chi-tiet-tai-khoan/${id}`);
    },

    // Thêm nhiều tài khoản cùng lúc
    storeMultiple(data) {
        return api.post('/admin/chi-tiet-tai-khoan/them-nhieu', data);
    },
};
