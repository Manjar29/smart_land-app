@extends('layouts.home', ['title' => 'District Admin Dashboard'])

@section('content')
    <section class="reveal">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2>{{ $district }} District Admin</h2>
                    <p>Manage khajna, mutation and notices for the selected district only.</p>
                </div>
                <div class="admin-toolbar">
                    <a class="btn btn-light" href="{{ route('home') }}">Back to Home</a>
                    <form action="{{ route('district-admin.logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger" type="submit">Logout</button>
                    </form>
                </div>
            </div>

            @if (session('adminMessage'))
                <div class="card receipt-banner">{{ session('adminMessage') }}</div>
            @endif

            <div class="two-col-grid">
                <article class="card form-panel">
                    <h3>See Applicants: Khajna</h3>
                    @forelse ($khajnaApplications as $application)
                        <div class="card application-card" style="box-shadow: none; margin-top: 0.75rem;">
                            <strong>{{ $application->receipt_no }}</strong>
                            <p>{{ $application->applicant_name }} - {{ $application->dag_no }} / {{ $application->khatian_no }}</p>
                            <p>{{ $application->land_percentage }}% = {{ number_format($application->amount, 2) }} Taka</p>
                            <span class="status-pill">{{ $application->status }}</span>
                            <div class="button-row">
                                <a class="btn btn-brand" href="{{ route('district-admin.khajna.show', $application) }}">View</a>
                            </div>
                        </div>
                    @empty
                        <p class="muted">No khajna applications for this district yet.</p>
                    @endforelse
                </article>

                <article class="card form-panel">
                    <h3>See Applicants: Mutation</h3>
                    @forelse ($mutationApplications as $application)
                        <div class="card application-card" style="box-shadow: none; margin-top: 0.75rem;">
                            <strong>{{ $application->tracking_no }}</strong>
                            <p>{{ $application->applicant_name }}</p>
                            <p>{{ $application->dag_no }} / {{ $application->khatian_no }} - {{ $application->land_percentage }}% = {{ number_format($application->amount, 2) }} Taka</p>
                            <p>Applicant IDs: {{ $application->applicant_id_no }}</p>
                            <span class="status-pill">{{ $application->status }}</span>
                            <div class="button-row">
                                <a class="btn btn-brand" href="{{ route('district-admin.mutation.show', $application) }}">View</a>
                            </div>
                        </div>
                    @empty
                        <p class="muted">No mutation applications for this district yet.</p>
                    @endforelse
                </article>
            </div>

            <div class="two-col-grid">
                <form class="card form-panel" action="{{ route('district-admin.notices.store') }}" method="POST">
                    @csrf
                    <h3>Add Notice</h3>
                    <p class="muted">Each notice is saved with the current timestamp automatically.</p>
                    <div class="form-grid">
                        <div class="field">
                            <label for="title">Title</label>
                            <input id="title" name="title" type="text" required>
                        </div>
                        <div class="field">
                            <label for="notice_type">Type</label>
                            <input id="notice_type" name="notice_type" type="text" required>
                        </div>
                        <div class="field" style="grid-column: span 2;">
                            <label for="body">Body</label>
                            <textarea id="body" name="body" rows="4" required></textarea>
                        </div>
                    </div>
                    <button class="btn btn-brand" type="submit">Save Notice</button>
                </form>

                <div class="card form-panel">
                    <h3>Existing Notices</h3>
                    @forelse ($notices as $notice)
                        <form class="card" action="{{ route('district-admin.notices.update', $notice) }}" method="POST" style="box-shadow: none; margin-top: 0.75rem;">
                            @csrf
                            @method('PATCH')
                            <p class="muted" style="margin-bottom: 0.5rem; font-size: 0.85rem;">Saved at (sysdate/timestamp): {{ $notice->updated_at->format('Y-m-d H:i:s') }}</p>
                            <div class="form-grid">
                                <div class="field">
                                    <label>Title</label>
                                    <input name="title" type="text" value="{{ $notice->title }}">
                                </div>
                                <div class="field">
                                    <label>Type</label>
                                    <input name="notice_type" type="text" value="{{ $notice->notice_type }}">
                                </div>
                                <div class="field" style="grid-column: span 2;">
                                    <label>Body</label>
                                    <textarea name="body" rows="3">{{ $notice->body }}</textarea>
                                </div>
                                <div class="field">
                                    <label>&nbsp;</label>
                                    <label><input type="checkbox" name="is_active" value="1" @checked($notice->is_active)> Active</label>
                                </div>
                            </div>
                            <div class="button-row" style="margin-top:0.5rem;">
                                <button class="btn btn-brand" type="submit">Update</button>
                            </div>
                        </form>
                        <form action="{{ route('district-admin.notices.destroy', $notice) }}" method="POST" style="margin-top: 0.5rem;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Delete Notice</button>
                        </form>
                    @empty
                        <p class="muted">No notices available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
