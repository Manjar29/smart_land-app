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
                            <label for="union_name">Union</label>
                            <select id="union_name" name="union_name" data-dependent-union data-selected-union="{{ old('union_name') }}" required>
                                <option value="">Select Union</option>
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

                    @php
                        $applicantCount = max(count(old('applicant_name', [])), count(old('applicant_id_no', [])), 2);
                    @endphp

                    <div id="mutation-applicants" class="applicant-list">
                        @for ($index = 0; $index < $applicantCount; $index++)
                            <div class="card applicant-card" data-applicant-card>
                                <h4>Applicant {{ $index + 1 }}</h4>
                                <div class="form-grid">
                                    <div class="field">
                                        <label for="applicant_name_{{ $index }}">Applicant Name</label>
                                        <input id="applicant_name_{{ $index }}" name="applicant_name[]" type="text" value="{{ old('applicant_name.' . $index) }}" @if ($index === 0) required @endif>
                                    </div>
                                    <div class="field">
                                        <label for="applicant_id_no_{{ $index }}">Applicant ID No</label>
                                        <input id="applicant_id_no_{{ $index }}" name="applicant_id_no[]" type="text" value="{{ old('applicant_id_no.' . $index) }}" @if ($index === 0) required @endif>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <template id="applicant-template">
                        <div class="card applicant-card" data-applicant-card>
                            <h4>Applicant __INDEX__</h4>
                            <div class="form-grid">
                                <div class="field">
                                    <label for="applicant_name___INDEX__">Applicant Name</label>
                                    <input id="applicant_name___INDEX__" name="applicant_name[]" type="text">
                                </div>
                                <div class="field">
                                    <label for="applicant_id_no___INDEX__">Applicant ID No</label>
                                    <input id="applicant_id_no___INDEX__" name="applicant_id_no[]" type="text">
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="button-row" style="margin-top: 1rem;">
                        <button class="btn btn-brand" style="background:#0284c7; border-color:#0284c7;" type="button" data-add-applicant><span style="margin-right:0.5rem; font-weight:900; font-size:1.1rem;">(+)</span> Add More Applicants</button>
                        <button class="btn btn-brand" type="submit">Submit Mutation</button>
                    </div>
                </form>

                <aside class="card form-panel">
                    <h3>Submission Notes</h3>
                    <div class="timeline">
                        <div class="timeline-item"><b></b><span>Provide the district, upazila and plot identifiers exactly as shown in the land record.</span></div>
                        <div class="timeline-item"><b></b><span>Multiple applicants can be entered for the same mutation request.</span></div>
                        <div class="timeline-item"><b></b><span>Charges are computed automatically from the land quantity percentage.</span></div>
                    </div>
                    <a href="{{ route('mutation.track') }}" class="btn btn-light">Track Mutation Status</a>
                </aside>
            </div>
        </div>
    </section>
@endsection
