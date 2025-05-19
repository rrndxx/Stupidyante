<!-- MFA Verification Modal -->
<div class="modal fade" id="mfaModal" tabindex="-1" aria-labelledby="mfaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content form-box">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="mfaModalLabel">Two-Factor Authentication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>We’ve sent a 6-digit verification code to your email. Enter it below to continue:</p>
                <form id="mfaForm">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="mfaCode" name="mfaCode"
                            placeholder="Enter verification code" required maxlength="6">
                    </div>
                    <div class="message" id="mfaMessage"></div>
                    <button type="submit" class="btn w-100 mt-2">Verify</button>
                </form>
            </div>
        </div>
    </div>
</div>