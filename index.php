
<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!------------ Include Header File Here -------------->

<?php include_once "include/header.php"; ?>


<!------------ Session Messages Code Here ------------>

<?php if (!empty($_SESSION['success'])): ?>

    <div class="alert alert-success text-center">
        <?=htmlspecialchars($_SESSION['success'])?>
    </div>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>

<div class="alert alert-danger text-center">
    <?=htmlspecialchars($_SESSION['error'])?>
</div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!------------ Session Messages Code  End Here ------------>

<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-10 col-md-12">

            <!-- User Card -->
            <div class="card user-card shadow">

                <!-- Header -->
                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h4 class="mb-1">Users</h4>
                            <small class="text-muted">
                                Manage all registered users
                            </small>
                        </div>
                        <!-------------------- ADD USER BUTTON HERE ---------------- -->
                        <button type="button" class="btn btn-primary btn-add" data-toggle="modal"
                            data-target="#addUserModal">
                            + Add User
                        </button>

                    </div>

                </div>
                <?php include_once "users/get-users.php"; ?>
                <!-- Body -->
                <div class="card-body">
                    <table id="userTable" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Bank</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $user): ?>
                            <tr>
                                <td>
                                    <img src="uploads/users/<?=htmlspecialchars($user['user_image'])?>" width="60" height="60"
                                        style="object-fit: cover; border-radius: 50%;">
                                </td>

                                <td>
                                    <?=htmlspecialchars($user['user_name'])?>
                                </td>

                                <td>
                                    <?=htmlspecialchars($user['user_email'])?>
                                </td>

                                <td>
                                    <?=htmlspecialchars($user['user_gender'])?>
                                </td>

                                <td>
                                    <?=htmlspecialchars($user['user_banks'])?>
                                </td>

                                <td>
                                    <div class="action-buttons">
            
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#editModal<?=$user['user_id']?>">
                                            Edit
                                        </button>

                                        <a href="users/delete-users.php?id=<?=$user['user_id']?>" class="btn btn-danger"
                                            onclick="return confirm('Are you sure?')">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Bank</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!------------------------------------ ADD USER MODAL HTML CODE ------------------------->

<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">
                    Add User
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="users/insert-users.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars(
                        $_SESSION['csrf_token'],
                        ENT_QUOTES,
                        'UTF-8'
                    )?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" maxlength="100" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" maxlength="255" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" minlength="5" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Upload Image</label>
                            <input type="file" name="imageUpload" class="form-control"
                                accept="image/jpeg,image/png,image/webp">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Gender</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" value="Male" required>
                                    <label class="form-check-label">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" value="Female">
                                    <label class="form-check-label">
                                        Female
                                    </label>

                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" value="Other">
                                    <label class="form-check-label">
                                        Other
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Bank Applied for</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bank[]"
                                    value="Bank of Baroda">
                                    <label class="form-check-label">
                                        Bank of Baroda
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bank[]"
                                        value="Punjab National Bank">

                                    <label class="form-check-label">
                                        Punjab National Bank
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bank[]" value="UCO bank">

                                    <label class="form-check-label">
                                        UCO Bank
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bank[]" value="Canara Bank">

                                    <label class="form-check-label">
                                        Canara Bank
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bank[]" value="SBI">

                                    <label class="form-check-label">
                                        SBI
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!------------------------------------ EDIT MODAL HTML CODE HERE --------------------------->
<?php foreach ($data as $user): ?>

<?php
    $selectedBanks = !empty($user['user_banks'])
    ? array_map('trim', explode(',', $user['user_banks']))
    : [];
?>
<div class="modal fade" id="editModal<?=$user['user_id']?>" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel<?=$user['user_id']?>" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel<?=$user['user_id']?>">
                    Edit User
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="users/update-users.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                <!-- User ID -->
                    <input type="hidden" name="user_id" value="<?=htmlspecialchars($user['user_id'])?>">
                    <!-- Name + Email -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input type="text" class="form-control" name="user_name"
                                value="<?=htmlspecialchars($user['user_name'])?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" class="form-control" name="user_email"
                                value="<?=htmlspecialchars($user['user_email'])?>" required>
                        </div>
                    </div>
                    <!-- Image + Banks -->
                    <div class="form-row">
                        <!-- Image -->
                        <div class="form-group col-md-6">
                            <label>Upload Image</label>
                            <input type="file" class="form-control" name="imageUpload"
                                id="imageUpload<?=$user['user_id']?>" accept="image/jpeg,image/png,image/webp">
                            <div class="mt-2">
                                <img id="previewImage<?=$user['user_id']?>"
                                    src="uploads/users/<?=htmlspecialchars($user['user_image'])?>"
                                    alt="User image" class="img-thumbnail"
                                    style="width:120px;height:120px;object-fit:cover;">
                            </div>
                        </div>
                        <!-- Banks -->
                        <div class="form-group col-md-6">
                            <label>
                                <strong>Banks</strong>
                            </label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="user_banks[]"
                                    value="Bank of Baroda" id="bank1_<?=$user['user_id']?>"
                                    <?=in_array('Bank of Baroda', $selectedBanks) ? 'checked' : ''?>>

                                <label class="form-check-label" for="bank1_<?=$user['user_id']?>">
                                    Bank of Baroda
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="user_banks[]"
                                    value="Punjab National Bank" id="bank2_<?=$user['user_id']?>"
                                    <?=in_array('Punjab National Bank', $selectedBanks) ? 'checked' : ''?>>
                                <label class="form-check-label" for="bank2_<?=$user['user_id']?>">
                                    Punjab National Bank
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="user_banks[]"
                                    value="UCO bank" id="bank3_<?=$user['user_id']?>"
                                    <?=in_array('UCO bank', $selectedBanks) ? 'checked' : ''?>>

                                <label class="form-check-label" for="bank3_<?=$user['user_id']?>">
                                    UCO Bank
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="user_banks[]"
                                    value="Canara Bank" id="bank4_<?=$user['user_id']?>"
                                    <?=in_array('Canara Bank', $selectedBanks) ? 'checked' : ''?>>

                                <label class="form-check-label" for="bank4_<?=$user['user_id']?>">
                                    Canara Bank
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="user_banks[]" value="SBI"
                                    id="bank5_<?=$user['user_id']?>"
                                    <?=in_array('SBI', $selectedBanks) ? 'checked' : ''?>>

                                <label class="form-check-label" for="bank5_<?=$user['user_id']?>">
                                    SBI
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Gender -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Gender</label>
                            <select class="form-control" name="user_gender">
                                <option value="">
                                    Select Gender
                                </option>

                                <option value="Male" <?=$user['user_gender'] == 'Male' ? 'selected' : ''?>>
                                    Male
                                </option>

                                <option value="Female" <?=$user['user_gender'] == 'Female' ? 'selected' : ''?>>
                                    Female
                                </option>

                                <option value="Other" <?=$user['user_gender'] == 'Other' ? 'selected' : ''?>>
                                    Other
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php include_once "include/footer.php"; ?>