$(document).ready(function() {
    // Tab switching functionality
    $('#loginTab').click(function() {
        $(this).addClass('border-orange-500 text-orange-500').removeClass('border-gray-200 text-gray-500');
        $('#registerTab').addClass('border-gray-200 text-gray-500').removeClass('border-orange-500 text-orange-500');
        $('#loginForm').removeClass('hidden');
        $('#registerForm').addClass('hidden');
    });

    $('#registerTab').click(function() {
        $(this).addClass('border-orange-500 text-orange-500').removeClass('border-gray-200 text-gray-500');
        $('#loginTab').addClass('border-gray-200 text-gray-500').removeClass('border-orange-500 text-orange-500');
        $('#registerForm').removeClass('hidden');
        $('#loginForm').addClass('hidden');
    });

    // Login form submission
    $('#loginBtn').click(function() {
        const email = $('#loginEmail').val();
        const password = $('#loginPassword').val();

        if (!email || !password) {
            alert('Please fill in all fields');
            return;
        }

        $.ajax({
            url: '../app/controllers/AuthController.php',
            type: 'POST',
            data: {
                action: 'login',
                email: email,
                password: password
            },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Register form submission
    $('#registerBtn').click(function() {
        const formData = new FormData();
        formData.append('action', 'register');
        formData.append('firstName', $('#firstName').val());
        formData.append('lastName', $('#lastName').val());
        formData.append('email', $('#email').val());
        formData.append('gender', $('#gender').val());
        formData.append('phone', $('#phone').val());
        formData.append('course', $('#course').val());
        formData.append('address', $('#address').val());
        formData.append('birthdate', $('#birthdate').val());
        formData.append('password', $('#password').val());
        
        const profileFile = $('#profile')[0].files[0];
        if (profileFile) {
            formData.append('profile', profileFile);
        }

        // Validate all fields are filled
        if (!formData.get('firstName') || !formData.get('lastName') || !formData.get('email') || 
            !formData.get('gender') || !formData.get('phone') || !formData.get('course') || 
            !formData.get('address') || !formData.get('birthdate') || !formData.get('password') || 
            !profileFile) {
            alert('Please fill in all fields');
            return;
        }

        $.ajax({
            url: '../app/controllers/AuthController.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    alert('Registration successful! Please login.');
                    $('#loginTab').click();
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
}); 