<?php

namespace Database\Seeders;

use App\Models\DistrictAdmin;
use App\Models\KhajnaApplication;
use App\Models\LandRecord;
use App\Models\MutationApplication;
use App\Models\Notice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Pabna', 'Sirajganj', 'Dhaka', 'Khulna', 'Jashore', 'Rajshahi', 'Comilla'] as $district) {
            DistrictAdmin::updateOrCreate(
                ['district' => $district],
                ['password_hash' => Hash::make(strtolower($district) . '123')]
            );
        }

        $landRecords = [
            ['district' => 'Dhaka', 'upazila' => 'Savar', 'dag_no' => '1205', 'khatian_no' => 'KH-341', 'mouza' => 'Aminbazar', 'owner_name' => 'Abdul Karim', 'area_percentage' => 42.00, 'khajna_status' => 'Paid', 'mutation_status' => 'Approved', 'previous_khajna_amount' => 504.00, 'previous_mutation_reference' => 'MUT-2048'],
            ['district' => 'Dhaka', 'upazila' => 'Keraniganj', 'dag_no' => '1804', 'khatian_no' => 'KH-552', 'mouza' => 'Hasnabad', 'owner_name' => 'Rina Begum', 'area_percentage' => 18.00, 'khajna_status' => 'Due', 'mutation_status' => 'In progress', 'previous_khajna_amount' => 216.00, 'previous_mutation_reference' => 'MUT-3197'],
            ['district' => 'Rajshahi', 'upazila' => 'Paba', 'dag_no' => '2201', 'khatian_no' => 'KH-118', 'mouza' => 'Laxmipur', 'owner_name' => 'Md. Selim', 'area_percentage' => 66.00, 'khajna_status' => 'Paid', 'mutation_status' => 'Field inspection', 'previous_khajna_amount' => 792.00, 'previous_mutation_reference' => 'MUT-4801'],
            ['district' => 'Khulna', 'upazila' => 'Batiaghata', 'dag_no' => '3042', 'khatian_no' => 'KH-907', 'mouza' => 'Gadaipur', 'owner_name' => 'Sharmeen Akter', 'area_percentage' => 29.00, 'khajna_status' => 'Paid', 'mutation_status' => 'Approved', 'previous_khajna_amount' => 348.00, 'previous_mutation_reference' => 'MUT-5560'],
        ];

        foreach ($landRecords as $record) {
            LandRecord::updateOrCreate(
                ['district' => $record['district'], 'upazila' => $record['upazila'], 'dag_no' => $record['dag_no'], 'khatian_no' => $record['khatian_no']],
                $record
            );
        }

        KhajnaApplication::updateOrCreate(
            ['receipt_no' => 'KH-TEST2048'],
            [
                'applicant_name' => 'Abdul Karim',
                'district' => 'Dhaka',
                'upazila' => 'Savar',
                'dag_no' => '1205',
                'khatian_no' => 'KH-341',
                'land_percentage' => 42.00,
                'tax_year' => '2026-27',
                'mobile' => '01700000000',
                'nid' => '1234567890',
                'amount' => 504.00,
                'status' => 'Submitted',
                'submitted_by' => 'Abdul Karim',
            ]
        );

        MutationApplication::updateOrCreate(
            ['tracking_no' => 'MUT-2048'],
            [
                'applicant_name' => 'Abdul Karim',
                'district' => 'Dhaka',
                'upazila' => 'Savar',
                'dag_no' => '1205',
                'khatian_no' => 'KH-341',
                'land_percentage' => 42.00,
                'applicant_id_no' => 'ID-001, ID-002',
                'amount' => 2100.00,
                'status' => 'Approved',
                'submitted_by' => 'Abdul Karim',
                'notes' => 'Field inspection completed',
            ]
        );

        Notice::updateOrCreate(
            ['title' => 'Mutation Certificate Delivery Timeline Updated'],
            [
                'body' => 'New guideline reduces standard processing cycle to 21 working days.',
                'notice_type' => 'Circular',
                'published_at' => Carbon::now()->subDays(2),
                'is_active' => true,
            ]
        );

        Notice::updateOrCreate(
            ['title' => 'Khajna Payment Deadline for 2026 Announced'],
            [
                'body' => 'Complete annual tax payment before September 30 to avoid late fines.',
                'notice_type' => 'Tax Deadline',
                'published_at' => Carbon::now()->subDay(),
                'is_active' => true,
            ]
        );

        Notice::updateOrCreate(
            ['title' => 'District Helpdesk Extended for Rural Citizens'],
            [
                'body' => 'Support desks are now open six days a week in selected upazilas.',
                'notice_type' => 'Service Update',
                'published_at' => Carbon::now(),
                'is_active' => true,
            ]
        );
    }
}
