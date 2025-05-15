<!-- SUBMIT TASK MODAL -->
<div class="modal fade" id="submitTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="submitTaskForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Submit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="task_id" id="submitTaskId">

                <div class="mb-3">
                    <label class="form-label">Upload file (optional)</label>
                    <input type="file" class="form-control" name="file">
                </div>

                <div class="text-center mb-2">— or —</div>

                <div class="mb-3">
                    <label class="form-label">Submission URL</label>
                    <input type="url" class="form-control" name="submission_url" placeholder="https://…">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-dark" type="submit"><i class="bi bi-upload"></i> Submit</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>