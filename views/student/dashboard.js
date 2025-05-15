$(document).ready(function () {
    getAssignedTask()
})

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
                    assignedTaskTableBody.append(`
                <tr>
                    <th scope="row">${task.task_id}</th>
                    <td>${task.title}</td>
                    <td>${task.description}</td>
                    <td>${task.due_date}</td>
                    <td>${task.status}</td>
                    <td>
                        <button class="btn btn-sm submit-task" data-id="${task.task_id}" title="Submit Task">
                            <i class="bi bi-upload"></i>
                        </button>
                    </td>
                </tr>
            `);
                });
            } else {
                assignedTaskTableBody.append(`<tr><td colspan="5" class="text-center">No Assigned Tasks Found.</td></tr>`);
            }
        },
        error: function () {
            alert("Error fetching students.");
        }
    })
}