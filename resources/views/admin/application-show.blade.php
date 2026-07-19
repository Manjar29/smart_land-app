@extends('layouts.home', ['title' => $type . ' Application Review'])

@section('content')
    <section class="reveal">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2>{{ $type }} Application Review</h2>
                    <p>{{ $titleField }}: {{ $identifier }}. Review the details, then accept or delete the request.</p>
                </div>
                <div class="admin-toolbar">
                    <a class="btn btn-light" href="{{ route('home') }}">Back to Home</a>
                    <a class="btn btn-light" href="{{ route('district-admin.dashboard') }}">Back to Dashboard</a>
                    <a class="btn btn-light" href="{{ $trackingRoute }}">View Applicant Status</a>
                </div>
            </div>

            <div class="two-col-grid">
                <article class="card form-panel">
                    <h3>Application Details</h3>
                    <div class="detail-stack">
                        @foreach ($detailRows as $label => $value)
                            <div class="detail-row">
                                <strong>{{ $label }}</strong>
                                <span>{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="card form-panel">
                    <h3>Action Panel</h3>
                    <p class="muted">Accept keeps the case active. Delete marks it as deleted, but the applicant can still see the final condition in tracking.</p>

                    <form action="{{ $actionRoute }}" method="POST" class="action-panel">
                        @csrf
                        <input type="hidden" name="status" value="Approved">
                        @if ($type === 'Mutation')
                            <textarea name="notes" rows="3" placeholder="Add a note before approval or deletion">{{ $application->notes }}</textarea>
                        @endif
                        <button class="btn btn-success" type="submit">Accept</button>
                    </form>

                    <form action="{{ $actionRoute }}" method="POST" class="action-panel" style="margin-top: 0.75rem;">
                        @csrf
                        <input type="hidden" name="status" value="Deleted">
                        @if ($type === 'Mutation')
                            <textarea name="notes" rows="3" placeholder="Add a note before approval or deletion">{{ $application->notes }}</textarea>
                        @endif
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                </article>
            </div>
        </div>
    </section>
@endsection