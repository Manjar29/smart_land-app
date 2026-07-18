<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandServicePagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_homepage_loads(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Smart Digital Land Record System');
        $response->assertSee('Track Khajna');
    }

    public function test_land_search_filters_records(): void
    {
        $response = $this->get('/search-land-record?district=Dhaka&upazila=Savar&dag_no=1205');

        $response->assertOk();
        $response->assertSee('Dag 1205 | KH-341');
        $response->assertSee('Abdul Karim owns the plot');
    }

    public function test_mutation_tracking_shows_case(): void
    {
        $response = $this->get('/mutation-tracking?district=Dhaka&upazila=Savar&dag_no=1205&tracking_no=MUT-2048');

        $response->assertOk();
        $response->assertSee('MUT-2048');
        $response->assertSee('Abdul Karim');
    }

    public function test_khajna_tracking_shows_case(): void
    {
        $response = $this->get('/khajna-tracking?district=Dhaka&upazila=Savar&dag_no=1205&receipt_no=KH-TEST2048');

        $response->assertOk();
        $response->assertSee('KH-TEST2048');
        $response->assertSee('Abdul Karim');
    }

    public function test_khajna_application_submits(): void
    {
        $response = $this->post('/khajna-apply', [
            'applicant_name' => 'Test Citizen',
            'nid' => '1234567890',
            'dag_no' => '1205',
            'khatian_no' => 'KH-341',
            'district' => 'Dhaka',
            'upazila' => 'Savar',
            'mobile' => '01700000000',
            'tax_year' => '2026-27',
            'land_percentage' => 12,
        ]);

        $response->assertRedirect('/khajna-apply');
        $this->assertDatabaseHas('land_records', [
            'district' => 'Dhaka',
            'upazila' => 'Savar',
            'dag_no' => '1205',
            'khatian_no' => 'KH-341',
            'owner_name' => 'Test Citizen',
            'khajna_status' => 'Submitted',
        ]);
    }

    public function test_mutation_application_submits(): void
    {
        $response = $this->post('/mutation-apply', [
            'district' => 'Dhaka',
            'upazila' => 'Savar',
            'dag_no' => '1205',
            'khatian_no' => 'KH-341',
            'land_percentage' => 15,
            'applicant_name' => ['Applicant One', 'Applicant Two'],
            'applicant_id_no' => ['ID-001', 'ID-002'],
        ]);

        $response->assertRedirect('/mutation-apply');
        $this->assertDatabaseHas('land_records', [
            'district' => 'Dhaka',
            'upazila' => 'Savar',
            'dag_no' => '1205',
            'khatian_no' => 'KH-341',
            'mutation_status' => 'Submitted',
        ]);
    }

    public function test_district_admin_login_works(): void
    {
        $response = $this->post('/district-admin/login', [
            'district' => 'Dhaka',
            'password' => 'dhaka123',
        ]);

        $response->assertRedirect('/district-admin');
    }
}