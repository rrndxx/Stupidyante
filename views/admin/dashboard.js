$(document).ready(function () {
    getAllStudents();
    getAllTasks();
    getStudentCount();

    $(document).on("click", ".view-task", function () {
        const taskId = $(this).data("id");
        // open view modal or fetch details
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
                                <button class="btn btn-sm view-task" data-id="${task.id}" title="View Submissions">
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

