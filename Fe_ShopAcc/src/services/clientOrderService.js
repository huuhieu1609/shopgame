import api from './api';

export const clientOrderService = {
    // Lấy danh sách đơn hàng của khách hàng
    getMyOrders(params = {}, clientId = null) {
        const config = { params };
        if (clientId) {
            config.headers = {
                'X-Client-Id': clientId
            };
            config.params = { ...config.params, client_id: clientId };
        }
        return api.get('/client/don-hang', config);
    },

    // Lấy chi tiết đơn hàng của khách hàng
    getMyOrder(id, clientId = null) {
        const config = {};
        if (clientId) {
            config.params = { client_id: clientId };
            config.headers = { 'X-Client-Id': clientId };
        }
        return api.get(`/client/don-hang/${id}`, config);
    },

    // Tạo đơn hàng mới
    createOrder(data) {
        return api.post('/client/don-hang', data);
    },

    // Lấy giao dịch nạp tiền của khách hàng
    getMyTransactions(params = {}) {
        return api.get('/client/giao-dich-nap-tien', { params });
    },

    // Lấy chi tiết giao dịch nạp tiền
    getMyTransaction(id) {
        return api.get(`/client/giao-dich-nap-tien/${id}`);
    },

    // Tạo giao dịch nạp tiền
    createTransaction(data) {
        return api.post('/client/giao-dich-nap-tien', data);
    },

    // Lấy giao dịch ví
    getMyWalletTransactions(params = {}) {
        return api.get('/client/giao-dich-vi', { params });
    },
};
