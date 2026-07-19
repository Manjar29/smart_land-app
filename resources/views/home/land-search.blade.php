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
                    <select id="district" name="district" data-dependent-district data-selected-district="{{ old('district', $criteria['district'] ?? '') }}">
                        <option value="">Select District</option>
                    </select>
                </div>
                <div class="field">
                    <label for="upazila">Upazila</label>
                    <select id="upazila" name="upazila" data-dependent-upazila data-selected-upazila="{{ old('upazila', $criteria['upazila'] ?? '') }}">
                        <option value="">Select Upazila</option>
                    </select>
                </div>
                <div style="display: flex; align-items: end;">
                    <button class="btn btn-brand" style="width: 100%;" type="submit">Search Records</button>
                </div>
            </form>

            <div class="results-shell">
                @if ($criteria['dag_no'] || $criteria['khatian_no'] || $criteria['district'] || $criteria['upazila'] || ($criteria['union_name'] ?? false))
                    @if ($results->isEmpty())
                        <div class="card" style="text-align: center; padding: 2rem;">
                            <p class="muted">No matching land records found. Try adjusting your search criteria.</p>
                        </div>
                    @else
                        <div class="two-col-grid" style="align-items: start;">
                            <div class="results-grid" style="grid-template-columns: 1fr;">
                                @foreach ($results as $record)
                                    <div class="card result-card">
                                        <div class="result-top">
                                            <div>
                                                <h3>{{ $record->dag_no }} / {{ $record->khatian_no }}</h3>
                                                <div class="result-meta">{{ $record->mouza }}, {{ $record->union_name ? $record->union_name . ', ' : '' }}{{ $record->upazila }}, {{ $record->district }}</div>
                                            </div>
                                            <span class="status-pill">Active</span>
                                        </div>
                                        <div class="result-details">
                                            <span><strong>Owner:</strong> {{ $record->owner_name }}</span>
                                            <span><strong>Area:</strong> {{ $record->area_percentage }}%</span>
                                            <span><strong>Khajna:</strong> {{ $record->khajna_status }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="card form-panel" style="position: sticky; top: 85px;">
                                <h3 style="margin: 0; display:flex; justify-content:space-between; align-items:center;">
                                    Land Map 
                                    <span style="font-size: 0.75rem; background: #dbeafe; color: #1e40af; padding: 0.2rem 0.5rem; border-radius: 999px;">BD Open API</span>
                                </h3>
                                <p class="muted" style="font-size: 0.9rem; margin-top:0;">Interactive geographical plot powered by national land data.</p>
                                <div id="landMap" data-lat="{{ $apiMapCenter[0] ?? 23.685 }}" data-lng="{{ $apiMapCenter[1] ?? 90.3563 }}"></div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="card" style="text-align: center; padding: 2rem;">
                        <p class="muted">Enter search criteria above to locate land records across Bangladesh.</p>
                        <p class="muted" style="font-size: 0.9rem;">Total digitized records: <strong>{{ number_format($totalRecords) }}</strong></p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection