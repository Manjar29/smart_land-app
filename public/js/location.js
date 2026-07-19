document.addEventListener('DOMContentLoaded', function () {
    const dependentDistricts = document.querySelectorAll("[data-dependent-district]");
    const dependentUpazilasGlobal = document.querySelectorAll("[data-dependent-upazila]");

    function getScopedElements(element) {
        const scope = element.closest('form') || document;
        return {
            upazilas: scope.querySelectorAll("[data-dependent-upazila]"),
            unions: scope.querySelectorAll("[data-dependent-union]")
        };
    }

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
                    const { upazilas } = getScopedElements(select);
                    populateUpazilaOptions(currentValue, upazilas, select);
                }
            });
        } catch (e) {
            console.error("Failed to load districts", e);
        }
    }

    async function populateUpazilaOptions(districtValue, targetSelects, sourceSelect) {
        if (!districtValue) return;
        
        try {
            const encodedDistrict = encodeURIComponent(districtValue);
            const response = await fetch(`/api/bd-upazilas/${encodedDistrict}`);
            if (!response.ok) throw new Error("API Network error");
            const result = await response.json();
            const upazilas = (result.data[0]?.upazillas || []).sort();

            const { unions } = getScopedElements(sourceSelect);

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
                    populateUnionOptions(currentValue, unions);
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
            const { upazilas, unions } = getScopedElements(districtSelect);

            upazilas.forEach((select) => {
                delete select.dataset.selectedUpazila;
                select.innerHTML = '<option value="">Select Upazila</option>';
            });
            unions.forEach((select) => {
                delete select.dataset.selectedUnion;
                select.innerHTML = '<option value="">Select Union</option>';
            });
            populateUpazilaOptions(event.target.value, upazilas, districtSelect);
        });
    });

    dependentUpazilasGlobal.forEach((upazilaSelect) => {
        upazilaSelect.addEventListener('change', (event) => {
            const { unions } = getScopedElements(upazilaSelect);

            unions.forEach((select) => {
                delete select.dataset.selectedUnion;
                select.innerHTML = '<option value="">Select Union</option>';
            });
            populateUnionOptions(event.target.value, unions);
        });
    });

    // Initialize dropdowns on load
    initLocations();
});
