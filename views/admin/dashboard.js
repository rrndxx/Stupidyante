$(document).ready(function () {
    getAllStudents();
    getAllTasks();
    getStudentCount();

    $(document).on('click', ".approve-submission", function (e) {
        e.preventDefault();

        const taskId = $(this).data('task-id');
        const studentId = $(this).data('student-id');
        const iconElement = $(this).find('i');

        if (confirm('Are you sure to approve this submission?')) {
            $.ajax({
                url: "../../controllers/taskControllers.php",
                method: "POST",
                data: {
                    action: "approvesubmission",
                    task_id: taskId,
                    student_id: studentId
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        iconElement.removeClass('bi-check-circle text-secondary').addClass('bi-check-circle-fill text-success');
                        iconElement.parent().replaceWith(`<i class="bi bi-check-circle-fill text-success" title="Approved"></i>`);
                        window.location.reload()
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert('Failed to approve submission.');
                }
            });
        } else {
            window.location.reload()
        }

    })

    $(document).on("click", ".view-submissions", function () {
        const taskId = $(this).data("id");

        $.ajax({
            url: "../../controllers/taskControllers.php",
            method: "GET",
            data: { action: "view_submissions", task_id: taskId },
            dataType: 'json',
            success: function (response) {
                const table = $("#submissionTableBody");
                table.empty();

                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach(student => {
                        const hasSubmitted = student.file_path?.trim() !== '';
                        const isPending = student.status.toLowerCase() === 'pending';

                        const viewButton = (hasSubmitted && !isPending)
                            ? `<a href="../../uploads/submissions/${student.file_path}" target="_blank" rel="noopener" class="btn btn-dark btn-sm" title="View Submission">
                                View
                           </a>`
                            : `<span class="text-muted fst-italic">Not submitted</span>`;

                        let approveButton = '';

                        if (isPending) {
                            approveButton = `<span class="text-muted fst-italic">Cannot approve before submission.</span>`;
                        } else if (student.is_approved == 1) {
                            approveButton = `<button class="btn btn-dark btn-sm" disabled title="Already approved">Approved</button>`;
                        } else {
                            approveButton = `<button class="btn btn-dark btn-sm approve-submission" data-task-id="${taskId}" data-student-id="${student.student_id}" title="Approve Submission">Approve</button>`;
                        }

                        table.append(`
                        <tr>
                            <td class="align-middle">${student.student_name}</td>
                            <td class="align-middle text-capitalize">${student.status}</td>
                            <td class="align-middle text-center">${viewButton}</td>
                            <td class="align-middle text-center">${approveButton}</td>
                        </tr>
                    `);
                    });
                } else {
                    table.append(`
                    <tr>
                        <td colspan="5" class="text-center text-muted fst-italic py-3">No data found.</td>
                    </tr>`);
                }

                $("#submissionModal").modal("show");
            },
            error: function () {
                alert("Error fetching submissions.");
            }
        });
    });

    $(document).on("click", ".edit-task", function () {
        const taskId = $(this).data("id");

        $.ajax({
            url: "../../controllers/taskControllers.php",
            method: "GET",
            data: { action: "get_task_by_id", task_id: taskId },
            dataType: 'json',
            success: function (task) {
                $("#editTaskId").val(task.id);
                $("#editTaskTitle").val(task.title);
                $("#editTaskDescription").val(task.description);
                $("#editDueDate").val(task.due_date.replace(" ", "T"));

                const taskStudentIds = task.students.map(id => String(id));

                $.ajax({
                    url: "../../controllers/userControllers.php",
                    method: "GET",
                    data: { action: "get_all_students" },
                    dataType: "json",
                    success: function (students) {
                        const studentSelect = $("#editStudents");
                        studentSelect.empty();

                        students.forEach(student => {
                            const selected = taskStudentIds.includes(String(student.id)) ? "selected" : "";
                            studentSelect.append(`<option value="${student.id}" ${selected}>${student.first_name} ${student.last_name}</option>`);
                        });

                        $("#editTaskModal").modal("show");
                    }
                });
            },
            error: function () {
                alert("Failed to load task data.");
            }
        });
    });

    $(document).on("click", ".delete-task", function () {
        const taskId = $(this).data("id");
        if (confirm("Are you sure you want to delete this task?")) {
            $.ajax({
                url: "../../controllers/taskControllers.php",
                method: "POST",
                data: { action: "deletetask", task_id: taskId },
                dataType: 'json',
                success: function (response) {
                    alert(response.message)
                    window.location.reload();
                },
                error: function () {
                    alert("Failed to load task data.");
                }
            })
        }
    });

    $("#addTaskForm").on("submit", function (e) {
        e.preventDefault();

        const selectedStudents = $("#students").val();
        const taskTitle = $("#taskTitle").val();
        const taskDescription = $("#taskDescription").val();
        const dueDate = $("#dueDate").val();

        var formData = new FormData(this);
        formData.append('action', 'addtask');

        $.ajax({
            url: "../../controllers/taskControllers.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                $("#addTaskModal").modal('hide');
                window.location.reload();
            },
            error: function () {
                alert("An error occurred.");
            }
        });
    });

    $("#editTaskForm").on("submit", function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'edittask');

        $.ajax({
            url: "../../controllers/taskControllers.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                $("#editTaskModal").modal("hide");
                getAllTasks();
            },
            error: function () {
                alert("Error updating task.");
            }
        });
    });

});

