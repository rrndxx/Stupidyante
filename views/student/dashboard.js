$(document).ready(function () {
    getAssignedTask()

    function showToast(message, isSuccess) {
        const toastEl = document.getElementById('toastMsg');
        const toastBody = document.getElementById('toastBody');

        toastBody.innerText = message;

        toastEl.classList.remove('bg-success', 'bg-danger');
        toastEl.classList.add(isSuccess ? 'bg-success' : 'bg-danger');
        toastEl.classList.add('text-white');

        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    $(document).on("click", ".submit-task", function () {
        const id = $(this).data("id");
        $("#submitTaskId").val(id);
        $("#submitTaskModal").modal("show");
    });

    $(document).on("click", ".edit-user-modal", function () {
        $.ajax({
            url: "../../controllers/userControllers.php",
            method: "GET",
            data: { action: "get_student_by_id" },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const user = response.data;

                    $("#editUserForm input[name='first_name']").val(user.first_name);
                    $("#editUserForm input[name='last_name']").val(user.last_name);
                    $("#editUserForm select[name='gender']").val(user.gender);
                    $("#editUserForm input[name='phone_number']").val(user.phone_number);
                    $("#editUserForm input[name='course']").val(user.course);
                    $("#editUserForm input[name='birthdate']").val(user.birthdate);
                    $("#editUserForm textarea[name='address']").val(user.address);
                    $("#editUserForm input[name='profilepic']").val(user.profile_path);

                    $("#editUserModal").modal("show");
                }
            },
        })
    })

    $("#editUserForm").on("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append("action", "editstudent");

        $.ajax({
            url: "../../controllers/userControllers.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    showToast(response.message, true)
                    setTimeout(() => {
                        $("#editUserModal").modal("hide");
                        window.location.reload()
                    }, 1500);
                }
            },
            error: function () {
                showToast("Submission failed.", false)
            }
        });
    });

    $("#submitTaskForm").on("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append("action", "submittask");

        $.ajax({
            url: "../../controllers/taskControllers.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                showToast(response.message, true);
                if (response.status === "success") {
                    setTimeout(() => {
                        $("#submitTaskModal").modal("hide");
                        window.location.reload()
                    }, 1500);
                }
            },
            error: function () {
                showToast("Submission failed.", false)
            }
        });
    });

    function getAssignedTask() {
        $.ajax({
            url: "../../controllers/taskControllers.php",
            method: "GET",
            data: { action: "get_assigned_task" },
            dataType: 'json',
            success: function (response) {
                const assignedTaskTableBody = $("#assignedTaskTableBody");
                assignedTaskTableBody.empty();

                if (response.status === 'success' && Array.isArray(response.data) && response.data.length > 0) {
                    response.data.forEach(task => {
                        var approved = task.is_approved ? 'Approved.' : 'Not approved yet.';
                        var status = '';
                        if (task.status == 'submitted') {
                            status = 'Submitted';
                        } else if (task.status == 'pending') {
                            status = 'Pending';
                        } else {
                            status = 'Late';
                        }

                        const isDisabled = (task.status === 'submitted');
                        const disabledAttr = isDisabled ? 'disabled' : '';
                        const btnClass = isDisabled ? 'btn-secondary' : 'btn-primary';

                        assignedTaskTableBody.append(`
                        <tr>
                            <th scope="row">${task.task_id}</th>
                            <td>${task.title}</td>
                            <td>${task.description}</td>
                            <td>${task.due_date}</td>
                            <td>${status}, ${approved}</td>
                            <td>
                                <button class="btn btn-sm ${btnClass} submit-task" data-id="${task.task_id}" title="Submit Task" ${disabledAttr}>
                                    <i class="bi bi-upload"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    });
                } else {
                    assignedTaskTableBody.append(`<tr><td colspan="6" class="text-center">No Assigned Tasks Yet.</td></tr>`);
                }
            },
            error: function () {
                alert("Error fetching students.");
            }
        })
    }

});


