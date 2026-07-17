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

    public function home()
    {
        return view('welcome', [
            'notices' => Notice::query()->where('is_active', true)->orderByDesc('published_at')->get(),
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
        ]);
    }

    public function search(Request $request)
    {
        $criteria = [
            'dag_no' => trim((string) $request->query('dag_no', '')),
            'khatian_no' => trim((string) $request->query('khatian_no', '')),
            'district' => trim((string) $request->query('district', '')),
            'upazila' => trim((string) $request->query('upazila', '')),
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

        $results = $query->orderBy('district')->orderBy('upazila')->orderBy('dag_no')->get();

        return view('home.land-search', [
            'criteria' => $criteria,
            'results' => $results,
            'totalRecords' => LandRecord::count(),
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
        ]);
    }

    public function trackMutation(Request $request)
    {
        $criteria = [
            'district' => trim((string) $request->query('district', '')),
            'upazila' => trim((string) $request->query('upazila', '')),
            'applicant_id_no' => trim((string) $request->query('applicant_id_no', '')),
            'land_percentage' => trim((string) $request->query('land_percentage', '')),
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

            if ($criteria['applicant_id_no'] !== '') {
                $query->where('applicant_id_no', 'like', '%' . $criteria['applicant_id_no'] . '%');
            }

            if ($criteria['land_percentage'] !== '') {
                $query->where('land_percentage', $criteria['land_percentage']);
            }

            $result = $query->latest()->first();
        }

        return view('home.mutation-tracking', [
            'criteria' => $criteria,
            'result' => $result,
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
        ]);
    }

    public function khajnaApply(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'applicant_name' => ['required', 'string', 'max:120'],
                'district' => ['required', 'string', 'max:80'],
                'upazila' => ['required', 'string', 'max:80'],
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

            return redirect()->route('khajna.apply')->with('khajnaReceipt', $record);
        }

        return view('home.khajna-apply', [
            'receipt' => session('khajnaReceipt'),
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
        ]);
    }

    public function mutationApply(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'district' => ['required', 'string', 'max:80'],
                'upazila' => ['required', 'string', 'max:80'],
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

            return redirect()->route('mutation.apply')->with('mutationReceipt', $application);
        }

        return view('home.mutation-apply', [
            'receipt' => session('mutationReceipt'),
            'districts' => array_keys(self::DISTRICT_UPAZILAS),
            'districtUpazilas' => self::DISTRICT_UPAZILAS,
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

    public function updateKhajnaStatus(Request $request, KhajnaApplication $khajnaApplication)
    {
        $this->ensureDistrictMatch($request, $khajnaApplication->district);

        $data = $request->validate([
            'status' => ['required', 'string', 'max:40'],
        ]);

        $khajnaApplication->update($data);

        return back()->with('adminMessage', 'Khajna application updated.');
    }

    public function updateMutationStatus(Request $request, MutationApplication $mutationApplication)
    {
        $this->ensureDistrictMatch($request, $mutationApplication->district);

        $data = $request->validate([
            'status' => ['required', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $mutationApplication->update($data);

        return back()->with('adminMessage', 'Mutation application updated.');
    }

    public function storeNotice(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:2000'],
            'notice_type' => ['required', 'string', 'max:50'],
            'published_at' => ['required', 'date'],
        ]);

        Notice::create($data + ['is_active' => true]);

        return back()->with('adminMessage', 'Notice created.');
    }

    public function updateNotice(Request $request, Notice $notice)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:2000'],
            'notice_type' => ['required', 'string', 'max:50'],
            'published_at' => ['required', 'date'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

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
}