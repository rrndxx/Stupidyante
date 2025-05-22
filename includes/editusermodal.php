<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form id="editUserForm" class="p-4 bg-light rounded-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                            <select name="gender" class="form-select" required>
                                <option value="" disabled selected>Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" name="phone_number" class="form-control" placeholder="Phone Number"
                                required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Course</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                            <input type="text" name="course" class="form-control" placeholder="Course" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Birthdate</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            <input type="date" name="birthdate" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <textarea name="address" class="form-control" rows="2" placeholder="Address"
                                required></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Profile Picture</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-image"></i></span>
                            <input type="file" name="profile_path" class="form-control" accept="image/*">
                            <input type="hidden" name="profilepic">
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-dark w-100 mt-3">
                            <i class="bi bi-save me-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>