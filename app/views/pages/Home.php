<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome to DentAlign</h2>
        <p class="text-gray-600 mb-4">You have successfully logged into your account. This is your dashboard where you can manage your dental care activities.</p>
    </div>

    <div class="bg-black rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Your Account Information</h3>

        <div class="space-y-4">
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <div class="font-medium text-gray-700">Name:</div>
                <div class="text-gray-900"><?php echo htmlspecialchars(
                    $_SESSION["user_name"]
                ); ?></div>
            </div>
            
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <div class="font-medium text-gray-700">Email:</div>
                <div class="text-gray-900"><?php echo htmlspecialchars(
                    $_SESSION["user_email"]
                ); ?></div>
            </div>
            
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <div class="font-medium text-gray-700">Account Type:</div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $_SESSION[
                        "user_type"
                    ] === "Patient"
                        ? "bg-blue-100 text-blue-800"
                        : "bg-green-100 text-green-800"; ?>">
                        <?php echo htmlspecialchars($_SESSION["user_type"]); ?>
                    </span>
                </div>
            </div>
            
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <div class="font-medium text-gray-700">User ID:</div>
                <div class="text-gray-900"><?php echo htmlspecialchars(
                    $_SESSION["user_id"]
                ); ?></div>
            </div>
        </div>

        <?php if ($_SESSION["user_type"] === "Patient"): ?>
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-lg font-medium text-gray-900 mb-2">Patient Features</h4>
                <p class="text-gray-600">As a patient, you can schedule appointments, view your treatment history, and manage your dental records.</p>
            </div>
        <?php elseif ($_SESSION["user_type"] === "ClinicStaff"): ?>
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-lg font-medium text-gray-900 mb-2">Staff Features</h4>
                <p class="text-gray-600">As clinic staff, you have access to patient management tools, appointment scheduling, and administrative functions.</p>
            </div>
        <?php endif; ?>
    </div>
</div> 