<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat data dummy untuk items dan item_logs dari Mei - Desember 2025
     * Updated: Dengan placement_type dan breakdown jumlah per kondisi
     */
    public function run(): void
    {
        // Ambil user yang ada (skip admin)
        $users = User::where('role', 'user')->get();

        if ($users->count() < 2) {
            $this->command->warn('Kurang dari 2 user ditemukan. Menambahkan user tambahan...');

            if (!User::where('email', 'user1@user.com')->exists()) {
                User::create([
                    'name' => 'Petugas Inventaris 1',
                    'email' => 'user1@user.com',
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'role' => 'user',
                    'is_active' => true,
                ]);
            }

            if (!User::where('email', 'user2@user.com')->exists()) {
                User::create([
                    'name' => 'Petugas Inventaris 2',
                    'email' => 'user2@user.com',
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'role' => 'user',
                    'is_active' => true,
                ]);
            }

            $users = User::where('role', 'user')->get();
        }

        // Daftar kategori barang
        $categories = ['Elektronik', 'Furniture', 'ATK', 'Peralatan Kebersihan', 'Perlengkapan Kantor', 'Peralatan Olahraga', 'Alat Musik', 'Peralatan Lab'];

        // Daftar lokasi
        $locations = ['Ruang Guru', 'Lab Komputer', 'Perpustakaan', 'Gudang', 'UKS', 'Aula', 'Ruang TU', 'Lab IPA', 'Lab Bahasa'];

        // Penempatan
        $placementTypes = ['dalam_ruang', 'dalam_lemari'];

        // Daftar nama barang dummy
        $itemNames = [
            'Elektronik' => ['Laptop', 'Printer', 'Proyektor', 'Monitor', 'Keyboard', 'Mouse', 'Speaker', 'Webcam'],
            'Furniture' => ['Meja', 'Kursi', 'Lemari', 'Rak Buku', 'Papan Tulis', 'Loker', 'Meja Komputer'],
            'ATK' => ['Stapler', 'Gunting', 'Pembolong Kertas', 'Cutter', 'Kalkulator', 'Penggaris', 'Stempel'],
            'Peralatan Kebersihan' => ['Sapu', 'Pel', 'Vakum', 'Tempat Sampah', 'Kemoceng', 'Ember'],
            'Perlengkapan Kantor' => ['Dispenser', 'Jam Dinding', 'Telepon', 'AC', 'Kipas Angin', 'Whiteboard'],
            'Peralatan Olahraga' => ['Bola Basket', 'Bola Voli', 'Matras', 'Raket', 'Bola Futsal', 'Net Badminton'],
            'Alat Musik' => ['Gitar', 'Piano', 'Keyboard', 'Biola', 'Drum', 'Suling'],
            'Peralatan Lab' => ['Mikroskop', 'Tabung Reaksi', 'Jas Lab', 'Neraca Digital', 'Pipet', 'Bunsen Burner'],
        ];

        $this->command->info("Memulai seeding data inventaris (Mei - Des 2025)...");
        $this->command->info("Dengan struktur baru: placement_type dan breakdown kondisi");

        foreach ($users as $uIndex => $user) {
            $this->command->info("Memproses Petugas: {$user->name}");

            for ($month = 5; $month <= 12; $month++) {
                // Tentukan jumlah barang per bulan (4-6 barang)
                $itemCount = rand(4, 6);

                for ($i = 1; $i <= $itemCount; $i++) {
                    $category = $categories[array_rand($categories)];
                    $baseName = $itemNames[$category][array_rand($itemNames[$category])];
                    $location = $locations[array_rand($locations)];
                    $placementType = $placementTypes[array_rand($placementTypes)];

                    // Buat tanggal acak di bulan tersebut tahun 2025
                    $day = rand(1, 28);
                    $date = Carbon::create(2025, $month, $day, rand(8, 16), rand(0, 59));

                    // Generate breakdown jumlah per kondisi
                    // Most items should be in good condition with some variations
                    $totalQty = rand(5, 30);
                    $scenario = rand(1, 10); // Random scenario for variety

                    if ($scenario <= 6) {
                        // 60% chance: Semua baik
                        $qtyBaik = $totalQty;
                        $qtyRusak = 0;
                        $qtyHilang = 0;
                        $condition = 'baik';
                    } elseif ($scenario <= 8) {
                        // 20% chance: Sebagian rusak
                        $qtyRusak = rand(1, max(1, intval($totalQty * 0.3)));
                        $qtyHilang = rand(0, max(0, intval($totalQty * 0.1)));
                        $qtyBaik = $totalQty - $qtyRusak - $qtyHilang;
                        $condition = 'sebagian_rusak';
                    } elseif ($scenario == 9) {
                        // 10% chance: Semua rusak
                        $qtyBaik = 0;
                        $qtyRusak = $totalQty;
                        $qtyHilang = 0;
                        $condition = 'rusak';
                    } else {
                        // 10% chance: Ada yang hilang
                        $qtyHilang = rand(1, max(1, intval($totalQty * 0.2)));
                        $qtyRusak = rand(0, max(0, intval($totalQty * 0.2)));
                        $qtyBaik = $totalQty - $qtyRusak - $qtyHilang;
                        $condition = ($qtyBaik == 0 && $qtyRusak == 0) ? 'hilang' : 'sebagian_rusak';
                    }

                    // Create Item
                    $item = Item::create([
                        'name' => $baseName . ' #' . ($uIndex + 1) . '-' . $month . $i,
                        'category' => $category,
                        'location' => $location,
                        'placement_type' => $placementType,
                        'quantity' => $totalQty,
                        'qty_baik' => $qtyBaik,
                        'qty_rusak' => $qtyRusak,
                        'qty_hilang' => $qtyHilang,
                        'condition' => $condition,
                        'user_id' => $user->id,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    // Create Log (Awal)
                    ItemLog::create([
                        'item_id' => $item->id,
                        'user_id' => $user->id,
                        'action' => 'create',
                        'new_condition' => $condition,
                        'new_quantity' => $totalQty,
                        'description' => "Input barang awal (Baik: {$qtyBaik}, Rusak: {$qtyRusak}, Hilang: {$qtyHilang})",
                        'created_at' => $date,
                    ]);

                    // Random Update di bulan yang sama atau bulan berikutnya (30% chance)
                    if (rand(1, 100) <= 30) {
                        $updateDate = (clone $date)->addDays(rand(5, 20));
                        if ($updateDate->month <= 12 && $updateDate->year == 2025) {
                            $oldCondition = $item->condition;
                            $oldQty = $item->quantity;
                            $oldQtyBaik = $item->qty_baik;
                            $oldQtyRusak = $item->qty_rusak;
                            $oldQtyHilang = $item->qty_hilang;

                            // Simulate some damage or loss
                            $updateScenario = rand(1, 4);

                            if ($updateScenario == 1 && $qtyBaik > 0) {
                                // Some items got damaged
                                $damaged = rand(1, min(3, $qtyBaik));
                                $qtyBaik -= $damaged;
                                $qtyRusak += $damaged;
                                $updateNote = "Kerusakan: {$damaged} unit rusak";
                            } elseif ($updateScenario == 2 && $qtyBaik > 0) {
                                // Some items went missing
                                $lost = rand(1, min(2, $qtyBaik));
                                $qtyBaik -= $lost;
                                $qtyHilang += $lost;
                                $updateNote = "Kehilangan: {$lost} unit hilang";
                            } elseif ($updateScenario == 3 && $qtyRusak > 0) {
                                // Some items got repaired
                                $repaired = rand(1, $qtyRusak);
                                $qtyRusak -= $repaired;
                                $qtyBaik += $repaired;
                                $updateNote = "Perbaikan: {$repaired} unit diperbaiki";
                            } else {
                                // New stock added
                                $added = rand(2, 5);
                                $qtyBaik += $added;
                                $totalQty += $added;
                                $updateNote = "Penambahan stok: {$added} unit baru";
                            }

                            // Recalculate condition
                            $newTotalQty = $qtyBaik + $qtyRusak + $qtyHilang;
                            if ($newTotalQty == 0) {
                                $newCondition = 'baik';
                            } elseif ($qtyHilang == $newTotalQty) {
                                $newCondition = 'hilang';
                            } elseif ($qtyRusak == $newTotalQty) {
                                $newCondition = 'rusak';
                            } elseif ($qtyBaik == $newTotalQty) {
                                $newCondition = 'baik';
                            } else {
                                $newCondition = 'sebagian_rusak';
                            }

                            $item->update([
                                'quantity' => $newTotalQty,
                                'qty_baik' => $qtyBaik,
                                'qty_rusak' => $qtyRusak,
                                'qty_hilang' => $qtyHilang,
                                'condition' => $newCondition,
                                'updated_at' => $updateDate
                            ]);

                            ItemLog::create([
                                'item_id' => $item->id,
                                'user_id' => $user->id,
                                'action' => 'update',
                                'old_condition' => $oldCondition,
                                'new_condition' => $newCondition,
                                'old_quantity' => $oldQty,
                                'new_quantity' => $newTotalQty,
                                'description' => "{$updateNote} (Baik: {$qtyBaik}, Rusak: {$qtyRusak}, Hilang: {$qtyHilang})",
                                'created_at' => $updateDate,
                            ]);
                        }
                    }
                }
            }
        }

        $totalItems = Item::count();
        $totalLogs = ItemLog::count();

        $this->command->info("================================");
        $this->command->info("Data Dummy Berhasil Dibuat!");
        $this->command->info("Total Items: {$totalItems}");
        $this->command->info("Total Logs: {$totalLogs}");
        $this->command->info("================================");

        // Show breakdown by placement type
        $dalamRuang = Item::where('placement_type', 'dalam_ruang')->count();
        $dalamLemari = Item::where('placement_type', 'dalam_lemari')->count();
        $this->command->info("Dalam Ruang: {$dalamRuang} items");
        $this->command->info("Dalam Lemari: {$dalamLemari} items");

        // Show breakdown by condition
        $kondisiBaik = Item::where('condition', 'baik')->count();
        $kondisiRusak = Item::where('condition', 'rusak')->count();
        $kondisiHilang = Item::where('condition', 'hilang')->count();
        $kondisiSebagian = Item::where('condition', 'sebagian_rusak')->count();
        $this->command->info("Kondisi Baik: {$kondisiBaik}");
        $this->command->info("Kondisi Rusak: {$kondisiRusak}");
        $this->command->info("Kondisi Hilang: {$kondisiHilang}");
        $this->command->info("Kondisi Sebagian Rusak: {$kondisiSebagian}");
    }
}
