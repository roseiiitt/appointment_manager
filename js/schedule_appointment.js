    document.addEventListener('DOMContentLoaded', function () {
        const doctors = JSON.parse('<?php echo json_encode($doctors); ?>');
        const doctorSelect = document.getElementById('doctor');
        const specialitySelect = document.getElementById('speciality');
        const appointmentDateInput = document.getElementById('appointment_date');
    
        // Restrict the appointment date to today and beyond
        const today = new Date().toISOString().split('T')[0];
        appointmentDateInput.setAttribute('min', today);
    
        doctorSelect.addEventListener('change', function () {
            const selectedDoctor = doctors.find(doctor => doctor.name === doctorSelect.value);
            if (selectedDoctor) {
                specialitySelect.value = selectedDoctor.speciality;
            } else {
                specialitySelect.value = '';
            }
        });
    
        specialitySelect.addEventListener('change', function () {
            const selectedSpeciality = specialitySelect.value;
            doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
            const filteredDoctors = doctors.filter(doctor => doctor.speciality === selectedSpeciality);
            filteredDoctors.forEach(doctor => {
                const option = document.createElement('option');
                option.value = doctor.name;
                option.text = doctor.name;
                doctorSelect.add(option);
            });
        });
    });
    