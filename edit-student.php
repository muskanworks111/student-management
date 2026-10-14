<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: index.php");
    exit();
}
require_once 'config/db.php';

$message = '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    header("Location: students.php");
    exit();
}

// Fetch existing student details
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: students.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roll_no = trim($_POST['roll_no']);
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $course  = trim($_POST['course']);
    $phone   = trim($_POST['phone']);

    if (!empty($roll_no) && !empty($name)) {
        try {
            $stmt = $pdo->prepare("UPDATE students SET roll_no = ?, name = ?, email = ?, course = ?, phone = ? WHERE id = ?");
            $stmt->execute([$roll_no, $name, $email, $course, $phone, $id]);
            header("Location: students.php");
            exit();
        } catch (\PDOException $e) {
            $message = "Error: Roll Number might already exist!";
        }
    } else {
        $message = "Roll Number and Name are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student - SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="dashboard.php">SMS Admin</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </nav>
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow p-4">
            <h3 class="mb-3">Edit Student Details</h3>
            <?php if(!empty($message)): ?>
                <div class="alert alert-danger py-2"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Roll Number</label>
                    <input type="text" name="roll_no" class="form-control" value="<?php echo htmlspecialchars($student['roll_no']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($student['course']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone']); ?>">
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Student</button>
                <a href="students.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>