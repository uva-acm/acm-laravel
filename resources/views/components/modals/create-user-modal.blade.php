<!-- Create User Modal -->
<dialog id="createUserModal" class="modal not-prose">
    <div class="modal-box max-h-3/4 overflow-y-auto">
        <h3 class="font-bold text-lg mb-4">Create New User</h3>
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4 mb-4">
                <legend class="fieldset-legend">Account Information</legend>
                
                <label class="label">Username *</label>
                <input type="text" name="username" class="input input-bordered" required>
                
                <label class="label">Email *</label>
                <input type="email" name="email" class="input input-bordered" required>
                
                <label class="label">Password *</label>
                <input type="password" name="password" class="input input-bordered" required>
            </fieldset>
            
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4 mb-4">
                <legend class="fieldset-legend">Personal Information</legend>
                
                <label class="label">First Name *</label>
                <input type="text" name="first_name" class="input input-bordered" required>
                
                <label class="label">Last Name *</label>
                <input type="text" name="last_name" class="input input-bordered" required>
                
                <label class="label">Bio (Optional)</label>
                <textarea name="bio" class="textarea textarea-bordered" rows="3"></textarea>
            </fieldset>
            
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4 mb-4">
                <legend class="fieldset-legend">Settings</legend>
                
                <label class="label cursor-pointer">
                    <span class="label-text">Is Admin</span>
                    <input type="checkbox" name="is_admin" class="checkbox">
                </label>
                
                <label class="label cursor-pointer">
                    <span class="label-text">Hidden</span>
                    <input type="checkbox" name="hidden" class="checkbox">
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
                <button type="submit" class="btn btn-primary">Create User</button>
                <button type="button" class="btn" onclick="document.getElementById('createUserModal').close()">Cancel</button>
            </div>
        </form>
    </div>
</dialog> 