<?php

namespace App\Console\Commands;

use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\ChiTietTaiKhoan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignAccountsToOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:assign-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gán tài khoản cho các đơn hàng đã tồn tại nhưng chưa có tài khoản';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra các đơn hàng cần gán tài khoản...');

        // Lấy tất cả đơn hàng
        $donHangs = DonHang::with('chiTietDonHangs')->get();
        
        $totalAssigned = 0;
        $totalSkipped = 0;

        foreach ($donHangs as $donHang) {
            foreach ($donHang->chiTietDonHangs as $chiTietDonHang) {
                // Kiểm tra xem đã có tài khoản được gán chưa
                $existingAccounts = ChiTietTaiKhoan::where('don_hang_id', $donHang->id)
                    ->where('san_pham_id', $chiTietDonHang->san_pham_id)
                    ->count();

                if ($existingAccounts >= $chiTietDonHang->so_luong) {
                    $totalSkipped++;
                    continue; // Đã có đủ tài khoản
                }

                // Số lượng tài khoản cần gán
                $soLuongCanGan = $chiTietDonHang->so_luong - $existingAccounts;

                // Lấy tài khoản còn hàng cho sản phẩm này
                $taiKhoans = ChiTietTaiKhoan::where('san_pham_id', $chiTietDonHang->san_pham_id)
                    ->where('trang_thai', true) // Chỉ lấy tài khoản còn hàng
                    ->whereNull('don_hang_id') // Chưa được gán cho đơn hàng nào
                    ->limit($soLuongCanGan)
                    ->get();

                if ($taiKhoans->count() < $soLuongCanGan) {
                    $this->warn("Đơn hàng #{$donHang->id} - Sản phẩm #{$chiTietDonHang->san_pham_id}: Không đủ tài khoản. Cần: {$soLuongCanGan}, Có: {$taiKhoans->count()}");
                    continue;
                }

                // Gán tài khoản cho đơn hàng
                DB::beginTransaction();
                try {
                    foreach ($taiKhoans as $taiKhoan) {
                        $taiKhoan->trang_thai = false; // Đánh dấu đã bán
                        $taiKhoan->don_hang_id = $donHang->id;
                        $taiKhoan->save();
                    }

                    DB::commit();
                    $totalAssigned += $taiKhoans->count();
                    $this->info("Đã gán {$taiKhoans->count()} tài khoản cho đơn hàng #{$donHang->id} - Sản phẩm #{$chiTietDonHang->san_pham_id}");
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->error("Lỗi khi gán tài khoản cho đơn hàng #{$donHang->id}: " . $e->getMessage());
                }
            }
        }

        $this->info("\nHoàn thành!");
        $this->info("Tổng số tài khoản đã gán: {$totalAssigned}");
        $this->info("Số mục đã có đủ tài khoản: {$totalSkipped}");

        return Command::SUCCESS;
    }
}
