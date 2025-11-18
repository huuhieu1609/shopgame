<template>
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="page-header mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="page-title mb-2">
                        <i class="bx bx-shopping-bag me-2 text-primary"></i>
                        Danh sách sản phẩm
                    </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Danh sách sản phẩm</li>
                    </ol>
                </nav>
                </div>
                <button class="btn btn-primary btn-lg shadow-sm" @click="openModal('create')">
                    <i class="bx bx-plus-circle me-2"></i>
                    Thêm sản phẩm
                </button>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label for="searchInput" class="form-label fw-semibold mb-2">
                            <i class="bx bx-search me-1"></i>
                            Tìm kiếm
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bx bx-search text-muted"></i>
                            </span>
                            <input 
                                type="text" 
                                class="form-control border-start-0" 
                                id="searchInput"
                                placeholder="Tìm kiếm theo tên, username hoặc danh mục..."
                                v-model="searchQuery"
                                @keyup.enter="loadData"
                            >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="itemsPerPageSelect" class="form-label fw-semibold mb-2">
                            <i class="bx bx-list-ul me-1"></i>
                            Hiển thị
                        </label>
                        <select class="form-select form-select-lg" id="itemsPerPageSelect" v-model="perPage" @change="loadData">
                            <option value="5">5 mục</option>
                            <option value="10">10 mục</option>
                            <option value="20">20 mục</option>
                            <option value="50">50 mục</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bx bx-package"></i>
                            </div>
                            <div class="ms-3">
                                <div class="text-muted small">Tổng sản phẩm</div>
                                <div class="h4 mb-0 ">7</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-success bg-opacity-10 text-success">
                                <i class="bx bx-check-circle"></i>
                            </div>
                            <div class="ms-3">
                                <div class="text-muted small">Đang hiển thị</div>
                                <div class="h4 mb-0 ">7</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-info bg-opacity-10 text-info">
                                <i class="bx bx-user"></i>
                            </div>
                            <div class="ms-3">
                                <div class="text-muted small">Tổng tài khoản</div>
                                <div class="h4 mb-0 ">63</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bx bx-hide"></i>
                            </div>
                            <div class="ms-3">
                                <div class="text-muted small">Đang ẩn</div>
                                <div class="h4 mb-0 ">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-uppercase">
                        <i class="bx bx-table me-2 text-primary"></i>
                        Bảng danh sách sản phẩm
                    </h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-success" title="Thêm mới" @click="openModal('create')">
                            <i class="bx bx-plus"></i>
                        </button>
                        <button class="btn btn-outline-info" title="Xuất Excel">
                            <i class="bx bx-export"></i>
                        </button>
                        <button class="btn btn-outline-secondary" title="Làm mới" @click="loadData">
                            <i class="bx bx-refresh"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">
                                    <i class="bx bx-hash"></i>
                                </th>
                                <th style="width: 130px;">
                                    <i class="bx bx-user me-1"></i>Username
                                </th>
                                <th style="min-width: 220px;">
                                    <i class="bx bx-package me-1"></i>Tên sản phẩm
                                </th>
                                <th style="width: 200px;">
                                    <i class="bx bx-category me-1"></i>Danh mục
                                </th>
                                <th class="text-center" style="width: 70px;">
                                    <i class="bx bx-flag me-1"></i>Cờ
                                </th>
                                <th class="text-center" style="width: 130px;">
                                    <i class="bx bx-money me-1"></i>Giá
                                </th>
                                <th class="text-center" style="width: 110px;">
                                    <i class="bx bx-check-circle me-1"></i>CheckLive
                                </th>
                                <th style="min-width: 180px;">
                                    <i class="bx bx-file-blank me-1"></i>Nội dung
                                </th>
                                <th class="text-center" style="width: 120px;">
                                    <i class="bx bx-show me-1"></i>Trạng thái
                                </th>
                                <th class="text-center" style="width: 140px;">
                                    <i class="bx bx-group me-1"></i>Tài khoản
                                </th>
                                <th class="text-center" style="width: 280px;">
                                    <i class="bx bx-cog me-1"></i>Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else-if="sanPhams.length === 0">
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">
                                    Không có dữ liệu
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr 
                                v-for="(item, index) in sanPhams" 
                                :key="item.id"
                                class="table-row-hover"
                            >
                                <td class="text-center">
                                    <span class="badge bg-secondary bg-opacity-10 text-dark fw-semibold">
                                        {{ (currentPage - 1) * perPage + index + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="text-dark">{{ item.username || 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ item.ten }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2">
                                        {{ item.danh_muc?.ten || 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="flag-icon fs-4">{{ item.co || '🇻🇳' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-success fs-6">{{ formatCurrency(item.gia) }}</span>
                                </td>
                                <td class="text-center">
                                    <button 
                                        class="btn btn-outline-success" 
                                        title="Check Live"
                                        :class="{ 'btn-success': item.check_live }"
                                    >
                                        <i class="bx bx-check me-1"></i>{{ item.check_live ? 'Có' : 'Không' }}
                                    </button>
                                </td>
                                <td>
                                    <div class="content-text text-muted" style="max-height: 60px; overflow-y: auto;">
                                        {{ item.mo_ta || 'N/A' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button 
                                        class="btn btn-outline-success" 
                                        title="Trạng thái"
                                        :class="{ 'btn-success': item.trang_thai }"
                                    >
                                        <i class="bx bx-show me-1"></i>{{ item.trang_thai ? 'Hiển thị' : 'Ẩn' }}
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-outline-primary" title="Số tài khoản">
                                        <i class="bx bx-group me-1"></i>{{ item.so_luong || 0 }}
                                    </button>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button 
                                            class="btn btn-outline-info" 
                                            title="Quản lý tài khoản"
                                            @click="openAccountModal(item)"
                                        >
                                            <i class="bx bx-list-ul me-1"></i>
                                            <span class="d-none d-md-inline">Quản lý</span>
                                        </button>
                                        <button class="btn btn-outline-primary" title="Sửa" @click="openModal('edit', item)">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Xóa" @click="confirmDelete(item)">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-white border-top py-3" v-if="totalPages > 1">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div class="text-muted small">
                            <i class="bx bx-info-circle me-1"></i>
                            Hiển thị <strong>{{ (currentPage - 1) * perPage + 1 }}</strong> đến 
                            <strong>{{ Math.min(currentPage * perPage, sanPhams.length) }}</strong> của 
                            <strong>{{ sanPhams.length }}</strong> mục
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                    <a class="page-link" href="#" @click.prevent="currentPage > 1 && (currentPage--, loadData())">
                                        <i class="bx bx-chevron-left"></i>
                                    </a>
                                </li>
                                <li 
                                    v-for="page in totalPages" 
                                    :key="page"
                                    class="page-item" 
                                    :class="{ active: page === currentPage }"
                                >
                                    <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                                </li>
                                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                    <a class="page-link" href="#" @click.prevent="currentPage < totalPages && (currentPage++, loadData())">
                                        <i class="bx bx-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Quản lý tài khoản -->
        <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="accountModalLabel">
                            <i class="bx bx-key me-2"></i>
                            Quản lý tài khoản - {{ selectedSanPham?.ten || 'N/A' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button 
                                    class="nav-link" 
                                    :class="{ active: activeTab === 'list' }"
                                    @click="activeTab = 'list'"
                                    type="button"
                                >
                                    <i class="bx bx-list-ul me-2"></i>
                                    Danh sách tài khoản
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button 
                                    class="nav-link" 
                                    :class="{ active: activeTab === 'add' }"
                                    @click="activeTab = 'add'"
                                    type="button"
                                >
                                    <i class="bx bx-plus-circle me-2"></i>
                                    Thêm tài khoản
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content: Danh sách -->
                        <div v-show="activeTab === 'list'">
                            <div v-if="loadingAccounts" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-muted mt-3 mb-0">Đang tải danh sách tài khoản...</p>
                            </div>
                            
                            <div v-else-if="accounts && accounts.length > 0">
                            <div class="alert alert-info mb-3">
                                <i class="bx bx-info-circle me-2"></i>
                                Tổng số: <strong>{{ accounts.length }}</strong> tài khoản
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 60px;">#</th>
                                            <th style="min-width: 200px;">Username</th>
                                            <th style="min-width: 200px;">Password</th>
                                            <th>Thông tin bổ sung</th>
                                            <th class="text-center" style="width: 100px;">Trạng thái</th>
                                            <th class="text-center" style="width: 120px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(account, index) in accounts" :key="account.id">
                                            <td class="text-center fw-semibold">{{ index + 1 }}</td>
                                            <td>
                                                <code class="d-block p-2 bg-light rounded border text-break">{{ account.username }}</code>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <code class="flex-grow-1 p-2 bg-light rounded border text-break">{{ account.password }}</code>
                                                    <button 
                                                        class="btn btn-sm btn-outline-success"
                                                        @click="copyToClipboard(account.password)"
                                                        title="Copy password"
                                                    >
                                                        <i class="bx bx-copy"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small text-muted" style="max-width: 300px;">
                                                    {{ account.thong_tin_bo_sung || 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span 
                                                    class="badge" 
                                                    :class="account.trang_thai ? 'bg-success' : 'bg-secondary'"
                                                >
                                                    {{ account.trang_thai ? 'Hoạt động' : 'Tạm khóa' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button 
                                                    class="btn btn-sm btn-outline-primary"
                                                    @click="copyToClipboard(`${account.username}\n${account.password}`)"
                                                    title="Copy tài khoản"
                                                >
                                                    <i class="bx bx-copy me-1"></i>
                                                    Copy
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                            <div v-else class="text-center text-muted py-5">
                                <i class="bx bx-info-circle fs-1 mb-3"></i>
                                <p class="mb-0">Chưa có tài khoản cho sản phẩm này</p>
                            </div>
                        </div>

                        <!-- Tab Content: Thêm tài khoản -->
                        <div v-show="activeTab === 'add'">
                            <form @submit.prevent="addAccounts">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên sản phẩm</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        :value="selectedSanPham?.ten || 'N/A'"
                                        disabled
                                    >
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Danh sách tài khoản
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea 
                                        class="form-control" 
                                        rows="15"
                                        v-model="newAccountsText"
                                        placeholder="1 dòng 1 tài khoản&#10;Format: username|password hoặc username password&#10;Ví dụ:&#10;user1|pass123&#10;user2|pass456|Thông tin bổ sung"
                                        required
                                    ></textarea>
                                    <small class="text-muted">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Mỗi dòng là 1 tài khoản. Format: <code>username|password</code> hoặc <code>username password</code>
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check mb-2">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            id="chiThemChuaBan"
                                            v-model="addAccountOptions.chi_them_chua_ban"
                                        >
                                        <label class="form-check-label" for="chiThemChuaBan">
                                            Nếu bạn tích vào ô này, hệ thống sẽ chỉ thêm các tài khoản chưa bán (tốc độ tải lên chậm).
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            id="khongLocTrung"
                                            v-model="addAccountOptions.khong_loc_trung"
                                        >
                                        <label class="form-check-label" for="khongLocTrung">
                                            Nếu bạn tích vào ô này hệ thống sẽ không lọc trùng nick đã thêm (tốc độ tải lên nhanh hơn).
                                        </label>
                                    </div>
                                </div>

                                <div v-if="addAccountResult" class="alert" :class="addAccountResult.success ? 'alert-success' : 'alert-danger'">
                                    <strong>{{ addAccountResult.success ? 'Thành công!' : 'Có lỗi!' }}</strong>
                                    <p class="mb-0">{{ addAccountResult.message }}</p>
                                    <div v-if="addAccountResult.data" class="mt-2">
                                        <small>
                                            Thành công: <strong>{{ addAccountResult.data.success_count }}</strong> | 
                                            Lỗi: <strong>{{ addAccountResult.data.error_count }}</strong> | 
                                            Bỏ qua: <strong>{{ addAccountResult.data.skipped_count }}</strong>
                                        </small>
                                        <ul v-if="addAccountResult.data.errors && addAccountResult.data.errors.length > 0" class="mt-2 mb-0 small">
                                            <li v-for="(error, idx) in addAccountResult.data.errors" :key="idx">{{ error }}</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button 
                                        type="submit" 
                                        class="btn btn-success btn-lg"
                                        :disabled="addingAccounts || !newAccountsText.trim()"
                                    >
                                        <span v-if="addingAccounts" class="spinner-border spinner-border-sm me-2"></span>
                                        <i v-else class="bx bx-plus-circle me-2"></i>
                                        {{ addingAccounts ? 'Đang thêm...' : 'Thêm Ngay' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-2"></i>
                            Đóng
                        </button>
                        <button 
                            v-if="accounts && accounts.length > 0"
                            type="button" 
                            class="btn btn-primary"
                            @click="copyAllAccounts"
                        >
                            <i class="bx bx-copy me-2"></i>
                            Copy tất cả
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Thêm/Sửa Sản Phẩm -->
        <div class="modal fade" id="sanPhamModal" tabindex="-1" aria-labelledby="sanPhamModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="sanPhamModalLabel">
                            <i class="bx bx-shopping-bag me-2"></i>
                            {{ modalTitle }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveData">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.ten"
                                        placeholder="Nhập tên sản phẩm"
                                        required
                                    >
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Username</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.username"
                                        placeholder="Nhập username"
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                                    <select 
                                        class="form-select" 
                                        v-model="formData.danh_muc_id"
                                        required
                                    >
                                        <option value="">-- Chọn danh mục --</option>
                                        <option v-for="dm in danhMucs" :key="dm.id" :value="dm.id">
                                            {{ dm.ten }}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Giá <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.gia"
                                        placeholder="Nhập giá"
                                        min="0"
                                        step="1000"
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Số lượng <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        v-model.number="formData.so_luong"
                                        placeholder="Nhập số lượng"
                                        min="0"
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Cờ</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="formData.co"
                                        placeholder="VD: VN, US, etc."
                                        maxlength="10"
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Check Live</label>
                                    <div class="form-check form-switch mt-2">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            id="checkLive"
                                            v-model="formData.check_live"
                                        >
                                        <label class="form-check-label" for="checkLive">
                                            {{ formData.check_live ? 'Có' : 'Không' }}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Mô tả</label>
                                    <textarea 
                                        class="form-control" 
                                        v-model="formData.mo_ta"
                                        rows="3"
                                        placeholder="Nhập mô tả ngắn"
                                    ></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Nội dung</label>
                                    <textarea 
                                        class="form-control" 
                                        v-model="formData.noi_dung"
                                        rows="5"
                                        placeholder="Nhập nội dung chi tiết"
                                    ></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Trạng thái</label>
                                    <div class="form-check form-switch">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            id="trangThai"
                                            v-model="formData.trang_thai"
                                        >
                                        <label class="form-check-label" for="trangThai">
                                            {{ formData.trang_thai ? 'Hiển thị' : 'Ẩn' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-2"></i>
                            Hủy
                        </button>
                        <button 
                            type="button" 
                            class="btn btn-primary" 
                            @click="saveData"
                            :disabled="saving"
                        >
                            <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
                            <i v-else class="bx bx-save me-2"></i>
                            {{ saving ? 'Đang lưu...' : 'Lưu' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Xác nhận xóa -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold">
                            <i class="bx bx-trash me-2"></i>
                            Xác nhận xóa
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa sản phẩm <strong>{{ selectedItem?.ten }}</strong>?</p>
                        <div class="alert alert-warning mb-0">
                            <i class="bx bx-info-circle me-2"></i>
                            Lưu ý: Hành động này không thể hoàn tác!
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-2"></i>
                            Hủy
                        </button>
                        <button 
                            type="button" 
                            class="btn btn-danger" 
                            @click="deleteData"
                            :disabled="deleting"
                        >
                            <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
                            <i v-else class="bx bx-trash me-2"></i>
                            {{ deleting ? 'Đang xóa...' : 'Xóa' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { sanPhamService } from '@/services/sanPhamService';
import { chiTietTaiKhoanService } from '@/services/chiTietTaiKhoanService';
import { danhMucService } from '@/services/danhMucService';
import api from '@/services/api';

export default {
    name: 'DanhSachSanPham',
    data() {
        return {
            sanPhams: [],
            loading: false,
            searchQuery: '',
            perPage: 10,
            currentPage: 1,
            totalPages: 1,
            // Modal quản lý tài khoản
            accountModal: null,
            selectedSanPham: null,
            accounts: [],
            loadingAccounts: false,
            activeTab: 'list', // 'list' or 'add'
            // Form thêm tài khoản
            newAccountsText: '',
            addAccountOptions: {
                chi_them_chua_ban: false,
                khong_loc_trung: false,
            },
            addingAccounts: false,
            addAccountResult: null,
            // Modal sản phẩm
            sanPhamModal: null,
            deleteModal: null,
            modalTitle: 'Thêm Sản Phẩm',
            formData: {
                id: null,
                ten: '',
                username: '',
                danh_muc_id: '',
                mo_ta: '',
                noi_dung: '',
                gia: 0,
                so_luong: 0,
                co: 'VN',
                check_live: false,
                trang_thai: true,
            },
            selectedItem: null,
            saving: false,
            deleting: false,
            danhMucs: [],
            searchTimeout: null,
        };
    },
    async mounted() {
        await this.loadDanhMucs();
        this.loadData();
        // Initialize modals
        this.$nextTick(() => {
            if (window.bootstrap) {
                this.accountModal = new window.bootstrap.Modal(document.getElementById('accountModal'));
                this.sanPhamModal = new window.bootstrap.Modal(document.getElementById('sanPhamModal'));
                this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
            } else {
                setTimeout(() => {
                    if (window.bootstrap) {
                        this.accountModal = new window.bootstrap.Modal(document.getElementById('accountModal'));
                        this.sanPhamModal = new window.bootstrap.Modal(document.getElementById('sanPhamModal'));
                        this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
                    }
                }, 500);
            }
        });
    },
    methods: {
        async loadData() {
            this.loading = true;
            try {
                const params = {
                    search: this.searchQuery,
                    per_page: this.perPage,
                    page: this.currentPage,
                };
                const response = await sanPhamService.getData(params);
                if (response.data.success) {
                    this.sanPhams = response.data.data.data || [];
                    this.currentPage = response.data.data.current_page || 1;
                    this.totalPages = response.data.data.last_page || 1;
                }
            } catch (error) {
                console.error('Error loading data:', error);
                alert('Có lỗi xảy ra khi tải dữ liệu');
            } finally {
                this.loading = false;
            }
        },
        async openAccountModal(sanPham) {
            this.selectedSanPham = sanPham;
            this.accounts = [];
            this.activeTab = 'list';
            this.newAccountsText = '';
            this.addAccountResult = null;
            this.loadingAccounts = true;
            
            // Show modal
            if (this.accountModal) {
                this.accountModal.show();
            } else {
                this.$nextTick(() => {
                    if (window.bootstrap) {
                        this.accountModal = new window.bootstrap.Modal(document.getElementById('accountModal'));
                        this.accountModal.show();
                    }
                });
            }
            
            // Load accounts
            await this.loadAccounts(sanPham.id);
        },
        async addAccounts() {
            if (!this.selectedSanPham || !this.newAccountsText.trim()) {
                alert('Vui lòng nhập danh sách tài khoản');
                return;
            }

            this.addingAccounts = true;
            this.addAccountResult = null;

            try {
                const response = await chiTietTaiKhoanService.storeMultiple({
                    san_pham_id: this.selectedSanPham.id,
                    accounts: this.newAccountsText,
                    chi_them_chua_ban: this.addAccountOptions.chi_them_chua_ban,
                    khong_loc_trung: this.addAccountOptions.khong_loc_trung,
                });

                if (response.data.success) {
                    this.addAccountResult = {
                        success: true,
                        message: response.data.message,
                        data: response.data.data,
                    };
                    
                    // Xóa textarea sau khi thêm thành công
                    this.newAccountsText = '';
                    
                    // Reload danh sách tài khoản
                    await this.loadAccounts(this.selectedSanPham.id);
                    
                    // Chuyển sang tab danh sách sau 2 giây
                    setTimeout(() => {
                        this.activeTab = 'list';
                    }, 2000);
                } else {
                    this.addAccountResult = {
                        success: false,
                        message: response.data.message || 'Có lỗi xảy ra khi thêm tài khoản',
                    };
                }
            } catch (error) {
                console.error('Error adding accounts:', error);
                this.addAccountResult = {
                    success: false,
                    message: error.response?.data?.message || 'Có lỗi xảy ra khi thêm tài khoản',
                    data: error.response?.data?.data,
                };
            } finally {
                this.addingAccounts = false;
            }
        },
        async loadAccounts(sanPhamId) {
            this.loadingAccounts = true;
            try {
                const response = await chiTietTaiKhoanService.getData({
                    san_pham_id: sanPhamId,
                    per_page: 1000, // Load tất cả tài khoản
                });
                if (response.data.success) {
                    this.accounts = response.data.data.data || [];
                }
            } catch (error) {
                console.error('Error loading accounts:', error);
                alert('Có lỗi xảy ra khi tải danh sách tài khoản');
            } finally {
                this.loadingAccounts = false;
            }
        },
        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show toast notification
                this.showToast('Đã copy: ' + text.substring(0, 30) + '...');
            }).catch(() => {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                this.showToast('Đã copy: ' + text.substring(0, 30) + '...');
            });
        },
        copyAllAccounts() {
            if (!this.accounts || this.accounts.length === 0) return;
            
            let allInfo = `Danh sách tài khoản - ${this.selectedSanPham?.ten || 'N/A'}\n`;
            allInfo += '='.repeat(50) + '\n\n';
            
            this.accounts.forEach((account, index) => {
                allInfo += `Tài khoản #${index + 1}:\n`;
                allInfo += `Username: ${account.username}\n`;
                allInfo += `Password: ${account.password}\n`;
                if (account.thong_tin_bo_sung) {
                    allInfo += `Thông tin bổ sung: ${account.thong_tin_bo_sung}\n`;
                }
                allInfo += '\n' + '-'.repeat(50) + '\n\n';
            });
            
            this.copyToClipboard(allInfo);
        },
        async loadDanhMucs() {
            try {
                const response = await danhMucService.getDataOpen();
                if (response.data.success) {
                    this.danhMucs = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading danh mucs:', error);
            }
        },
        openModal(mode, item = null) {
            if (mode === 'create') {
                this.modalTitle = 'Thêm Sản Phẩm';
                this.formData = {
                    id: null,
                    ten: '',
                    username: '',
                    danh_muc_id: '',
                    mo_ta: '',
                    noi_dung: '',
                    gia: 0,
                    so_luong: 0,
                    co: 'VN',
                    check_live: false,
                    trang_thai: true,
                };
            } else {
                this.modalTitle = 'Sửa Sản Phẩm';
                this.formData = {
                    id: item.id,
                    ten: item.ten || '',
                    username: item.username || '',
                    danh_muc_id: item.danh_muc_id || '',
                    mo_ta: item.mo_ta || '',
                    noi_dung: item.noi_dung || '',
                    gia: item.gia || 0,
                    so_luong: item.so_luong || 0,
                    co: item.co || 'VN',
                    check_live: item.check_live || false,
                    trang_thai: item.trang_thai !== undefined ? item.trang_thai : true,
                };
            }
            if (this.sanPhamModal) {
                this.sanPhamModal.show();
            } else {
                this.$nextTick(() => {
                    if (window.bootstrap) {
                        this.sanPhamModal = new window.bootstrap.Modal(document.getElementById('sanPhamModal'));
                        this.sanPhamModal.show();
                    }
                });
            }
        },
        async saveData() {
            this.saving = true;
            try {
                if (this.formData.id) {
                    await sanPhamService.update(this.formData.id, this.formData);
                } else {
                    await sanPhamService.create(this.formData);
                }
                if (this.sanPhamModal) {
                    this.sanPhamModal.hide();
                }
                this.loadData();
                this.showToast('Lưu thành công!');
            } catch (error) {
                console.error('Error saving data:', error);
                alert(error.response?.data?.message || 'Có lỗi xảy ra khi lưu dữ liệu');
            } finally {
                this.saving = false;
            }
        },
        confirmDelete(item) {
            this.selectedItem = item;
            if (this.deleteModal) {
                this.deleteModal.show();
            } else {
                this.$nextTick(() => {
                    if (window.bootstrap) {
                        this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
                        this.deleteModal.show();
                    }
                });
            }
        },
        async deleteData() {
            this.deleting = true;
            try {
                await sanPhamService.delete(this.selectedItem.id);
                if (this.deleteModal) {
                    this.deleteModal.hide();
                }
                this.loadData();
                this.showToast('Xóa thành công!');
            } catch (error) {
                console.error('Error deleting data:', error);
                alert(error.response?.data?.message || 'Có lỗi xảy ra khi xóa dữ liệu');
            } finally {
                this.deleting = false;
            }
        },
        showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.textContent = message;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #28a745;
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => document.body.removeChild(toast), 300);
            }, 2000);
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.loadData();
            }
        },
    },
    watch: {
        searchQuery() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.currentPage = 1;
                this.loadData();
            }, 500);
        },
    },
}
</script>

<style scoped>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
}

.page-title {
    color: #2c3e50;
    font-size: 1.75rem;
    font-weight: 700;
}

/* Stats Cards */
.stats-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent !important;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.stats-card:nth-child(1):hover {
    border-left-color: #0d6efd !important;
}

.stats-card:nth-child(2):hover {
    border-left-color: #198754 !important;
}

.stats-card:nth-child(3):hover {
    border-left-color: #0dcaf0 !important;
}

.stats-card:nth-child(4):hover {
    border-left-color: #ffc107 !important;
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* Table Styles */
.table {
    font-size: 0.95rem;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem 0.75rem;
}

.table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.table-row-hover {
    transition: all 0.2s ease;
}

.table-row-hover:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Avatar */
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
}

/* Badge Styles */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 0.375rem;
}

/* Content Text */
.content-text {
    font-size: 0.9rem;
    line-height: 1.5;
    color: #6c757d;
}

/* Flag Icon */
.flag-icon {
    font-size: 1.75rem;
    line-height: 1;
}

/* Button Groups */
.btn-group .btn {
    white-space: nowrap;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Pagination */
.pagination .page-link {
    border-radius: 0.375rem;
    margin: 0 0.125rem;
    border: 1px solid #dee2e6;
    color: #495057;
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Search Input */
.input-group-text {
    border-color: #ced4da;
}

.input-group:focus-within .input-group-text {
    border-color: #86b7fe;
    background-color: #f8f9fa;
}

/* Card Enhancements */
.card {
    border-radius: 0.75rem;
    overflow: hidden;
}

.card-header {
    background-color: #ffffff;
    border-bottom: 2px solid #e9ecef;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        width: 100%;
        margin-bottom: 0.25rem;
        border-radius: 0.375rem !important;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 0.5rem;
    }
    
    .pagination {
        flex-wrap: wrap;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.3s ease;
}

/* Custom Scrollbar */
.content-text::-webkit-scrollbar {
    width: 6px;
}

.content-text::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.content-text::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.content-text::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Toast Notification */
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

code {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    word-break: break-all;
}
</style>

                        success: true,
                        message: response.data.message,
                        data: response.data.data,
                    };
                    
                    // Xóa textarea sau khi thêm thành công
                    this.newAccountsText = '';
                    
                    // Reload danh sách tài khoản
                    await this.loadAccounts(this.selectedSanPham.id);
                    
                    // Chuyển sang tab danh sách sau 2 giây
                    setTimeout(() => {
                        this.activeTab = 'list';
                    }, 2000);
                } else {
                    this.addAccountResult = {
                        success: false,
                        message: response.data.message || 'Có lỗi xảy ra khi thêm tài khoản',
                    };
                }
            } catch (error) {
                console.error('Error adding accounts:', error);
                this.addAccountResult = {
                    success: false,
                    message: error.response?.data?.message || 'Có lỗi xảy ra khi thêm tài khoản',
                    data: error.response?.data?.data,
                };
            } finally {
                this.addingAccounts = false;
            }
        },
        async loadAccounts(sanPhamId) {
            this.loadingAccounts = true;
            try {
                const response = await chiTietTaiKhoanService.getData({
                    san_pham_id: sanPhamId,
                    per_page: 1000, // Load tất cả tài khoản
                });
                if (response.data.success) {
                    this.accounts = response.data.data.data || [];
                }
            } catch (error) {
                console.error('Error loading accounts:', error);
                alert('Có lỗi xảy ra khi tải danh sách tài khoản');
            } finally {
                this.loadingAccounts = false;
            }
        },
        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show toast notification
                this.showToast('Đã copy: ' + text.substring(0, 30) + '...');
            }).catch(() => {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                this.showToast('Đã copy: ' + text.substring(0, 30) + '...');
            });
        },
        copyAllAccounts() {
            if (!this.accounts || this.accounts.length === 0) return;
            
            let allInfo = `Danh sách tài khoản - ${this.selectedSanPham?.ten || 'N/A'}\n`;
            allInfo += '='.repeat(50) + '\n\n';
            
            this.accounts.forEach((account, index) => {
                allInfo += `Tài khoản #${index + 1}:\n`;
                allInfo += `Username: ${account.username}\n`;
                allInfo += `Password: ${account.password}\n`;
                if (account.thong_tin_bo_sung) {
                    allInfo += `Thông tin bổ sung: ${account.thong_tin_bo_sung}\n`;
                }
                allInfo += '\n' + '-'.repeat(50) + '\n\n';
            });
            
            this.copyToClipboard(allInfo);
        },
        async loadDanhMucs() {
            try {
                const response = await danhMucService.getDataOpen();
                if (response.data.success) {
                    this.danhMucs = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading danh mucs:', error);
            }
        },
        openModal(mode, item = null) {
            if (mode === 'create') {
                this.modalTitle = 'Thêm Sản Phẩm';
                this.formData = {
                    id: null,
                    ten: '',
                    username: '',
                    danh_muc_id: '',
                    mo_ta: '',
                    noi_dung: '',
                    gia: 0,
                    so_luong: 0,
                    co: 'VN',
                    check_live: false,
                    trang_thai: true,
                };
            } else {
                this.modalTitle = 'Sửa Sản Phẩm';
                this.formData = {
                    id: item.id,
                    ten: item.ten || '',
                    username: item.username || '',
                    danh_muc_id: item.danh_muc_id || '',
                    mo_ta: item.mo_ta || '',
                    noi_dung: item.noi_dung || '',
                    gia: item.gia || 0,
                    so_luong: item.so_luong || 0,
                    co: item.co || 'VN',
                    check_live: item.check_live || false,
                    trang_thai: item.trang_thai !== undefined ? item.trang_thai : true,
                };
            }
            if (this.sanPhamModal) {
                this.sanPhamModal.show();
            } else {
                this.$nextTick(() => {
                    if (window.bootstrap) {
                        this.sanPhamModal = new window.bootstrap.Modal(document.getElementById('sanPhamModal'));
                        this.sanPhamModal.show();
                    }
                });
            }
        },
        async saveData() {
            this.saving = true;
            try {
                if (this.formData.id) {
                    await sanPhamService.update(this.formData.id, this.formData);
                } else {
                    await sanPhamService.create(this.formData);
                }
                if (this.sanPhamModal) {
                    this.sanPhamModal.hide();
                }
                this.loadData();
                this.showToast('Lưu thành công!');
            } catch (error) {
                console.error('Error saving data:', error);
                alert(error.response?.data?.message || 'Có lỗi xảy ra khi lưu dữ liệu');
            } finally {
                this.saving = false;
            }
        },
        confirmDelete(item) {
            this.selectedItem = item;
            if (this.deleteModal) {
                this.deleteModal.show();
            } else {
                this.$nextTick(() => {
                    if (window.bootstrap) {
                        this.deleteModal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
                        this.deleteModal.show();
                    }
                });
            }
        },
        async deleteData() {
            this.deleting = true;
            try {
                await sanPhamService.delete(this.selectedItem.id);
                if (this.deleteModal) {
                    this.deleteModal.hide();
                }
                this.loadData();
                this.showToast('Xóa thành công!');
            } catch (error) {
                console.error('Error deleting data:', error);
                alert(error.response?.data?.message || 'Có lỗi xảy ra khi xóa dữ liệu');
            } finally {
                this.deleting = false;
            }
        },
        showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.textContent = message;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #28a745;
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => document.body.removeChild(toast), 300);
            }, 2000);
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.loadData();
            }
        },
    },
    watch: {
        searchQuery() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.currentPage = 1;
                this.loadData();
            }, 500);
        },
    },
}
</script>

