<!-- SUBMISSIONS MODAL -->
<div class="modal fade" id="submissionModal" tabindex="-1" role="dialog" aria-labelledby="submissionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="submissionModalLabel">
                    <i class="bi bi-clipboard-data me-2"></i> Task Submissions
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark text-center">
                        <tr>
                            <th><i class="bi bi-person-fill"></i> Student Name</th>
                            <th><i class="bi bi-check2-circle"></i> Status</th>
                            <th><i class="bi bi-file-earmark"></i> File</th>
                            <th><i class="bi bi-tools"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody id="submissionTableBody" class="text-center">
                        <!-- Dynamic content here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>