function getAllStudents() {
    $.ajax({
        url: "../../controllers/userControllers.php",
        method: "GET",
        data: { action: "get_all_students" },
        dataType: 'json',
        success: function (students) {
            const select = $("#students");
            select.empty();
            students.forEach(student => {
                const fullName = `${student.first_name} ${student.last_name}`;
                select.append(`<option value="${student.id}">${fullName}</option>`);
            });

            const studentsTableBody = $("#studentsTableBody");
            studentsTableBody.empty();

            if (Array.isArray(students) && students.length > 0) {
                students.forEach(student => {
                    studentsTableBody.append(`
                        <tr>
                            <th scope="row">${student.id}</th>
                            <td>${student.first_name}</td>
                            <td>${student.last_name}</td>
                            <td>${student.email}</td>
                            <td>${student.gender}</td>
                            <td>${student.phone_number}</td>
                            <td>${student.course}</td>
                            <td>${student.address}</td>
                            <td>${student.birthdate}</td>
                        </tr>
                    `);
                });
            } else {
                studentsTableBody.append(`<tr><td colspan="9" class="text-center">No students found.</td></tr>`);
            }
        },
        error: function () {
            alert("Error fetching students.");
        }
    });
}

function getAllTasks() {
    $.ajax({
        url: "../../controllers/taskControllers.php",
        method: "GET",
        data: { action: "get_all_tasks" },
        dataType: 'json',
        success: function (tasks) {
            const tasksTableBody = $("#tasksTableBody");
            tasksTableBody.empty();

            if (Array.isArray(tasks) && tasks.length > 0) {
                tasks.forEach(task => {
                    tasksTableBody.append(`
                        <tr>
                            <th scope="row">${task.id}</th>
                            <td>${task.title}</td>
                            <td>${task.description}</td>
                            <td>${task.due_date}</td>
                            <td>
                                <button class="btn btn-sm view-submissions" data-id="${task.id}" title="View Submissions">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm edit-task" data-id="${task.id}" title="Edit Task">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm delete-task" data-id="${task.id}" title="Delete Task">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                tasksTableBody.append(`<tr><td colspan="5" class="text-center">No tasks found.</td></tr>`);
            }
        },
        error: function () {
            alert("Error fetching tasks.");
        }
    });
}

function getStudentCount() {
    $.ajax({
        url: "../../controllers/userControllers.php",
        method: "GET",
        data: { action: "get_student_count" },
        dataType: 'json',
        success: function (response) {
            const totalStudents = $("#totalStudents");
            totalStudents.empty();

            if (response.status == 'success') {
                totalStudents.html(response.message);
            } else if (response.status == 'error') {
                totalStudents.html(response.message);
            }
        },
        error: function () {
            alert("Error fetching students.");
        }
    });
}