<style scoped>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
}

.page-title {
    color: #2c3e50;
    font-size: 1.75rem;
    font-weight: 700;
}

/* Stats Cards */
.stats-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent !important;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.stats-card:nth-child(1):hover {
    border-left-color: #0d6efd !important;
}

.stats-card:nth-child(2):hover {
    border-left-color: #198754 !important;
}

.stats-card:nth-child(3):hover {
    border-left-color: #0dcaf0 !important;
}

.stats-card:nth-child(4):hover {
    border-left-color: #ffc107 !important;
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* Table Styles */
.table {
    font-size: 0.95rem;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem 0.75rem;
}

.table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.table-row-hover {
    transition: all 0.2s ease;
}

.table-row-hover:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Avatar */
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
}

/* Badge Styles */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 0.375rem;
}

/* Content Text */
.content-text {
    font-size: 0.9rem;
    line-height: 1.5;
    color: #6c757d;
}

/* Flag Icon */
.flag-icon {
    font-size: 1.75rem;
    line-height: 1;
}

/* Button Groups */
.btn-group .btn {
    white-space: nowrap;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Pagination */
.pagination .page-link {
    border-radius: 0.375rem;
    margin: 0 0.125rem;
    border: 1px solid #dee2e6;
    color: #495057;
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Search Input */
.input-group-text {
    border-color: #ced4da;
}

.input-group:focus-within .input-group-text {
    border-color: #86b7fe;
    background-color: #f8f9fa;
}

/* Card Enhancements */
.card {
    border-radius: 0.75rem;
    overflow: hidden;
}

.card-header {
    background-color: #ffffff;
    border-bottom: 2px solid #e9ecef;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        width: 100%;
        margin-bottom: 0.25rem;
        border-radius: 0.375rem !important;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 0.5rem;
    }
    
    .pagination {
        flex-wrap: wrap;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.3s ease;
}

/* Custom Scrollbar */
.content-text::-webkit-scrollbar {
    width: 6px;
}

.content-text::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.content-text::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.content-text::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Toast Notification */
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

code {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    word-break: break-all;
}
</style>