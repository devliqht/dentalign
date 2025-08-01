<div class="mx-auto px-4">
    <div class="mb-8">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Profile Settings</h2>
        <p class="text-gray-600 mb-4">Manage your account information and security settings.</p>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="glass-card p-6 rounded-2xl border-gray-200 border-1 shadow-sm">
            <div class="flex items-center mb-6">
                <div class="w-16 h-16 bg-nhd-blue rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                    <?php echo strtoupper(
                        substr($userDetails["firstName"], 0, 1)
                    ); ?>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-nhd-blue font-family-sans">
                        <?php echo htmlspecialchars(
                            $userDetails["firstName"]
                        ); ?>
                    </h3>
                    <p class="text-gray-600"><?php echo htmlspecialchars(
                        $userDetails["email"]
                    ); ?></p>
                    <p class="text-sm text-gray-500">
                        <?php
                        $displayType = $userDetails["userType"];
                    if ($displayType === "ClinicStaff") {
                        $displayType = "Doctor";
                    }
                    echo htmlspecialchars($displayType);
                    ?>
                    </p>
                </div>
            </div>
            
            <div class="space-y-4 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Account Type:</span>
                    <span class="font-medium"><?php
                    $displayType = $userDetails["userType"];
                    if ($displayType === "ClinicStaff") {
                        $displayType = "Doctor/Staff";
                    }
                    echo htmlspecialchars($displayType);
                    ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Member Since:</span>
                    <span class="font-medium"><?php echo date(
                        "M j, Y",
                        strtotime($userDetails["createdAt"])
                    ); ?></span>
                </div>
            </div>
        </div>

        <!-- Update Profile Form -->
        <div class="glass-card p-6 rounded-2xl border-gray-200 border-1 shadow-sm">
            <h3 class="text-xl font-semibold text-nhd-blue mb-6 font-family-sans">Update Profile Information</h3>
            
            <form method="POST" action="<?php echo BASE_URL; ?>/<?php echo $user[
    "type"
] === "Patient"
    ? "patient"
    : "staff"; ?>/profile" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(
                    $csrf_token
                ); ?>">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="form-group">
                    <label for="first_name" class="text-sm font-medium text-neutral-700 mb-1">First Name</label>
                    <input type="text" id="first_name" name="first_name" 
                           value="<?php echo htmlspecialchars(
                               $userDetails["firstName"]
                           ); ?>" 
                           placeholder="Enter your first name" required />
                </div>
                
                <div class="form-group">
                    <label for="email" class="text-sm font-medium text-neutral-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars(
                               $userDetails["email"]
                           ); ?>" 
                           placeholder="Enter your email" required />
                </div>
                
                <button type="submit" class="w-full bg-nhd-blue/80 glass-card text-white font-medium py-3 px-4 rounded-2xl hover:bg-nhd-blue/90 transition-colors">
                    Update Profile
                </button>
            </form>
        </div>
    </div>

    <!-- Medical Information Section (For Patients Only) -->
    <?php if ($user["type"] === "Patient"): ?>
    <div class="mt-8">
        <div class="glass-card p-6 rounded-2xl shadow-none border-1 border-gray-200">
            <h3 class="text-xl font-semibold text-nhd-blue mb-6 font-family-sans">Medical Information</h3>
            
            <?php if ($patientRecord): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Physical Measurements -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-nhd-brown">Physical Measurements</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Height</label>
                                <p class="text-gray-900 font-medium">
                                    <?php echo $patientRecord["height"]
                                        ? $patientRecord["height"] . " cm"
                                        : "Not recorded"; ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Weight</label>
                                <p class="text-gray-900 font-medium">
                                    <?php echo $patientRecord["weight"]
                                        ? $patientRecord["weight"] . " kg"
                                        : "Not recorded"; ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">BMI</label>
                                <p class="text-gray-900 font-medium">
                                    <?php if (
                                        $patientRecord["height"] &&
                                        $patientRecord["weight"]
                                    ) {
                                        $heightInM =
                                            $patientRecord["height"] / 100;
                                        $bmi =
                                            $patientRecord["weight"] /
                                            ($heightInM * $heightInM);
                                        $bmiValue = number_format($bmi, 1);

                                        // BMI Categories
                                        $bmiClass = "";
                                        $bmiStatus = "";
                                        if ($bmi < 18.5) {
                                            $bmiClass = "text-blue-600";
                                            $bmiStatus = " (Underweight)";
                                        } elseif ($bmi < 25) {
                                            $bmiClass = "text-green-600";
                                            $bmiStatus = " (Normal)";
                                        } elseif ($bmi < 30) {
                                            $bmiClass = "text-yellow-600";
                                            $bmiStatus = " (Overweight)";
                                        } else {
                                            $bmiClass = "text-red-600";
                                            $bmiStatus = " (Obese)";
                                        }

                                        echo "<span class='{$bmiClass}'>" .
                                            $bmiValue .
                                            $bmiStatus .
                                            "</span>";
                                    } else {
                                        echo "Not available";
                                    } ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Medical History -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-nhd-brown">Medical History</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Known Allergies</label>
                                <div class="text-gray-900">
                                    <?php if ($patientRecord["allergies"]): ?>
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                            <p class="text-red-800 text-sm">
                                                ⚠️ <?php echo nl2br(
                                                    htmlspecialchars(
                                                        $patientRecord[
                                                            "allergies"
                                                        ]
                                                    )
                                                ); ?>
                                            </p>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-gray-500">No known allergies recorded</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Last Visit</label>
                                <p class="text-gray-900 font-medium">
                                    <?php echo $patientRecord["lastVisit"]
                                        ? date(
                                            "M j, Y",
                                            strtotime(
                                                $patientRecord["lastVisit"]
                                            )
                                        )
                                        : "No visits recorded"; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Record Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-nhd-brown">Record Information</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Record ID</label>
                                <p class="text-gray-600 text-sm font-mono">
                                    #<?php echo str_pad(
                                        $patientRecord["recordID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Record Created</label>
                                <p class="text-gray-900">
                                    <?php echo date(
                                        "M j, Y",
                                        strtotime($patientRecord["createdAt"])
                                    ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="mt-6 p-4 bg-nhd-blue/10 border border-nhd-blue/50 rounded-lg">
                    <h5 class="text-sm font-medium text-nhd-blue mb-2">📋 Note</h5>
                    <p class="text-sm text-nhd-blue">
                        Medical measurements and information are updated by healthcare providers during your appointments. 
                        If you notice any inaccuracies, please contact your doctor.
                    </p>
                </div>

            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h4 class="text-lg font-medium text-gray-600 mb-2">No Medical Record Found</h4>
                    <p class="text-gray-500 mb-4">
                        Your medical record will be created automatically when you book your first appointment.
                    </p>
                    <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
                       class="inline-flex items-center px-4 py-2 glass-card bg-blue-600/80 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Book Your First Appointment
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Change Password Section -->
    <div class="mt-8">
        <div class="glass-card p-6 rounded-2xl shadow-none border-1 border-gray-200">
            <h3 class="text-xl font-semibold text-nhd-blue mb-6 font-family-sans">Change Password</h3>
            
            <form method="POST" action="<?php echo BASE_URL; ?>/<?php echo $user[
    "type"
] === "Patient"
    ? "patient"
    : "staff"; ?>/profile" class="space-y-4 max-w-md">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(
                    $csrf_token
                ); ?>">
                <input type="hidden" name="action" value="update_password">
                
                <div class="form-group">
                    <label for="current_password" class="text-sm font-medium text-neutral-700 mb-1">Current Password</label>
                    <input type="password" id="current_password" name="current_password" 
                           placeholder="Enter your current password" required />
                </div>
                
                <div class="form-group">
                    <label for="new_password" class="text-sm font-medium text-neutral-700 mb-1">New Password</label>
                    <input type="password" id="new_password" name="new_password" 
                           placeholder="Enter new password (min. 8 characters)" required />
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="text-sm font-medium text-neutral-700 mb-1">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           placeholder="Confirm your new password" required />
                    <div id="password-match" class="mt-1 text-sm"></div>
                </div>

                <!-- Password Requirements -->
                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Password Requirements:</h4>
                    <div class="space-y-1 text-xs text-gray-600">
                        <div id="req-length" class="requirement invalid">✗ At least 8 characters long</div>
                        <div id="req-uppercase" class="requirement invalid">✗ One uppercase letter</div>
                        <div id="req-lowercase" class="requirement invalid">✗ One lowercase letter</div>
                        <div id="req-number" class="requirement invalid">✗ One number</div>
                        <div id="req-special" class="requirement invalid">✗ One special character</div>
                    </div>
                </div>
                
                <button type="submit" id="update-password-btn" class="w-full bg-nhd-green/85 glass-card text-white font-medium py-3 px-4 rounded-2xl hover:bg-nhd-green/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Update Password
                </button>
            </form>
        </div>
    </div>

    <!-- Security Tips -->
    <div class="mt-8">
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
            <h4 class="text-lg font-semibold text-blue-800 mb-3 font-family-bodoni">Security Tips</h4>
            <ul class="space-y-2 text-sm text-blue-700">
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Use a strong password with at least 6 characters
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Don't share your login credentials with anyone
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Log out from shared or public computers
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Keep your email address up to date for important notifications
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Server Messages for Toast -->
<script>
window.serverMessages = {
    <?php if (isset($_SESSION["success"])): ?>
        success: <?php echo json_encode($_SESSION["success"]); ?>,
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION["error"])): ?>
        error: <?php echo json_encode($_SESSION["error"]); ?>,
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>
};

