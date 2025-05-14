$(document).ready(function () {
    $("#loginForm").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'login');

        $.ajax({
            url: "../Stupidyante/controllers/userControllers.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                $("#loginMsg").html(response.message);
                if (response.status === 'success') {
                    if (response.role === 'admin') {
                        setTimeout(function () {
                            window.location.href = 'views/admin/dashboard.php';
                        }, 1500);
                    } else if (response.role === 'student') {
                        setTimeout(function () {
                            window.location.href = 'views/student/dashboard.php';
                        }, 1500);
                    }

                }
            },
            error: function () {
                $("#loginMsg").html("An error occurred.");
            }
        });
    });
}); 
