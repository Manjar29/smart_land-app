@extends('layouts.home', ['title' => 'Search Land Record'])

@section('content')
    <section class="reveal">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2>Search Land Record</h2>
                    <p>Use the live filters to inspect the registry snapshot for dag, khatian, district, and upazila.</p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-light">Back to Home</a>
            </div>

            <form class="search-box" action="{{ route('land.search') }}" method="GET">
                <div class="field">
                    <label for="dag_no">Dag Number</label>
                    <input id="dag_no" name="dag_no" type="text" value="{{ $criteria['dag_no'] }}" placeholder="e.g. 1205">
                </div>
                <div class="field">
                    <label for="khatian_no">Khatian Number</label>
                    <input id="khatian_no" name="khatian_no" type="text" value="{{ $criteria['khatian_no'] }}" placeholder="e.g. KH-341">
                </div>
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
                <div style="display: flex; align-items: end;">
                    <button class="btn btn-brand" style="width: 100%;" type="submit">Search Records</button>
                </div>
            </form>

            <div class="results-shell">
                <div class="section-head" style="margin-top: 1.2rem;">
                    <div>
                        <h2>Search Results</h2>
                        <p>{{ count($results) }} of {{ $totalRecords }} records matched your request.</p>
                    </div>
                </div>

                <div class="results-grid">
                    @forelse ($results as $record)
                        <article class="result-card card">
                            <div class="result-top">
                                <div>
                                    <span class="status-pill">{{ $record->khajna_status }}</span>
                                    <h3>Dag {{ $record->dag_no }} | {{ $record->khatian_no }}</h3>
                                </div>
                                <div class="result-meta">{{ $record->district }}, {{ $record->upazila }}</div>
                            </div>
                            <p>{{ $record->owner_name }} owns the plot in {{ $record->mouza }}, {{ $record->upazila }}, {{ $record->district }}.</p>
                            <div class="result-details">
                                <span>Area: {{ $record->area_percentage }}%</span>
                                <span>Mouza: {{ $record->mouza }}</span>
                                <span>District: {{ $record->district }}</span>
                                <span>Upazila: {{ $record->upazila }}</span>
                                <span>Mutation: {{ $record->mutation_status }}</span>
                                <span>Previous Khajna: {{ number_format($record->previous_khajna_amount, 2) }} Taka</span>
                                <span>Previous Mutation: {{ $record->previous_mutation_reference ?? 'N/A' }}</span>
                            </div>
                        </article>
                    @empty
                        <div class="card" style="grid-column: 1 / -1;">
                            <h3>No matching record found</h3>
                            <p>Try a broader search or clear one of the filters.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection