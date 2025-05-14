<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editTaskForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editTaskId" name="task_id">

                <div class="mb-3">
                    <label for="editTaskTitle" class="form-label">Task Title</label>
                    <input type="text" class="form-control" id="editTaskTitle" name="task_title" required>
                </div>
                <div class="mb-3">
                    <label for="editTaskDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="editTaskDescription" name="task_description" rows="3"
                        required></textarea>
                </div>
                <div class="mb-3">
                    <label for="editDueDate" class="form-label">Due Date and Time</label>
                    <input type="datetime-local" class="form-control" id="editDueDate" name="due_date" required>
                </div>
                <div class="mb-3">
                    <label for="editStudents" class="form-label">Assign to Students</label>
                    <select id="editStudents" name="students[]" class="form-control" multiple required>

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Update Task</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>