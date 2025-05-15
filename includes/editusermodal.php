<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editUserModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="mx-auto">
                <form id="editUserForm" class="border p-4 rounded shadow-sm bg-light">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="first_name" class="form-control" placeholder="First Name">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="last_name" class="form-control" placeholder="Last Name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                <select name="gender" class="form-select">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone_number" class="form-control" placeholder="Phone Number">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Course</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                <input type="text" name="course" class="form-control" placeholder="Course">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Birthdate</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="date" name="birthdate" class="form-control">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="address" class="form-control" rows="2" placeholder="Address"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Profile Picture</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input type="file" name="profile_path" class="form-control">
                                <input type="hidden" name="profilepic">
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-dark w-100 mt-2">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>