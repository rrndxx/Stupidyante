<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="addTaskForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="taskTitle" class="form-label">Task Title</label>
                    <input type="text" class="form-control" id="taskTitle" name="task_title" required>
                </div>
                <div class="mb-3">
                    <label for="taskDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="taskDescription" name="task_description" rows="3"
                        required></textarea>
                </div>
                <div class="mb-3">
                    <label for="dueDate" class="form-label">Due Date and Time</label>
                    <input type="datetime-local" class="form-control" id="dueDate" name="due_date" required>
                </div>
                <div class="mb-3">
                    <label for="students" class="form-label">Assign to Students</label>
                    <select id="students" name="students[]" class="form-control" multiple required>

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Save Task</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>