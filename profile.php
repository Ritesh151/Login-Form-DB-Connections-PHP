<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// get current user data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$error = "";
$success = "";

if (isset($_POST['update'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (empty($username) || empty($email)) {
        $error = "Username and email are required.";
    } else {

        // check if username or email already taken by someone else
        $check_query = "SELECT * FROM users WHERE (username = '$username' OR email = '$email') AND id != '$user_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username or email already taken.";
        } else {

            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {

                if (!password_verify($current_password, $user['password'])) {
                    $error = "Current password is incorrect.";
                } elseif ($new_password != $confirm_password) {
                    $error = "New passwords do not match.";
                } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/", $new_password)) {
                    $error = "Password must be at least 8 characters with 1 uppercase, 1 number, and 1 special character.";
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_query = "UPDATE users SET username = '$username', email = '$email', password = '$hashed_password' WHERE id = '$user_id'";
                    mysqli_query($conn, $update_query);
                    $_SESSION['username'] = $username;
                    $success = "Profile updated successfully.";
                }

            } else {
                $update_query = "UPDATE users SET username = '$username', email = '$email' WHERE id = '$user_id'";
                mysqli_query($conn, $update_query);
                $_SESSION['username'] = $username;
                $success = "Profile updated successfully.";
            }

        }

    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body p-4">
            <h2 class="text-center mb-4">Edit Profile</h2>

            <?php if ($error != ""): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success != ""): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo $user['username']; ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" class="form-control" required>
                </div>

                <hr>
                <p class="text-muted">Leave blank to keep current password.</p>

                <div class="mb-3">
                    <label>Current Password</label>
                    <div class="input-group">
                        <input type="password" name="current_password" id="current_password" class="form-control">
                        <button type="button" class="btn btn-outline-secondary" onclick="toggle('current_password', this)">👁</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label>New Password</label>
                    <div class="input-group">
                        <input type="password" name="new_password" id="new_password" class="form-control">
                        <button type="button" class="btn btn-outline-secondary" onclick="toggle('new_password', this)">👁</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Confirm New Password</label>
                    <div class="input-group">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                        <button type="button" class="btn btn-outline-secondary" onclick="toggle('confirm_password', this)">👁</button>
                    </div>
                </div>

                <button type="submit" name="update" class="btn btn-primary w-100">Update Profile</button>
                <p class="text-center mt-3"><a href="dashboard.php">Back to Dashboard</a></p>
            </form>
        </div>
    </div>
</div>

<script>
function toggle(id, btn) {
    var input = document.getElementById(id);
    if (input.type == "password") {
        input.type = "text";
        btn.innerHTML = "🙈";
    } else {
        input.type = "password";
        btn.innerHTML = "🫣";
    }
}
</script>
</body>
</html>
