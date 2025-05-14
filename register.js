$(document).ready(function () {
    $("#registerForm").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'register');

        $.ajax({
            url: "../Stupidyante/controllers/userControllers.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                $("#registerMsg").html(response.message);
                if (response.status === 'success') {
                    setTimeout(function () {
                        window.location.href = 'login.php';
                    }, 1500);
                }
            },
            error: function () {
                $("#registerMsg").html("An error occurred.");
            }
        });
    });
}); 
