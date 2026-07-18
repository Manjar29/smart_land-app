<?php

namespace App\Http\Controllers;

use App\Models\DistrictAdmin;
use App\Models\KhajnaApplication;
use App\Models\LandRecord;
use App\Models\MutationApplication;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LandServiceController extends Controller
{
    private const DISTRICT_UPAZILAS = [
        'Pabna' => ['Pabna Sadar', 'Atgharia', 'Bera', 'Bhangura', 'Santhia', 'Sujanagar', 'Ishwardi', 'Chatmohar'],
        'Sirajganj' => ['Sirajganj Sadar', 'Belkuchi', 'Chauhali', 'Kazipur', 'Raiganj', 'Shahjadpur', 'Tarash', 'Ullapara'],
        'Dhaka' => ['Dhamrai', 'Dohar', 'Keraniganj', 'Nawabganj', 'Savar'],
        'Khulna' => ['Batiaghata', 'Dacope', 'Dighalia', 'Koyra', 'Paikgachha', 'Phultala', 'Rupsa', 'Terokhada'],
        'Jashore' => ['Abhaynagar', 'Bagherpara', 'Chaugachha', 'Jhikargachha', 'Keshabpur', 'Manirampur', 'Sharsha'],
        'Rajshahi' => ['Bagha', 'Bagmara', 'Charghat', 'Durgapur', 'Godagari', 'Mohanpur', 'Paba', 'Puthia'],
        'Comilla' => ['Adarsha Sadar', 'Burichang', 'Chauddagram', 'Debidwar', 'Laksam', 'Monohargonj', 'Nangalkot', 'Titas'],
    ];

    private const UPAZILA_UNIONS = [
        'Pabna Sadar' => ['Hemayetpur', 'Maligacha', 'Sadar Union', 'Bharara', 'Dogachi'],
        'Sirajganj Sadar' => ['Khokshabari', 'Kalia Haripur', 'Saydabad', 'Bagbati'],
        'Dhamrai' => ['Amta', 'Balia', 'Bara Paturia', 'Bhararia'],
        'Batiaghata' => ['Batiaghata Union', 'Bhandarkote', 'Gangarampur', 'Surkhali'],
        'Abhaynagar' => ['Bagutia', 'Chalishia', 'Nawapara', 'Payra', 'Prembag'],
        'Bagha' => ['Arani', 'Bausa', 'Chakrajapur', 'Gargari', 'Monigram'],
        'Adarsha Sadar' => ['Amratoli', 'Durgapur', 'Jagannathpur', 'Panchthubi'],
    ];

    private function getUnionsForUpazila(string $upazila): array
    {
        if (isset(self::UPAZILA_UNIONS[$upazila])) {
            return self::UPAZILA_UNIONS[$upazila];
        }
        
        return [$upazila . ' Union 1', $upazila . ' Union 2', $upazila . ' Union 3'];
    }

    public function home()
    {
        $allUnions = [];
        foreach (self::DISTRICT_UPAZILAS as $district => $upazilas) {
            foreach ($upazilas as $upazila) {
                $allUnions[$upazila] = $this->getUnionsForUpazila($upazila);
            }
        }

        return view('welcome', [
            'notices' => Notice::query()->where('is_active', true)->orderByDesc('published_at')->get(),
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
            'upazilaUnions' => $allUnions,
        ]);
    }

    public function search(Request $request)
    {
        $criteria = [
            'dag_no' => trim((string) $request->query('dag_no', '')),
            'khatian_no' => trim((string) $request->query('khatian_no', '')),
            'district' => trim((string) $request->query('district', '')),
            'upazila' => trim((string) $request->query('upazila', '')),
            'union_name' => trim((string) $request->query('union_name', '')),
        ];

        $query = LandRecord::query();

        if ($criteria['dag_no'] !== '') {
            $query->where('dag_no', 'like', '%' . $criteria['dag_no'] . '%');
        }

        if ($criteria['khatian_no'] !== '') {
            $query->where('khatian_no', 'like', '%' . $criteria['khatian_no'] . '%');
        }

        if ($criteria['district'] !== '') {
            $query->where('district', $criteria['district']);
        }

        if ($criteria['upazila'] !== '') {
            $query->where('upazila', $criteria['upazila']);
        }

        if ($criteria['union_name'] !== '') {
            $query->where('union_name', $criteria['union_name']);
        }

        $results = $query->orderBy('district')->orderBy('upazila')->orderBy('dag_no')->get();

        $allUnions = [];
        foreach (self::DISTRICT_UPAZILAS as $district => $upazilas) {
            foreach ($upazilas as $upazila) {
                $allUnions[$upazila] = $this->getUnionsForUpazila($upazila);
            }
        }

        // Mock BD Open API coords based on search
        $mapCenter = [23.685, 90.3563]; // default BD center
        if ($criteria['district'] === 'Dhaka') $mapCenter = [23.8103, 90.4125];
        if ($criteria['district'] === 'Rajshahi') $mapCenter = [24.3745, 88.6042];
        if ($criteria['district'] === 'Khulna') $mapCenter = [22.8456, 89.5403];
        if ($criteria['district'] === 'Pabna') $mapCenter = [24.0063, 89.2492];
        if ($criteria['district'] === 'Sirajganj') $mapCenter = [24.4534, 89.7006];
        if ($criteria['district'] === 'Jashore') $mapCenter = [23.1634, 89.2182];
        if ($criteria['district'] === 'Comilla') $mapCenter = [23.4607, 91.1809];

        return view('home.land-search', [
            'criteria' => $criteria,
            'results' => $results,
            'totalRecords' => LandRecord::count(),
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
            'upazilaUnions' => $allUnions,
            'apiMapCenter' => $mapCenter,
        ]);
    }

    public function trackMutation(Request $request)
    {
        $criteria = [
            'district' => trim((string) $request->query('district', '')),
            'upazila' => trim((string) $request->query('upazila', '')),
            'dag_no' => trim((string) $request->query('dag_no', '')),
            'tracking_no' => trim((string) $request->query('tracking_no', '')),
        ];

        $result = null;

        if (collect($criteria)->filter()->isNotEmpty()) {
            $query = MutationApplication::query();

            if ($criteria['district'] !== '') {
                $query->where('district', $criteria['district']);
            }

            if ($criteria['upazila'] !== '') {
                $query->where('upazila', $criteria['upazila']);
            }

            if ($criteria['dag_no'] !== '') {
                $query->where('dag_no', 'like', '%' . $criteria['dag_no'] . '%');
            }

            if ($criteria['tracking_no'] !== '') {
                $query->where('tracking_no', 'like', '%' . $criteria['tracking_no'] . '%');
            }

            $result = $query->latest()->first();
        }

        $allUnions = [];
        foreach (self::DISTRICT_UPAZILAS as $district => $upazilas) {
            foreach ($upazilas as $upazila) {
                $allUnions[$upazila] = $this->getUnionsForUpazila($upazila);
            }
        }

        return view('home.mutation-tracking', [
            'criteria' => $criteria,
            'result' => $result,
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
            'upazilaUnions' => $allUnions,
        ]);
    }

    public function trackKhajna(Request $request)
    {
        $criteria = [
            'district' => trim((string) $request->query('district', '')),
            'upazila' => trim((string) $request->query('upazila', '')),
            'dag_no' => trim((string) $request->query('dag_no', '')),
            'receipt_no' => trim((string) $request->query('receipt_no', '')),
        ];

        $result = null;

        if (collect($criteria)->filter()->isNotEmpty()) {
            $query = KhajnaApplication::query();

            if ($criteria['district'] !== '') {
                $query->where('district', $criteria['district']);
            }

            if ($criteria['upazila'] !== '') {
                $query->where('upazila', $criteria['upazila']);
            }

            if ($criteria['dag_no'] !== '') {
                $query->where('dag_no', 'like', '%' . $criteria['dag_no'] . '%');
            }

            if ($criteria['receipt_no'] !== '') {
                $query->where('receipt_no', 'like', '%' . $criteria['receipt_no'] . '%');
            }

            $result = $query->latest()->first();
        }

        $allUnions = [];
        foreach (self::DISTRICT_UPAZILAS as $district => $upazilas) {
            foreach ($upazilas as $upazila) {
                $allUnions[$upazila] = $this->getUnionsForUpazila($upazila);
            }
        }

        return view('home.khajna-tracking', [
            'criteria' => $criteria,
            'result' => $result,
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
            'upazilaUnions' => $allUnions,
        ]);
    }

    public function khajnaApply(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'applicant_name' => ['required', 'string', 'max:120'],
                'district' => ['required', 'string', 'max:80'],
                'upazila' => ['required', 'string', 'max:80'],
                'union_name' => ['required', 'string', 'max:80'],
                'dag_no' => ['required', 'string', 'max:30'],
                'khatian_no' => ['required', 'string', 'max:30'],
                'land_percentage' => ['required', 'numeric', 'min:0.01', 'max:100'],
                'tax_year' => ['required', 'string', 'max:20'],
                'mobile' => ['required', 'string', 'max:20'],
                'nid' => ['required', 'string', 'max:30'],
            ]);

            $this->ensureUpazilaMatchesDistrict($data['district'], $data['upazila']);

            $record = KhajnaApplication::create([
                'applicant_name' => $data['applicant_name'],
                'district' => $data['district'],
                'upazila' => $data['upazila'],
                'union_name' => $data['union_name'],
                'dag_no' => $data['dag_no'],
                'khatian_no' => $data['khatian_no'],
                'land_percentage' => $data['land_percentage'],
                'tax_year' => $data['tax_year'],
                'mobile' => $data['mobile'],
                'nid' => $data['nid'],
                'receipt_no' => 'KH-' . Str::upper(Str::random(8)),
                'amount' => round(((float) $data['land_percentage']) * 12, 2),
                'status' => 'Submitted',
                'submitted_by' => $data['applicant_name'],
            ]);

            $this->syncLandRecordFromKhajnaApplication($record);

            return redirect()->route('khajna.apply')->with('khajnaReceipt', $record);
        }

        $allUnions = [];
        foreach (self::DISTRICT_UPAZILAS as $district => $upazilas) {
            foreach ($upazilas as $upazila) {
                $allUnions[$upazila] = $this->getUnionsForUpazila($upazila);
            }
        }

        return view('home.khajna-apply', [
            'receipt' => session('khajnaReceipt'),
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
            'upazilaUnions' => $allUnions,
        ]);
    }

    public function mutationApply(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'district' => ['required', 'string', 'max:80'],
                'upazila' => ['required', 'string', 'max:80'],
                'union_name' => ['required', 'string', 'max:80'],
                'dag_no' => ['required', 'string', 'max:30'],
                'khatian_no' => ['required', 'string', 'max:30'],
                'land_percentage' => ['required', 'numeric', 'min:0.01', 'max:100'],
                'applicant_name' => ['required', 'array', 'min:1'],
                'applicant_name.*' => ['required', 'string', 'max:120'],
                'applicant_id_no' => ['required', 'array', 'min:1'],
                'applicant_id_no.*' => ['required', 'string', 'max:50'],
            ]);

            $this->ensureUpazilaMatchesDistrict($data['district'], $data['upazila']);

            $applicantNames = array_values(array_filter($data['applicant_name']));
            $applicantIds = array_values(array_filter($data['applicant_id_no']));

            $application = MutationApplication::create([
                'applicant_name' => implode(', ', $applicantNames),
                'district' => $data['district'],
                'upazila' => $data['upazila'],
                'union_name' => $data['union_name'],
                'dag_no' => $data['dag_no'],
                'khatian_no' => $data['khatian_no'],
                'land_percentage' => $data['land_percentage'],
                'applicant_id_no' => implode(', ', $applicantIds),
                'status' => 'Submitted',
                'tracking_no' => 'MUT-' . Str::upper(Str::random(8)),
                'amount' => round(((float) $data['land_percentage']) * 50, 2),
                'submitted_by' => $applicantNames[0],
                'notes' => 'Multiple applicants submitted for mutation review.',
            ]);

            $this->syncLandRecordFromMutationApplication($application);

            return redirect()->route('mutation.apply')->with('mutationReceipt', $application);
        }

        $allUnions = [];
        foreach (self::DISTRICT_UPAZILAS as $district => $upazilas) {
            foreach ($upazilas as $upazila) {
                $allUnions[$upazila] = $this->getUnionsForUpazila($upazila);
            }
        }

        return view('home.mutation-apply', [
            'receipt' => session('mutationReceipt'),
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
            'upazilaUnions' => $allUnions,
        ]);
    }

    public function adminLoginForm()
    {
        return view('admin.login', [
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
        ]);
    }

    public function adminLogin(Request $request)
    {
        $data = $request->validate([
            'district' => ['required', 'string', 'in:' . implode(',', array_keys(self::DISTRICT_UPAZILAS))],
            'password' => ['required', 'string'],
        ]);

        $expectedPassword = Str::lower($data['district']) . '123';

        if ($data['password'] !== $expectedPassword) {
            return back()->withErrors(['password' => 'Invalid district credentials.'])->withInput();
        }

        $admin = DistrictAdmin::query()->firstOrCreate(
            ['district' => $data['district']],
            ['password_hash' => Hash::make($expectedPassword)]
        );

        $request->session()->put('district_admin', [
            'id' => $admin->id,
            'district' => $admin->district,
        ]);

        return redirect()->route('district-admin.dashboard');
    }

    public function adminDashboard(Request $request)
    {
        $district = $request->session()->get('district_admin.district');

        return view('admin.dashboard', [
            'district' => $district,
            'khajnaApplications' => KhajnaApplication::query()->where('district', $district)->latest()->get(),
            'mutationApplications' => MutationApplication::query()->where('district', $district)->latest()->get(),
            'notices' => Notice::query()->orderByDesc('published_at')->get(),
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
        ]);
    }

    public function adminLogout(Request $request)
    {
        $request->session()->forget('district_admin');
        $request->session()->regenerateToken();

        return redirect()->route('district-admin.login');
    }

    public function showKhajnaApplication(Request $request, KhajnaApplication $khajnaApplication)
    {
        $this->ensureDistrictMatch($request, $khajnaApplication->district);

        return view('admin.application-show', [
            'district' => $khajnaApplication->district,
            'type' => 'Khajna',
            'application' => $khajnaApplication,
            'titleField' => 'Receipt No',
            'identifier' => $khajnaApplication->receipt_no,
            'detailRows' => [
                'Applicant' => $khajnaApplication->applicant_name,
                'Dag No' => $khajnaApplication->dag_no,
                'Khatian No' => $khajnaApplication->khatian_no,
                'Land Percentage' => $khajnaApplication->land_percentage . '%',
                'Amount' => number_format($khajnaApplication->amount, 2) . ' Taka',
                'Mobile' => $khajnaApplication->mobile,
                'National ID' => $khajnaApplication->nid,
                'Status' => $khajnaApplication->status,
                'Last Updated' => optional($khajnaApplication->updated_at)->format('d M Y, h:i A'),
            ],
            'actionRoute' => route('district-admin.khajna.action', $khajnaApplication),
            'trackingRoute' => route('khajna.track', [
                'district' => $khajnaApplication->district,
                'upazila' => $khajnaApplication->upazila,
                'dag_no' => $khajnaApplication->dag_no,
                'receipt_no' => $khajnaApplication->receipt_no,
            ]),
        ]);
    }

    public function showMutationApplication(Request $request, MutationApplication $mutationApplication)
    {
        $this->ensureDistrictMatch($request, $mutationApplication->district);

        return view('admin.application-show', [
            'district' => $mutationApplication->district,
            'type' => 'Mutation',
            'application' => $mutationApplication,
            'titleField' => 'Tracking No',
            'identifier' => $mutationApplication->tracking_no,
            'detailRows' => [
                'Applicant' => $mutationApplication->applicant_name,
                'Dag No' => $mutationApplication->dag_no,
                'Khatian No' => $mutationApplication->khatian_no,
                'Land Percentage' => $mutationApplication->land_percentage . '%',
                'Applicant IDs' => $mutationApplication->applicant_id_no,
                'Amount' => number_format($mutationApplication->amount, 2) . ' Taka',
                'Notes' => $mutationApplication->notes ?: 'No admin notes yet',
                'Status' => $mutationApplication->status,
                'Last Updated' => optional($mutationApplication->updated_at)->format('d M Y, h:i A'),
            ],
            'actionRoute' => route('district-admin.mutation.action', $mutationApplication),
            'trackingRoute' => route('mutation.track', [
                'district' => $mutationApplication->district,
                'upazila' => $mutationApplication->upazila,
                'dag_no' => $mutationApplication->dag_no,
                'tracking_no' => $mutationApplication->tracking_no,
            ]),
        ]);
    }

    public function updateKhajnaStatus(Request $request, KhajnaApplication $khajnaApplication)
    {
        $this->ensureDistrictMatch($request, $khajnaApplication->district);

        $data = $request->validate([
            'status' => ['required', 'string', 'in:Submitted,Under review,Approved,Rejected,Deleted'],
        ]);

        $khajnaApplication->update($data);
        $this->syncLandRecordFromKhajnaApplication($khajnaApplication);

        return back()->with('adminMessage', 'Khajna application updated.');
    }

    public function updateMutationStatus(Request $request, MutationApplication $mutationApplication)
    {
        $this->ensureDistrictMatch($request, $mutationApplication->district);

        $data = $request->validate([
            'status' => ['required', 'string', 'in:Submitted,Field inspection,Approved,Rejected,Deleted'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $mutationApplication->update($data);
        $this->syncLandRecordFromMutationApplication($mutationApplication);

        return back()->with('adminMessage', 'Mutation application updated.');
    }

    public function storeNotice(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:2000'],
            'notice_type' => ['required', 'string', 'max:50'],
        ]);

        Notice::create($data + [
            'published_at' => now(),
            'is_active' => true,
        ]);

        return back()->with('adminMessage', 'Notice created.');
    }

    public function updateNotice(Request $request, Notice $notice)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:2000'],
            'notice_type' => ['required', 'string', 'max:50'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['published_at'] = now();

        $notice->update($data);

        return back()->with('adminMessage', 'Notice updated.');
    }

    public function destroyNotice(Notice $notice)
    {
        $notice->delete();

        return back()->with('adminMessage', 'Notice deleted.');
    }

    private function ensureDistrictMatch(Request $request, string $targetDistrict): void
    {
        $district = $request->session()->get('district_admin.district');

        abort_unless($district && strcasecmp($district, $targetDistrict) === 0, 403, 'You can only manage your own district.');
    }

    private function ensureUpazilaMatchesDistrict(string $district, string $upazila): void
    {
        $allowedUpazilas = self::DISTRICT_UPAZILAS[$district] ?? [];

        if (! in_array($upazila, $allowedUpazilas, true)) {
            throw ValidationException::withMessages([
                'upazila' => 'The selected upazila does not belong to the chosen district.',
            ]);
        }
    }

    private function syncLandRecordFromKhajnaApplication(KhajnaApplication $application): void
    {
        $landRecord = LandRecord::query()->firstOrNew([
            'district' => $application->district,
            'upazila' => $application->upazila,
            'dag_no' => $application->dag_no,
            'khatian_no' => $application->khatian_no,
        ]);

        $landRecord->fill([
            'union_name' => $application->union_name,
            'mouza' => $landRecord->mouza ?: $application->upazila . ' Mouza',
            'owner_name' => $application->applicant_name,
            'area_percentage' => $application->land_percentage,
            'khajna_status' => $application->status,
            'mutation_status' => $landRecord->mutation_status ?: 'Not applied',
            'previous_khajna_amount' => $application->amount,
            'previous_mutation_reference' => $landRecord->previous_mutation_reference,
        ]);

        $landRecord->save();
    }

    private function syncLandRecordFromMutationApplication(MutationApplication $application): void
    {
        $landRecord = LandRecord::query()->firstOrNew([
            'district' => $application->district,
            'upazila' => $application->upazila,
            'dag_no' => $application->dag_no,
            'khatian_no' => $application->khatian_no,
        ]);

        $landRecord->fill([
            'union_name' => $application->union_name,
            'mouza' => $landRecord->mouza ?: $application->upazila . ' Mouza',
            'owner_name' => $application->applicant_name,
            'area_percentage' => $application->land_percentage,
            'khajna_status' => $landRecord->khajna_status ?: 'Pending',
            'mutation_status' => $application->status,
            'previous_khajna_amount' => $landRecord->previous_khajna_amount ?? 0,
            'previous_mutation_reference' => $application->tracking_no,
        ]);

        $landRecord->save();
    }
}