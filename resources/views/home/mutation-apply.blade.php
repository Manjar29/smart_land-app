@extends('layouts.home', ['title' => 'Apply for Mutation'])

@section('content')
    <section class="reveal">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2>Apply for Mutation</h2>
                    <p>Submit one or more appliers for the same plot. The charge is calculated at 50 taka per land percentage.</p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-light">Back to Home</a>
            </div>

            @if ($receipt)
                <div class="card receipt-banner">
                    <h3>Mutation application submitted</h3>
                    <p>Tracking No: <strong>{{ $receipt->tracking_no }}</strong></p>
                    <p>Status: {{ $receipt->status }} | Amount: {{ number_format($receipt->amount, 2) }} Taka</p>
                </div>
            @endif

            <div class="two-col-grid">
                <form class="card form-panel" action="{{ route('mutation.apply') }}" method="POST">
                    @csrf
                    <h3>Mutation Application Form</h3>

                    <div class="form-grid">
                        <div class="field">
                            <label for="district">District</label>
                            <select id="district" name="district" data-dependent-district>
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
                            <label for="dag_no">Dag Number</label>
                            <input id="dag_no" name="dag_no" type="text" value="{{ old('dag_no') }}" required>
                        </div>
                        <div class="field">
                            <label for="khatian_no">Khatian Number</label>
                            <input id="khatian_no" name="khatian_no" type="text" value="{{ old('khatian_no') }}" required>
                        </div>
                        <div class="field">
                            <label for="land_percentage">Land Quantity (%)</label>
                            <input id="land_percentage" name="land_percentage" type="number" min="0.01" max="100" step="0.01" value="{{ old('land_percentage') }}" required>
                        </div>
                        <div class="field">
                            <label for="estimated_amount">Estimated Charge</label>
                            <input id="estimated_amount" type="text" value="0.00 Taka" readonly>
                        </div>
                    </div>

                    <div class="card" style="border-style: dashed;">
                        <h4>Applicant 1</h4>
                        <div class="form-grid">
                            <div class="field">
                                <label for="applicant_name_0">Applicant Name</label>
                                <input id="applicant_name_0" name="applicant_name[]" type="text" value="{{ old('applicant_name.0') }}" required>
                            </div>
                            <div class="field">
                                <label for="applicant_id_no_0">Applicant ID No</label>
                                <input id="applicant_id_no_0" name="applicant_id_no[]" type="text" value="{{ old('applicant_id_no.0') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="border-style: dashed;">
                        <h4>Applicant 2</h4>
                        <div class="form-grid">
                            <div class="field">
                                <label for="applicant_name_1">Applicant Name</label>
                                <input id="applicant_name_1" name="applicant_name[]" type="text" value="{{ old('applicant_name.1') }}">
                            </div>
                            <div class="field">
                                <label for="applicant_id_no_1">Applicant ID No</label>
                                <input id="applicant_id_no_1" name="applicant_id_no[]" type="text" value="{{ old('applicant_id_no.1') }}">
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-brand" type="submit" style="margin-top: 1rem;">Submit Mutation</button>
                </form>

                <aside class="card form-panel">
                    <h3>Submission Notes</h3>
                    <div class="timeline">
                        <div class="timeline-item"><b></b><span>Provide the district, upazila and plot identifiers exactly as shown in the land record.</span></div>
                        <div class="timeline-item"><b></b><span>Multiple applicants can be entered for the same mutation request.</span></div>
                        <div class="timeline-item"><b></b><span>Charges are computed automatically from the land quantity percentage.</span></div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
