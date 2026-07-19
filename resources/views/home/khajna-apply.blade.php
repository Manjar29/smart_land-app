@extends('layouts.home', ['title' => 'Apply for Khajna'])

@section('content')
    <section class="reveal">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2>Apply for Khajna</h2>
                    <p>Submit the tax application against a land record and receive a receipt number for follow-up.</p>
                </div>
                <div class="button-row">
                    <a href="{{ route('khajna.track') }}" class="btn btn-light">Track Khajna</a>
                    <a href="{{ route('home') }}" class="btn btn-light">Back to Home</a>
                </div>
            </div>

            @if ($receipt)
                <div class="card receipt-banner">
                    <h3>Application submitted</h3>
                    <p>Receipt No: <strong>{{ $receipt->receipt_no }}</strong></p>
                    <p>Status: {{ $receipt->status }} | Amount: {{ number_format($receipt->amount, 2) }} Taka</p>
                </div>
            @endif

            <div class="two-col-grid">
                <form class="card form-panel" action="{{ route('khajna.apply') }}" method="POST">
                    @csrf
                    <h3>Khajna Application Form</h3>

                    <div class="form-grid">
                        <div class="field">
                            <label for="applicant_name">Applicant Name</label>
                            <input id="applicant_name" name="applicant_name" type="text" value="{{ old('applicant_name') }}" required>
                        </div>
                        <div class="field">
                            <label for="nid">National ID</label>
                            <input id="nid" name="nid" type="text" value="{{ old('nid') }}" required>
                        </div>
                        <div class="field">
                            <label for="dag_no">Dag Number</label>
                            <input id="dag_no" name="dag_no" type="text" value="{{ old('dag_no') }}" required>
                        </div>
                        <div class="field">
                            <label for="khatian_no">Khatian Number</label>
                            <input id="khatian_no" name="khatian_no" type="text" value="{{ old('khatian_no') }}" required>
                        </div>
                        <div class="field">
                            <label for="district">District</label>
                            <select id="district" name="district" data-dependent-district required>
                                <option value="">Select District</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district }}" @selected(old('district') === $district)>{{ $district }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="upazila">Upazila</label>
                            <select id="upazila" name="upazila" data-dependent-upazila data-selected-upazila="{{ old('upazila') }}">
                                <option value="">Select Upazila</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="union_name">Union</label>
                            <select id="union_name" name="union_name" data-dependent-union data-selected-union="{{ old('union_name') }}" required>
                                <option value="">Select Union</option>
                            </select>
                        </div>

                        <div class="field">
                            <label for="mobile">Mobile Number</label>
                            <input id="mobile" name="mobile" type="text" value="{{ old('mobile') }}" required>
                        </div>
                        <div class="field">
                            <label for="tax_year">Tax Year</label>
                            <input id="tax_year" name="tax_year" type="text" value="{{ old('tax_year') }}" placeholder="2026-27" required>
                        </div>
                        <div class="field">
                            <label for="land_percentage">Land Quantity (%)</label>
                            <input id="land_percentage" name="land_percentage" type="number" step="0.01" min="0.01" max="100" value="{{ old('land_percentage') }}" required>
                        </div>
                        <div class="field">
                            <label for="amount_preview">Estimated Amount</label>
                            <input id="amount_preview" type="text" value="0.00 Taka" readonly>
                        </div>
                    </div>

                    <button class="btn btn-brand" type="submit" style="margin-top: 1rem;">Submit Application</button>
                </form>

                <aside class="card form-panel">
                    <h3>How it works</h3>
                    <div class="timeline">
                        <div class="timeline-item"><b></b><span>Verify the land record and ownership details.</span></div>
                        <div class="timeline-item"><b></b><span>Submit the khajna request with the current tax year and payable amount.</span></div>
                        <div class="timeline-item"><b></b><span>Use the generated receipt number to track the request status later.</span></div>
                    </div>
                    <a href="{{ route('khajna.track') }}" class="btn btn-brand">Track by Receipt or Dag No</a>
                </aside>
            </div>
        </div>
    </section>
@endsection