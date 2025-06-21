<div class="max-w-4xl px-4 py-8">
    <div class="px-6 pt-6">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Book Appointment</h2>
        <p class="text-gray-600">Schedule your dental appointment with one of our experienced doctors.</p>
    </div>

    <div class="bg-white rounded-lg p-6">
        <form method="POST" action="<?php echo BASE_URL; ?>/patient/book-appointment" class="space-y-6" id="appointment-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(
                $csrf_token
            ); ?>">
            
            <!-- Doctor Selection -->
            <div class="form-group">
                <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">Select Doctor</label>
                <select id="doctor_id" name="doctor_id" require>
                    <option value="">Choose a doctor</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?php echo $doctor["UserID"]; ?>" 
                                <?php echo $selectedDoctorId ==
                                $doctor["UserID"]
                                    ? "selected"
                                    : ""; ?>>
                            Dr. <?php echo htmlspecialchars(
                                $doctor["FirstName"] . " " . $doctor["LastName"]
                            ); ?> 
                            - <?php echo htmlspecialchars(
                                $doctor["Specialization"]
                            ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date Selection -->
            <div class="form-group">
                <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Appointment Date</label>
                <input type="date" 
                       id="appointment_date" 
                       name="appointment_date" 
                       required 
                       min="<?php echo date("Y-m-d", strtotime("+1 day")); ?>"
                       value="<?php echo htmlspecialchars($selectedDate); ?>"
                      >
            </div>

            <!-- Time Selection -->
            <div class="form-group">
                <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">Appointment Time</label>
                <select id="appointment_time" name="appointment_time" required>
                    <option value="">Select a time slot</option>
                    <?php if (!empty($availableTimeSlots)): ?>
                        <?php foreach ($availableTimeSlots as $timeSlot): ?>
                            <option value="<?php echo $timeSlot; ?>">
                                <?php echo date(
                                    "g:i A",
                                    strtotime($timeSlot)
                                ); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p class="text-sm text-gray-500 mt-1">Available time slots will appear after selecting a doctor and date.</p>
            </div>

            <!-- Appointment Type -->
            <div class="form-group">
                <label for="appointment_type" class="block text-sm font-medium text-gray-700 mb-2">Appointment Type</label>
                <select id="appointment_type" name="appointment_type" required>
                    <option value="">Select appointment type</option>
                    <option value="Consultation">Consultation</option>
                    <option value="Cleaning">Cleaning</option>
                    <option value="Filling">Filling</option>
                    <option value="Root Canal">Root Canal</option>
                    <option value="Extraction">Extraction</option>
                    <option value="Orthodontics">Orthodontics</option>
                    <option value="Emergency">Emergency</option>
                    <option value="Follow-up">Follow-up</option>
                </select>
            </div>

            <!-- Reason for Visit -->
            <div class="form-group">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Visit</label>
                <textarea id="reason" 
                         name="reason" 
                         rows="4" 
                         required 
                         placeholder="Please describe your symptoms or the reason for your visit..."
                         ></textarea>
                <p class="text-sm text-gray-500 mt-1">Minimum 10 characters required.</p>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-4">
                <a href="<?php echo BASE_URL; ?>/patient/dashboard" 
                   class="px-4 py-2 text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        ">
                    Book Appointment
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Appointment Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
            <div>
                <h4 class="font-medium mb-2">Clinic Hours:</h4>
                <ul class="space-y-1">
                    <li>Monday - Friday: 8:00 AM - 6:00 PM</li>
                    <li>Emergency services available 24/7</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium mb-2">Important Notes:</h4>
                <ul class="space-y-1">
                    <li>• Please arrive 15 minutes early</li>
                    <li>• Bring your ID and insurance card</li>
                    <li>• Cancel at least 24 hours in advance</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const doctorSelect = document.getElementById('doctor_id');
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('appointment_time');

    function updateTimeSlots() {
        const doctorId = doctorSelect.value;
        const date = dateInput.value;

        if (doctorId && date) {
            // reload page for time slots
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('doctor_id', doctorId);
            currentUrl.searchParams.set('date', date);
            window.location.href = currentUrl.toString();
        } else {
            timeSelect.innerHTML = '<option value="">Select a time slot</option>';
        }
    }

    doctorSelect.addEventListener('change', updateTimeSlots);
    dateInput.addEventListener('change', updateTimeSlots);

    document.getElementById('appointment-form').addEventListener('submit', function(e) {
        const reason = document.getElementById('reason').value;
        if (reason.length < 10) {
            e.preventDefault();
            alert('Please provide a reason for your visit (at least 10 characters).');
            return false;
        }
    });
});
</script> 