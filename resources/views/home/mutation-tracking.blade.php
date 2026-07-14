@extends('layouts.home', ['title' => 'Mutation Tracking'])

@section('content')
    <section class="reveal">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2>Mutation Tracking</h2>
                    <p>Follow the land transfer case from submission to the latest field or officer review.</p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-light">Back to Home</a>
            </div>

            <form class="search-box" action="{{ route('mutation.track') }}" method="GET">
                <div class="field">
                    <label for="district">District</label>
                    <select id="district" name="district" data-dependent-district>
                        <option value="">Select District</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district }}" @selected($criteria['district'] === $district)>{{ $district }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="upazila">Upazila</label>
                    <select id="upazila" name="upazila" data-dependent-upazila data-selected-upazila="{{ $criteria['upazila'] }}">
                        <option value="">Select Upazila</option>
                    </select>
                </div>
                <div class="field">
                    <label for="applicant_id_no">Mutation Applicant ID</label>
                    <input id="applicant_id_no" name="applicant_id_no" type="text" value="{{ $criteria['applicant_id_no'] }}" placeholder="Applicant ID number">
                </div>
                <div class="field">
                    <label for="land_percentage">Land Quantity (%)</label>
                    <input id="land_percentage" name="land_percentage" type="number" step="0.01" min="0.01" max="100" value="{{ $criteria['land_percentage'] }}" placeholder="e.g. 25">
                </div>
                <div style="display: flex; align-items: end;">
                    <button class="btn btn-brand" style="width: 100%;" type="submit">Track Case</button>
                </div>
            </form>

            <div class="two-col-grid">
                <article class="card form-panel">
                    <h3>Case Status</h3>
                    @if ($result)
                        <span class="status-pill">{{ $result['status'] }}</span>
                        <p><strong>{{ $result->tracking_no }}</strong> for Dag {{ $result->dag_no }} and Khatian {{ $result->khatian_no }}.</p>
                        <p>Applicant: {{ $result->applicant_name }}</p>
                        <p>Applicant IDs: {{ $result->applicant_id_no }}</p>
                        <p>Land quantity: {{ $result->land_percentage }}%</p>
                        <p>Charge: {{ number_format($result->amount, 2) }} Taka</p>
                        <p>Last updated: {{ $result->updated_at->format('d M Y, h:i A') }}</p>
                    @elseif (collect($criteria)->filter()->isNotEmpty())
                        <p>No case found for the district, upazila and applicant ID you entered.</p>
                    @else
                        <p>Search by district, upazila and mutation applicant ID to see the latest mutation progress.</p>
                    @endif
                </article>

                <article class="card form-panel">
                    <h3>Timeline</h3>
                    @if ($result)
                        <div class="timeline">
                            <div class="timeline-item"><b></b><span>Application received</span></div>
                            <div class="timeline-item"><b></b><span>Status: {{ $result->status }}</span></div>
                            <div class="timeline-item"><b></b><span>Notes: {{ $result->notes ?? 'Awaiting officer note' }}</span></div>
                        </div>
                    @else
                        <p class="muted">The timeline will appear after a case is found.</p>
                    @endif
                </article>
            </div>
        </div>
    </section>
@endsection