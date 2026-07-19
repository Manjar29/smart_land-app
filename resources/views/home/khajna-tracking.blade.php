@extends('layouts.home', ['title' => 'Khajna Tracking'])

@section('content')
    <section class="reveal">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2>Khajna Tracking</h2>
                    <p>Track by district, upazila, dag number and receipt number.</p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-light">Back to Home</a>
            </div>

            <form class="search-box" action="{{ route('khajna.track') }}" method="GET">
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
                    <label for="dag_no">Dag Number</label>
                    <input id="dag_no" name="dag_no" type="text" value="{{ $criteria['dag_no'] }}" placeholder="Plot number">
                </div>
                <div class="field">
                    <label for="receipt_no">Khajna ID / Receipt No</label>
                    <input id="receipt_no" name="receipt_no" type="text" value="{{ $criteria['receipt_no'] }}" placeholder="Receipt number">
                </div>
                <div style="display: flex; align-items: end;">
                    <button class="btn btn-brand" style="width: 100%;" type="submit">Track Khajna</button>
                </div>
            </form>

            <div class="two-col-grid">
                <article class="card form-panel">
                    <h3>Status Result</h3>
                    @if ($result)
                        <span class="status-pill">{{ $result->status }}</span>
                        <p><strong>{{ $result->receipt_no }}</strong> for Dag {{ $result->dag_no }} and Khatian {{ $result->khatian_no }}.</p>
                        <p>Applicant: {{ $result->applicant_name }}</p>
                        <p>Land percentage: {{ $result->land_percentage }}%</p>
                        <p>Amount: {{ number_format($result->amount, 2) }} Taka</p>
                        <p>Last updated: {{ $result->updated_at->format('d M Y, h:i A') }}</p>
                    @elseif (collect($criteria)->filter()->isNotEmpty())
                        <p>No khajna request found for the district, upazila, dag number and receipt number you entered.</p>
                    @else
                        <p>Search by district, upazila, dag number and receipt number to see the latest khajna progress.</p>
                    @endif
                </article>

                <article class="card form-panel">
                    <h3>Timeline</h3>
                    @if ($result)
                        <div class="timeline">
                            <div class="timeline-item"><b></b><span>Application received</span></div>
                            <div class="timeline-item"><b></b><span>Status: {{ $result->status }}</span></div>
                            <div class="timeline-item"><b></b><span>Tax year: {{ $result->tax_year }}</span></div>
                        </div>
                    @else
                        <p class="muted">The timeline will appear after a case is found.</p>
                    @endif
                </article>
            </div>
        </div>
    </section>
@endsection