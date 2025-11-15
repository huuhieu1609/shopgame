<template>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold">Quản Lý Cộng Tác Viên</h2>
            <button class="btn btn-primary">
                <i class="bx bx-plus me-2"></i>
                Thêm Mới
            </button>
        </div>

        <!-- Search Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tìm Kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bx bx-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Tìm kiếm theo tên, email, username...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Phân Quyền</label>
                        <select class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1">Admin</option>
                            <option value="2">CTV</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Trạng Thái</label>
                        <select class="form-select">
                            <option value="">Tất cả</option>
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Tạm khóa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Hiển Thị</label>
                        <select class="form-select">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">Tất cả</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th style="width: 150px;">Nhân Viên</th>
                                <th style="width: 120px;">Tài Khoản</th>
                                <th style="width: 200px;">Thông Tin Liên Hệ</th>
                                <th class="text-center" style="width: 120px;">Phân Quyền</th>
                                <th class="text-center" style="width: 100px;">Trạng Thái</th>
                                <th class="text-center" style="width: 150px;">Ngày Tạo</th>
                                <th class="text-center" style="width: 150px;">Cập Nhật</th>
                                <th class="text-center" style="width: 180px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center fw-semibold">1</td>
                                <td>
                                    <div class="fw-bold">Nguyễn Văn Admin</div>
                                    <small class="text-muted">ID: 1</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">admin</div>
                                    <small class="text-muted">Password: **********</small>
                                </td>
                                <td>
                                    <div>
                                        <i class="bx bx-envelope me-1 text-muted"></i>
                                        <small>admin@email.com</small>
                                    </div>
                                    <div class="mt-1">
                                        <i class="bx bx-phone me-1 text-muted"></i>
                                        <small>0123456789</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger">Admin</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success">Hoạt động</button>
                                </td>
                                <td class="text-center">
                                    <small>01/01/2024 08:00</small>
                                </td>
                                <td class="text-center">
                                    <small>15/01/2024 10:30</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info text-white" title="Xem chi tiết">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center fw-semibold">2</td>
                                <td>
                                    <div class="fw-bold">Trần Thị CTV</div>
                                    <small class="text-muted">ID: 2</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">ctv001</div>
                                    <small class="text-muted">Password: **********</small>
                                </td>
                                <td>
                                    <div>
                                        <i class="bx bx-envelope me-1 text-muted"></i>
                                        <small>ctv001@email.com</small>
                                    </div>
                                    <div class="mt-1">
                                        <i class="bx bx-phone me-1 text-muted"></i>
                                        <small>0987654321</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info">CTV</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success">Hoạt động</button>
                                </td>
                                <td class="text-center">
                                    <small>05/01/2024 09:15</small>
                                </td>
                                <td class="text-center">
                                    <small>14/01/2024 16:20</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info text-white" title="Xem chi tiết">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center fw-semibold">3</td>
                                <td>
                                    <div class="fw-bold">Lê Văn CTV</div>
                                    <small class="text-muted">ID: 3</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">ctv002</div>
                                    <small class="text-muted">Password: **********</small>
                                </td>
                                <td>
                                    <div>
                                        <i class="bx bx-envelope me-1 text-muted"></i>
                                        <small>ctv002@email.com</small>
                                    </div>
                                    <div class="mt-1">
                                        <i class="bx bx-phone me-1 text-muted"></i>
                                        <small>0912345678</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info">CTV</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success">Hoạt động</button>
                                </td>
                                <td class="text-center">
                                    <small>08/01/2024 10:30</small>
                                </td>
                                <td class="text-center">
                                    <small>13/01/2024 11:45</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info text-white" title="Xem chi tiết">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center fw-semibold">4</td>
                                <td>
                                    <div class="fw-bold">Phạm Thị CTV</div>
                                    <small class="text-muted">ID: 4</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">ctv003</div>
                                    <small class="text-muted">Password: **********</small>
                                </td>
                                <td>
                                    <div>
                                        <i class="bx bx-envelope me-1 text-muted"></i>
                                        <small>ctv003@email.com</small>
                                    </div>
                                    <div class="mt-1">
                                        <i class="bx bx-phone me-1 text-muted"></i>
                                        <small>0923456789</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info">CTV</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-secondary">Tạm khóa</button>
                                </td>
                                <td class="text-center">
                                    <small>10/01/2024 14:20</small>
                                </td>
                                <td class="text-center">
                                    <small>12/01/2024 09:10</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info text-white" title="Xem chi tiết">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center fw-semibold">5</td>
                                <td>
                                    <div class="fw-bold">Hoàng Văn CTV</div>
                                    <small class="text-muted">ID: 5</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">ctv004</div>
                                    <small class="text-muted">Password: **********</small>
                                </td>
                                <td>
                                    <div>
                                        <i class="bx bx-envelope me-1 text-muted"></i>
                                        <small>ctv004@email.com</small>
                                    </div>
                                    <div class="mt-1">
                                        <i class="bx bx-phone me-1 text-muted"></i>
                                        <small>0934567890</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info">CTV</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success">Hoạt động</button>
                                </td>
                                <td class="text-center">
                                    <small>12/01/2024 11:00</small>
                                </td>
                                <td class="text-center">
                                    <small>15/01/2024 15:30</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info text-white" title="Xem chi tiết">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0 mt-3">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Trước</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Sau</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CongTacVien'
}
</script>

<style scoped>
/* Chỉ sử dụng Bootstrap 5 - không có CSS tùy chỉnh */
</style>
