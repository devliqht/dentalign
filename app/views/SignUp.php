<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - DentAlign</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-row {
            display: flex;
            gap: 15px;
        }
        .form-row .form-group {
            flex: 1;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: #dc3545;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }
        .success {
            color: #155724;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
        }
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            color: #007bff;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
        #specialization-group {
            display: none;
        }
    </style>
</head>
<body>
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
</body>
</html>
