<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="addTaskForm" class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addTaskModalLabel">
                    <i class="bi bi-plus-circle me-2"></i> Add New Task
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="taskTitle" class="form-label">
                        <i class="bi bi-card-text me-1"></i> Task Title
                    </label>
                    <input type="text" class="form-control" id="taskTitle" name="task_title"
                        placeholder="Enter task title" required>
                </div>

                <div class="mb-3">
                    <label for="taskDescription" class="form-label">
                        <i class="bi bi-textarea-t me-1"></i> Description
                    </label>
                    <textarea class="form-control" id="taskDescription" name="task_description" rows="3"
                        placeholder="Brief task description..." required></textarea>
                </div>

                <div class="mb-3">
                    <label for="dueDate" class="form-label">
                        <i class="bi bi-calendar-event me-1"></i> Due Date and Time
                    </label>
                    <input type="datetime-local" class="form-control" id="dueDate" name="due_date" required>
                </div>

                <div class="mb-3">
                    <label for="students" class="form-label">
                        <i class="bi bi-people-fill me-1"></i> Assign to Students
                    </label>
                    <select id="students" name="students[]" class="form-select" multiple required>
                        <!-- Dynamically populated options -->
                    </select>
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i> Hold Ctrl (Windows) or Command (Mac) to select multiple
                        students.
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Save Task
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>