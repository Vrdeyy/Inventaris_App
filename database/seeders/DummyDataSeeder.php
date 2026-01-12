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
        $locations = ['Ruang Guru', 'Lab Komputer', 'Perpustakaan', 'Gudang', 'UKS', 'Aula', 'Ruang TU'];

        // Daftar nama barang dummy
        $itemNames = [
            'Elektronik' => ['Laptop', 'Printer', 'Proyektor', 'Monitor', 'Keyboard', 'Mouse'],
            'Furniture' => ['Meja', 'Kursi', 'Lemari', 'Rak Buku', 'Papan Tulis'],
            'ATK' => ['Stapler', 'Gunting', 'Pembolong Kertas', 'Cutter', 'Kalkulator'],
            'Peralatan Kebersihan' => ['Sapu', 'Pel', 'Vakum', 'Tempat Sampah'],
            'Perlengkapan Kantor' => ['Dispenser', 'Jam Dinding', 'Telepon', 'AC'],
            'Peralatan Olahraga' => ['Bola Basket', 'Bola Voli', 'Matras', 'Raket'],
            'Alat Musik' => ['Gitar', 'Piano', 'Keyboard', 'Biola'],
            'Peralatan Lab' => ['Mikroskop', 'Tabung Reaksi', 'Jas Lab', 'Neraca Digital'],
        ];

        $this->command->info("Memulai seeding data inventaris (Mei - Des 2025)...");

        foreach ($users as $uIndex => $user) {
            $this->command->info("Memproses Petugas: {$user->name}");

            for ($month = 5; $month <= 12; $month++) {
                // Tentukan jumlah barang per bulan (3-5 barang)
                $itemCount = rand(3, 5);

                for ($i = 1; $i <= $itemCount; $i++) {
                    $category = $categories[array_rand($categories)];
                    $baseName = $itemNames[$category][array_rand($itemNames[$category])];
                    $location = $locations[array_rand($locations)];

                    // Buat tanggal acak di bulan tersebut tahun 2025
                    $day = rand(1, 28);
                    $date = Carbon::create(2025, $month, $day, rand(8, 16), rand(0, 59));

                    // Create Item
                    $item = Item::create([
                        'code' => strtoupper(substr($category, 0, 3)) . '-' . rand(1000, 9999),
                        'name' => $baseName . ' #' . ($uIndex + 1) . '-' . $month . $i,
                        'category' => $category,
                        'location' => $location,
                        'quantity' => rand(5, 50),
                        'condition' => 'baik',
                        'user_id' => $user->id,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    // Create Log (Awal)
                    ItemLog::create([
                        'item_id' => $item->id,
                        'user_id' => $user->id,
                        'action' => 'create',
                        'new_condition' => 'baik',
                        'new_quantity' => $item->quantity,
                        'description' => 'Input barang dummy awal',
                        'created_at' => $date,
                    ]);

                    // Random Update di bulan yang sama atau bulan berikutnya
                    if (rand(0, 1)) {
                        $updateDate = (clone $date)->addDays(rand(1, 15));
                        if ($updateDate->month <= 12) {
                            $oldCond = $item->condition;
                            $oldQty = $item->quantity;

                            $newCond = ['baik', 'rusak', 'hilang'][rand(0, 2)];
                            $newQty = max(0, $oldQty + rand(-5, 5));

                            $item->update([
                                'condition' => $newCond,
                                'quantity' => $newQty,
                                'updated_at' => $updateDate
                            ]);

                            ItemLog::create([
                                'item_id' => $item->id,
                                'user_id' => $user->id,
                                'action' => 'update',
                                'old_condition' => $oldCond,
                                'new_condition' => $newCond,
                                'old_quantity' => $oldQty,
                                'new_quantity' => $newQty,
                                'description' => 'Update kondisi rutin (Dummy)',
                                'created_at' => $updateDate,
                            ]);
                        }
                    }
                }
            }
        }

        $this->command->info("Data Dummy Berhasil Dibuat.");
    }
}
