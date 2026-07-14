<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    window.districtUpazilas = @json($districtUpazilas ?? []);

    const revealItems = document.querySelectorAll(".reveal");
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
            }
        });
    }, { threshold: 0.15 });

    revealItems.forEach((item) => revealObserver.observe(item));

    const dependentDistricts = document.querySelectorAll("[data-dependent-district]");
    const dependentUpazilas = document.querySelectorAll("[data-dependent-upazila]");

    function populateUpazilaOptions(districtValue) {
        const options = window.districtUpazilas?.[districtValue] || [];

        dependentUpazilas.forEach((select) => {
            const currentValue = select.dataset.selectedUpazila || select.value;
            select.innerHTML = '<option value="">Select Upazila</option>';

            options.forEach((upazila) => {
                const option = document.createElement('option');
                option.value = upazila;
                option.textContent = upazila;
                if (currentValue === upazila) {
                    option.selected = true;
                }
                select.appendChild(option);
            });

            delete select.dataset.selectedUpazila;
        });
    }

    dependentDistricts.forEach((districtSelect) => {
        districtSelect.addEventListener('change', (event) => {
            dependentUpazilas.forEach((select) => {
                delete select.dataset.selectedUpazila;
            });
            populateUpazilaOptions(event.target.value);
        });

        if (districtSelect.value) {
            populateUpazilaOptions(districtSelect.value);
        }
    });

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

    const mapElement = document.getElementById("landMap");
    if (mapElement) {
        const map = L.map("landMap").setView([23.685, 90.3563], 7);

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
    }
</script>
