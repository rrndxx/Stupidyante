<!-- SUBMIT TASK MODAL -->
<div class="modal fade" id="submitTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="submitTaskForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="bi bi-upload me-2"></i> Submit Task
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="task_id" id="submitTaskId">

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-file-earmark-arrow-up me-1"></i> Upload file (optional)
                    </label>
                    <input type="file" class="form-control" name="file" accept=".pdf,.doc,.docx,.txt,.jpg,.png,.zip">
                </div>

                <div class="text-center mb-2">— or —</div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-link-45deg me-1"></i> Submission URL
                    </label>
                    <input type="url" class="form-control" name="submission_url" placeholder="https://…">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-dark" type="submit">
                    <i class="bi bi-upload me-1"></i> Submit
                </button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>