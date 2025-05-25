document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100, // Sesuaikan offset untuk header yang sticky
                    behavior: 'smooth'
                });
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
            const masterMkSelect = document.getElementById('id_master_mk');
            const kodeMkInput = document.getElementById('kode_mk');
            const namaMkInput = document.getElementById('nama_mk');
            const sksInput = document.getElementById('sks');

            // Function to clear all auto-filled fields
            function clearAutoFilledFields() {
                kodeMkInput.value = '';
                namaMkInput.value = '';
                sksInput.value = '';
            }

            // Function to fill fields based on selected option
            function fillFieldsFromSelection() {
                const selectedOption = masterMkSelect.options[masterMkSelect.selectedIndex];
                
                if (selectedOption.value === '') {
                    clearAutoFilledFields();
                    return;
                }

                // Get data from selected option
                const kode = selectedOption.getAttribute('data-kode') || '';
                const nama = selectedOption.getAttribute('data-nama') || '';
                const sks = selectedOption.getAttribute('data-sks') || '';

                // Fill the readonly fields
                kodeMkInput.value = kode;
                namaMkInput.value = nama;
                sksInput.value = sks;

                // Trigger change events for validation if needed
                kodeMkInput.dispatchEvent(new Event('change'));
                namaMkInput.dispatchEvent(new Event('change'));
                sksInput.dispatchEvent(new Event('change'));
            }

            // Add event listener to master mata kuliah select
            masterMkSelect.addEventListener('change', fillFieldsFromSelection);

            // Initialize on page load if there's an old value (for form validation errors)
            if (masterMkSelect.value) {
                fillFieldsFromSelection();
            }
});