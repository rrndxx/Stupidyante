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

    function showMFAModal() {
        const mfaModal = new bootstrap.Modal(document.getElementById('mfaModal'));
        mfaModal.show();
    }

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
                console.log(response.code)
                if (response.status === 'success') {
                    showMFAModal();
                } else {
                    showToast("Error.", false);
                }
            },
            error: function () {
                showToast("An error occurred while processing your request.", false);
            }
        });
    });

    $('#mfaForm').on('submit', function (e) {
        e.preventDefault();
        const code = $('#mfaCode').val().trim();
        console.log(code)

        $.ajax({
            type: 'POST',
            url: '../Stupidyante/controllers/userControllers.php',
            data: { code: code, action: 'two_step_verify' },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    showToast(response.message, true);
                    setTimeout(function () {
                        if (response.role === 'admin') {
                            window.location.href = 'views/admin/index.php';
                        } else if (response.role === 'student') {
                            window.location.href = 'views/student/dashboard.php';
                        }
                    }, 1500);
                } else {
                    showToast(response.message, true);
                }
            },
            error: function () {
                showToast("An error occurred while processing your request.", false);
            }
        });
    });
});