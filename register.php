<?php
include 'config.php';

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } else {
        $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username or email already exists.";
        } elseif ($password != $confirm_password) {
            $error = "Passwords do not match.";
        } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/", $password)) {
            $error = "Password must be at least 8 characters with 1 uppercase, 1 number, and 1 special character.";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            $insert_result = mysqli_query($conn, $insert_query);

            if ($insert_result) {
                $success = "Registration successful. You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }

        }

    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 450px;">
    <div class="card shadow">
        <div class="card-body p-4">
            <h2 class="text-center mb-4">Register</h2>

            <?php if ($error != ""): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success != ""): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="toggle('password', this)">👁</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <div class="input-group">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="toggle('confirm_password', this)">👁</button>
                    </div>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
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
