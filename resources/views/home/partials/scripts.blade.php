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
