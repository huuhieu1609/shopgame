import api from './api';

export const clientService = {
    // Sản phẩm
    getFlashSale() {
        return api.get('/san-pham/data-flash-sale');
    },

    getNoiBat() {
        return api.get('/san-pham/data-noi-bat');
    },

    getNew() {
        return api.get('/san-pham/data-new');
    },

    search(q) {
        return api.get('/san-pham/search', { params: { q } });
    },

    getProductById(id) {
        return api.get(`/san-pham/${id}`);
    },

    // Danh mục
    getCategories() {
        return api.get('/danh-muc/data-open');
    },

    getCategoryById(id) {
        return api.get(`/danh-muc/${id}`);
    },

    // Mã giảm giá
    validateDiscountCode(ma) {
        return api.post('/ma-giam-gia/validate', { ma });
    },
};

