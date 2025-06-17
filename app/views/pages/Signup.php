<div class="container">
        <h2>Sign Up for DentAlign</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo BASE_URL; ?>/signup">
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="user_type">Account Type:</label>
                <select id="user_type" name="user_type" required onchange="toggleSpecialization()">
                    <option value="">Select account type</option>
                    <option value="Patient">Patient</option>
                    <option value="Doctor">Doctor</option>
                </select>
            </div>
            
            <div class="form-group" id="specialization-group">
                <label for="specialization">Specialization:</label>
                <input type="text" id="specialization" name="specialization" placeholder="e.g., General Dentistry, Orthodontics">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            
            <button type="submit">Sign Up</button>
        </form>
        
        <div class="links">
            <p>Already have an account? <a href="<?php echo BASE_URL; ?>/login">Login here</a></p>
        </div>
    </div>

    <script>
        function toggleSpecialization() {
            const userType = document.getElementById('user_type').value;
            const specializationGroup = document.getElementById('specialization-group');
            const specializationInput = document.getElementById('specialization');
            
            if (userType === 'Doctor') {
                specializationGroup.style.display = 'block';
                specializationInput.required = true;
            } else {
                specializationGroup.style.display = 'none';
                specializationInput.required = false;
                specializationInput.value = '';
            }
        }
    </script>