<section id="notices" class="reveal">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Latest Government Notices</h2>
                <p>Important public circulars and tax deadline updates.</p>
            </div>
        </div>

        <div class="notices-grid">
            @forelse ($notices ?? [] as $notice)
                <article class="card">
                    <span class="notice-tag">{{ $notice->notice_type }}</span>
                    <h4>{{ $notice->title }}</h4>
                    <p>{{ $notice->body }}</p>
                </article>
            @empty
                <article class="card">
                    <span class="notice-tag">Circular</span>
                    <h4>No active notices yet</h4>
                    <p>Administrators can add notices from the district dashboard.</p>
                </article>
            @endforelse
        </div>
    </div>
</section>
