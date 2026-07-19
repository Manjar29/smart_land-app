@extends('layouts.home', ['title' => 'District Admin Login'])

@section('content')
    <section class="reveal">
        <div class="container" style="max-width: 760px;">
            <div class="section-head">
                <div>
                    <h2>District Admin Login</h2>
                    <p>Select your district and enter your administrator password to continue.</p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-light">Back to Home</a>
            </div>

            <form class="card form-panel" action="{{ route('district-admin.login.attempt') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label for="district">District</label>
                        <select id="district" name="district" data-dependent-district data-selected-district="{{ old('district') }}" required>
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" placeholder="Enter password" required>
                    </div>
                </div>

                <button class="btn btn-brand" type="submit" style="margin-top: 1rem;">Enter Dashboard</button>
            </form>
        </div>
    </section>
@endsection
