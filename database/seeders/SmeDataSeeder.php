<?php

namespace Database\Seeders;

use App\Models\SmeData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'data_id' => '183', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '300', 'size' => '1.0GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 2, 'data_id' => '184', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '440', 'size' => '1.5GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 3, 'data_id' => '185', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '580', 'size' => '2.0GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 4, 'data_id' => '186', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '860', 'size' => '3.0GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 5, 'data_id' => '188', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '1450', 'size' => '5.0GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 6, 'data_id' => '189', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '2900', 'size' => '10.0GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 7, 'data_id' => '221', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '150', 'size' => '500.0MB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 8, 'data_id' => '229', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '5700', 'size' => '20.0GB', 'validity' => 'Monthly', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 9, 'data_id' => '265', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '1220', 'size' => '4.0GB', 'validity' => '30 day', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 10, 'data_id' => '145', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '950', 'size' => '1.0GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 11, 'data_id' => '147', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '4750', 'size' => '5.0GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 12, 'data_id' => '148', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '9500', 'size' => '10.0GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 13, 'data_id' => '165', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '500', 'size' => '500.0MB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 14, 'data_id' => '193', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '300', 'size' => '300.0MB', 'validity' => '14 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 15, 'data_id' => '226', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '14250', 'size' => '15.0GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 16, 'data_id' => '194', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '440', 'size' => '1.0GB', 'validity' => '30days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 17, 'data_id' => '195', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '880', 'size' => '2.0GB', 'validity' => '30days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 18, 'data_id' => '196', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '1320', 'size' => '3.0GB', 'validity' => '30days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 19, 'data_id' => '197', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '2200', 'size' => '5.0GB', 'validity' => '30days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 20, 'data_id' => '200', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '4400', 'size' => '10.0GB', 'validity' => '30days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 21, 'data_id' => '203', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '220', 'size' => '500.0MB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 22, 'data_id' => '225', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '100', 'size' => '200.0MB', 'validity' => '14 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 23, 'data_id' => '227', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '19000', 'size' => '20.0GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 24, 'data_id' => '283', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '3500', 'size' => '10.0GB', 'validity' => 'Monthly', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 25, 'data_id' => '299', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '45', 'size' => '75.0MB', 'validity' => '7 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 26, 'data_id' => '301', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '200', 'size' => '500.0MB', 'validity' => '7 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 27, 'data_id' => '304', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '2450', 'size' => '7.0GB', 'validity' => '7 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 28, 'data_id' => '308', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '100', 'size' => '100.0 MB', 'validity' => '7 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 29, 'data_id' => '314', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '1900', 'size' => '2.0 GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 30, 'data_id' => '310', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '100', 'size' => '150.0 MB', 'validity' => '1 day', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 31, 'data_id' => '311', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '200', 'size' => '300.0MB', 'validity' => '2 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 32, 'data_id' => '312', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '300', 'size' => '600.0MB', 'validity' => '2 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 33, 'data_id' => '313', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '1050', 'size' => '3.0GB', 'validity' => '7 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 34, 'data_id' => '217', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '2525', 'size' => '6.0 GB', 'validity' => '7 Days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 35, 'data_id' => '307', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '50000', 'size' => '2000 GB', 'validity' => '60 Days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 36, 'data_id' => '309', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '3000', 'size' => '7.0 GB', 'validity' => '7 Days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 37, 'data_id' => '215', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '650', 'size' => '1.0 GB', 'validity' => '1 Days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 38, 'data_id' => '345', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '1555', 'size' => '2.0 GB', 'validity' => '30 Days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 39, 'data_id' => '307', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '500', 'size' => '200.0GB', 'validity' => '60 day +10.5min', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 40, 'data_id' => '216', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '1300', 'size' => '2.0GB', 'validity' => '30 Days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 41, 'data_id' => '364', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '600', 'size' => '1.5 GB', 'validity' => '2 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 42, 'data_id' => '365', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '1000', 'size' => '1.5 GB', 'validity' => '7 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 43, 'data_id' => '362', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '1555', 'size' => '3.0 GB +N1500 for call+100 SMS', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:26', 'updated_at' => '2026-02-13 09:37:26'],
            ['id' => 44, 'data_id' => '306', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '21000', 'size' => '75GB', 'validity' => '30 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:27', 'updated_at' => '2026-02-13 09:37:27'],
            ['id' => 45, 'data_id' => '316', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '827', 'size' => '2.0GB', 'validity' => '7 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:27', 'updated_at' => '2026-02-13 09:37:27'],
            ['id' => 46, 'data_id' => '317', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '1200', 'size' => '2.5GB', 'validity' => '7 Days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:27', 'updated_at' => '2026-02-13 09:37:27'],
            ['id' => 47, 'data_id' => '318', 'network' => 'MTN', 'plan_type' => 'MTN SME BOSS', 'amount' => '1000', 'size' => '2.0 GB', 'validity' => '7 DAYS', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:27', 'updated_at' => '2026-02-13 09:37:27'],
            ['id' => 48, 'data_id' => '320', 'network' => 'MTN', 'plan_type' => 'MTN SME BOSS', 'amount' => '100', 'size' => '110MB', 'validity' => '1 DAY', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:27', 'updated_at' => '2026-02-13 09:37:27'],
            ['id' => 49, 'data_id' => '321', 'network' => 'MTN', 'plan_type' => 'MTN SME BOSS', 'amount' => '70', 'size' => '75.0 MB', 'validity' => '1 Day', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:27', 'updated_at' => '2026-02-13 09:37:27'],
            ['id' => 50, 'data_id' => '324', 'network' => 'MTN', 'plan_type' => 'MTN SME BOSS', 'amount' => '10000', 'size' => '40.0 GB', 'validity' => '60 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:27', 'updated_at' => '2026-02-13 09:37:27'],
            ['id' => 51, 'data_id' => '326', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '40800', 'size' => '150.0 GB', 'validity' => '60 days', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:27', 'updated_at' => '2026-02-13 09:37:27'],
            ['id' => 52, 'data_id' => '327', 'network' => 'MTN', 'plan_type' => 'MTN SME BOSS', 'amount' => '26500', 'size' => '90.0 GB', 'validity' => '60 day +10.5min', 'status' => 'enabled', 'created_at' => '2026-02-13 09:37:27', 'updated_at' => '2026-02-13 09:37:27'],
        ];

        DB::table('sme_datas')->insert($data);
    }
}
