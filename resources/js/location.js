document.addEventListener('DOMContentLoaded', function () {
    const dependentDistricts = document.querySelectorAll("[data-dependent-district]");
    const dependentUpazilas = document.querySelectorAll("[data-dependent-upazila]");
    const dependentUnions = document.querySelectorAll("[data-dependent-union]");

    function initLocations() {
        try {
            const districts = window.districts || [];
            if (!districts || districts.length === 0) return;
            
            dependentDistricts.forEach((select) => {
                const currentValue = select.dataset.selectedDistrict || select.value;
                select.innerHTML = '<option value="">Select District</option>';
                districts.forEach(d => {
                    const option = document.createElement('option');
                    option.value = d;
                    option.textContent = d;
                    if (d === currentValue) option.selected = true;
                    select.appendChild(option);
                });
                
                if (currentValue) {
                    populateUpazilaOptions(currentValue, dependentUpazilas);
                }
            });
        } catch (e) {
            console.error("Failed to load districts", e);
        }
    }

    async function populateUpazilaOptions(districtValue, targetSelects) {
        if (!districtValue) return;
        
        try {
            const encodedDistrict = encodeURIComponent(districtValue);
            const response = await fetch(`/api/bd-upazilas/${encodedDistrict}`);
            if (!response.ok) throw new Error("API Network error");
            const result = await response.json();
            const upazilas = (result.data[0]?.upazillas || []).sort();

            targetSelects.forEach((select) => {
                const currentValue = select.dataset.selectedUpazila || select.value;
                select.innerHTML = '<option value="">Select Upazila</option>';

                upazilas.forEach((upazila) => {
                    const option = document.createElement('option');
                    option.value = upazila;
                    option.textContent = upazila;
                    if (currentValue === upazila) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });

                if (currentValue) {
                    populateUnionOptions(currentValue, dependentUnions);
                }
            });
        } catch (e) {
            console.error("Failed to load upazilas", e);
        }
    }

    function populateUnionOptions(upazilaValue, targetSelects) {
        if (!upazilaValue) return;
        
        const options = [`Union 1`, `Union 2`, `Union 3`];

        targetSelects.forEach((select) => {
            const currentValue = select.dataset.selectedUnion || select.value;
            select.innerHTML = '<option value="">Select Union</option>';

            options.forEach((union) => {
                const option = document.createElement('option');
                option.value = union;
                option.textContent = union;
                if (currentValue === union) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        });
    }

    dependentDistricts.forEach((districtSelect) => {
        districtSelect.addEventListener('change', (event) => {
            dependentUpazilas.forEach((select) => {
                delete select.dataset.selectedUpazila;
                select.innerHTML = '<option value="">Select Upazila</option>';
            });
            dependentUnions.forEach((select) => {
                delete select.dataset.selectedUnion;
                select.innerHTML = '<option value="">Select Union</option>';
            });
            populateUpazilaOptions(event.target.value, dependentUpazilas);
        });
    });

    dependentUpazilas.forEach((upazilaSelect) => {
        upazilaSelect.addEventListener('change', (event) => {
            dependentUnions.forEach((select) => {
                delete select.dataset.selectedUnion;
                select.innerHTML = '<option value="">Select Union</option>';
            });
            populateUnionOptions(event.target.value, dependentUnions);
        });
    });

    // Initialize dropdowns on load
    initLocations();
});
