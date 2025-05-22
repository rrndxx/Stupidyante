$(document).ready(function () {
    function showToast(message, isSuccess) {
        const toastEl = document.getElementById('toastMsg');
        const toastBody = document.getElementById('toastBody');

        toastBody.innerText = message;
        toastEl.classList.remove('bg-success', 'bg-danger');
        toastEl.classList.add(isSuccess ? 'bg-success' : 'bg-danger');

        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    $("#registerForm").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'register');

        const btn = document.getElementById("registerBtn");
        const spinner = document.getElementById("registerSpinner");
        spinner.classList.remove("spinner-hidden");
        btn.disabled = true;

        $.ajax({
            url: "../Stupidyante/controllers/userControllers.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {

                if (response.status === 'success') {
                    spinner.classList.add("spinner-hidden");
                    btn.disabled = false;
                    showToast(response.message, true)
                    setTimeout(function () {
                        window.location.href = 'login.php';
                    }, 1000);
                } else {
                    showToast(response.message, false)
                }
            },
            error: function () {
                showToast("An error occurred.", false)

            }
        });
    });
}); 
