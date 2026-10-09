<!-- Edit User Modal -->
<dialog id="editUserModal" class="modal not-prose">
    <div class="modal-box max-h-3/4 overflow-y-auto">
        <h3 class="font-bold text-lg mb-4">Edit User</h3>
        <form method="POST" action="" id="editUserForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4 mb-4">
                <legend class="fieldset-legend">Account Information</legend>
                
                <label class="label">Username *</label>
                <input type="text" name="username" id="edit_username" class="input input-bordered" required>
                
                <label class="label">Email *</label>
                <input type="email" name="email" id="edit_email" class="input input-bordered" required>
            </fieldset>
            
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4 mb-4">
                <legend class="fieldset-legend">Personal Information</legend>
                
                <label class="label">First Name *</label>
                <input type="text" name="first_name" id="edit_first_name" class="input input-bordered" required>
                
                <label class="label">Last Name *</label>
                <input type="text" name="last_name" id="edit_last_name" class="input input-bordered" required>
                
                <label class="label">Bio (Optional)</label>
                <textarea name="bio" id="edit_bio" class="textarea textarea-bordered" rows="3"></textarea>
            </fieldset>
            
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4 mb-4">
                <legend class="fieldset-legend">Settings</legend>
                
                <label class="label cursor-pointer">
                    <span class="label-text">Is Admin</span>
                    <input type="hidden" name="is_admin" value="0">
                    <input type="checkbox" name="is_admin" id="edit_is_admin" class="checkbox" value="1">
                </label>

                <label class="label cursor-pointer">
                    <span class="label-text">Hidden</span>
                    <input type="hidden" name="hidden" value="0">
                    <input type="checkbox" name="hidden" id="edit_hidden" class="checkbox" value="1">
                </label>
            </fieldset>
            
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4 mb-4">
                <legend class="fieldset-legend">Profile Media</legend>
                
                <label class="label">Profile Image (Optional)</label>
                <input type="file" name="image" class="file-input file-input-bordered" accept="image/*">
                <div class="label">
                    <span class="label-text-alt text-base-content/60">Maximum file size: @maxUploadSize</span>
                </div>
            </fieldset>
            
            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Update User</button>
                <button type="button" class="btn" onclick="document.getElementById('editUserModal').close()">Cancel</button>
            </div>
        </form>
    </div>
</dialog>

<script>
function editUser(userId) {
    // Fetch user data
    fetch(`/admin/users/${userId}/edit`)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('edit_username').value = data.username;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_first_name').value = data.first_name;
            document.getElementById('edit_last_name').value = data.last_name;
            document.getElementById('edit_bio').value = data.bio || '';
            document.getElementById('edit_is_admin').checked = data.is_admin;
            document.getElementById('edit_hidden').checked = data.hidden;
            
            // Update form action
            document.getElementById('editUserForm').action = `/admin/users/${userId}`;
            
            // Show modal
            document.getElementById('editUserModal').showModal();
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
            alert('Error loading user data');
        });
}
</script> 