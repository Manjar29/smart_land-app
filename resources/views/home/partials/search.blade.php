<section id="search" class="reveal">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Search Land Record</h2>
                <p>Find records by Dag Number or Khatian Number and narrow results by district and upazila.</p>
            </div>
            <a href="{{ route('mutation.apply') }}" class="btn btn-light">Apply for Mutation</a>
        </div>
        <form class="search-box" action="{{ route('land.search') }}" method="GET">
            <div class="field">
                <label for="dag_no">Dag Number</label>
                <input id="dag_no" name="dag_no" type="text" placeholder="e.g. 1205">
            </div>
            <div class="field">
                <label for="khatian_no">Khatian Number</label>
                <input id="khatian_no" name="khatian_no" type="text" placeholder="e.g. KH-341">
            </div>
            <div class="field">
                <label for="district">District</label>
                    <select id="district" name="district" data-dependent-district data-selected-district="{{ request('district') }}">
                    <option value="">Select District</option>
                </select>
            </div>
            <div class="field">
                <label for="upazila">Upazila</label>
                    <select id="upazila" name="upazila" data-dependent-upazila data-selected-upazila="{{ request('upazila') }}">
                    <option value="">Select Upazila</option>
                </select>
            </div>
            <div style="display: flex; align-items: end;">
                <button class="btn btn-brand" style="width: 100%;" type="submit">Search Records</button>
            </div>
        </form>
    </div>
</section>
