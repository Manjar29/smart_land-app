<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>

    const revealItems = document.querySelectorAll(".reveal");
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
            }
        });
    }, { threshold: 0.15 });

    revealItems.forEach((item) => revealObserver.observe(item));

    window.districts = @json($districts ?? []);
</script>
<script src="{{ asset('js/location.js') }}"></script>
<script>

    const khajnaAmountInput = document.getElementById('land_percentage');
    const khajnaAmountPreview = document.getElementById('amount_preview');
    const mutationAmountPreview = document.getElementById('estimated_amount');

    function updateChargePreview() {
        const percentage = parseFloat(khajnaAmountInput?.value || '0');
        const khajnaAmount = Number.isFinite(percentage) ? (percentage * 12) : 0;
        const mutationAmount = Number.isFinite(percentage) ? (percentage * 50) : 0;

        if (khajnaAmountPreview) {
            khajnaAmountPreview.value = `${khajnaAmount.toFixed(2)} Taka`;
        }

        if (mutationAmountPreview) {
            mutationAmountPreview.value = `${mutationAmount.toFixed(2)} Taka`;
        }
    }

    if (khajnaAmountInput) {
        khajnaAmountInput.addEventListener('input', updateChargePreview);
        updateChargePreview();
    }

    const applicantList = document.getElementById('mutation-applicants');
    const applicantTemplate = document.getElementById('applicant-template');
    const addApplicantButton = document.querySelector('[data-add-applicant]');

    if (applicantList && applicantTemplate && addApplicantButton) {
        addApplicantButton.addEventListener('click', () => {
            const nextIndex = applicantList.querySelectorAll('[data-applicant-card]').length + 1;
            const template = applicantTemplate.innerHTML.replaceAll('__INDEX__', String(nextIndex));
            applicantList.insertAdjacentHTML('beforeend', template);
        });
    }

    const mapElement = document.getElementById("landMap");
    if (mapElement) {
        const lat = parseFloat(mapElement.dataset.lat || 23.685);
        const lng = parseFloat(mapElement.dataset.lng || 90.3563);
        const map = L.map("landMap").setView([lat, lng], 9);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "&copy; OpenStreetMap contributors"
        }).addTo(map);

        const points = [
            { name: "Dhaka Land Office", lat: 23.8103, lng: 90.4125 },
            { name: "Rajshahi Survey Unit", lat: 24.3745, lng: 88.6042 },
            { name: "Khulna Service Point", lat: 22.8456, lng: 89.5403 },
            { name: "Chattogram Land Hub", lat: 22.3569, lng: 91.7832 }
        ];

        points.forEach((point) => {
            L.marker([point.lat, point.lng])
                .addTo(map)
                .bindPopup(`<strong>${point.name}</strong><br>GPS: ${point.lat}, ${point.lng}`);
        });

        if (lat !== 23.685 && lng !== 90.3563) {
            L.marker([lat, lng])
                .addTo(map)
                .bindPopup(`<strong>Search Location</strong><br>GPS: ${lat}, ${lng}`)
                .openPopup();
        }
    }
</script>
