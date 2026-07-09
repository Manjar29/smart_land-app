<section id="contact" class="reveal">
    <div class="container contact-grid">
        <article class="card">
            <h2 style="margin-top: 0;">Contact Information</h2>
            <div class="contact-list">
                <div><strong>National Land Service Helpline:</strong> +880 2-5500-7788</div>
                <div><strong>Email:</strong> support@landservice.gov.bd</div>
                <div><strong>Office Hours:</strong> Sunday - Thursday, 9:00 AM - 5:00 PM</div>
                <div><strong>Head Office:</strong> Tejgaon Administrative Area, Dhaka</div>
            </div>
        </article>

        <form class="card" action="#" method="POST">
            @csrf
            <h3 style="margin-top: 0;">Send a Quick Inquiry</h3>
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" placeholder="Your name">
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="you@example.com">
            </div>
            <div class="field">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="4" placeholder="How can we help?"></textarea>
            </div>
            <button class="btn btn-brand" type="submit">Submit Message</button>
        </form>
    </div>
</section>