// Add confirmation dialogs for form submissions
document.addEventListener('DOMContentLoaded', function() {
    const updateProfileForm = document.querySelector('form[method="POST"][action*="/profile"]:not([action*="update_password"])');
    if (updateProfileForm) {
        updateProfileForm.addEventListener('submit', function(e) {
            const actionInput = this.querySelector('input[name="action"]');
            if (actionInput && actionInput.value === 'update_profile') {
                if (!confirm('Are you sure you want to update your profile information?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    }

    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordMatch = document.getElementById('password-match');
    const updatePasswordBtn = document.getElementById('update-password-btn');
    
    if (newPasswordInput && confirmPasswordInput && passwordMatch && updatePasswordBtn) {
        const requirements = {
            length: document.getElementById('req-length'),
            uppercase: document.getElementById('req-uppercase'),
            lowercase: document.getElementById('req-lowercase'),
            number: document.getElementById('req-number'),
            special: document.getElementById('req-special')
        };

        function validatePassword(password) {
            const checks = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };

            for (let check in checks) {
                if (checks[check]) {
                    requirements[check].classList.add('valid');
                    requirements[check].classList.remove('invalid');
                    requirements[check].innerHTML = requirements[check].innerHTML.replace('✗', '✓');
                } else {
                    requirements[check].classList.add('invalid');
                    requirements[check].classList.remove('valid');
                    requirements[check].innerHTML = requirements[check].innerHTML.replace('✓', '✗');
                }
            }

            return Object.values(checks).every(check => check);
        }

        function validatePasswordMatch() {
            const password = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (confirmPassword === '') {
                passwordMatch.textContent = '';
                passwordMatch.className = 'mt-1 text-sm';
                return false;
            }
            
            if (password === confirmPassword) {
                passwordMatch.textContent = '✓ Passwords match';
                passwordMatch.className = 'mt-1 text-sm text-green-600';
                return true;
            } else {
                passwordMatch.textContent = '✗ Passwords do not match';
                passwordMatch.className = 'mt-1 text-sm text-red-600';
                return false;
            }
        }

        function updateSubmitButton() {
            const passwordValid = validatePassword(newPasswordInput.value);
            const passwordsMatch = validatePasswordMatch();
            const currentPasswordFilled = document.getElementById('current_password').value.length > 0;
            
            updatePasswordBtn.disabled = !(passwordValid && passwordsMatch && currentPasswordFilled);
        }

        newPasswordInput.addEventListener('input', updateSubmitButton);
        confirmPasswordInput.addEventListener('input', updateSubmitButton);
        document.getElementById('current_password').addEventListener('input', updateSubmitButton);
        
        updateSubmitButton();
    }

    const updatePasswordForm = document.querySelector('form input[name="action"][value="update_password"]');
    if (updatePasswordForm) {
        const passwordForm = updatePasswordForm.closest('form');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to change your password?')) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    }
});
</script>

<style>
.requirement {
    transition: color 0.3s ease;
    color: #6b7280;
}

.requirement.valid {
    color: #10b981;
}

.requirement.invalid {
    color: #ef4444;
}

.glass-card {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
</style>
