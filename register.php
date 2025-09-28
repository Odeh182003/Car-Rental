
<!DOCTYPE html>
<html>
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signin.css">
    <title>Register</title>
</head>
<body>
    <form method="POST" action="register.php">
        <div align="center">
            <h3>Register a new user</h3>
            <table border="1" width="300" cellpadding="2">
                <tr>
                    <th width="50%" align="right">Username</th>
                    <td width="50%">
                        <input type="text" name="username" required size="25"/>
                    </td>
                </tr>
                <tr>
                    <th width="50%" align="right">Password</th>
                    <td width="50%">
                        <input type="password" name="password" required size="25"/>
                    </td>
                </tr>
                <tr>
                    <th width="50%" align="right">User Type</th>
                    <td width="50%">
                        <select name="userposition" required>
                            <option value="Customer">Customer</option>
                            <option value="Manager">Manager</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="Register" name="Submit"/>
                    </td>
                </tr>
            </table>
        </div>
    </form>
    <?php
session_start();
include "db.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userposition = $_POST['userposition'];

    // Validate input
    if (empty($username) || empty($password) || empty($userposition)) {
        echo "All fields are required.";
        exit;
    }

    try {
        $pdo = db_connect();
        if (!$pdo) {
            throw new Exception("Database connection failed.");
        }

        // Check if the username already exists
        $checkQuery = "SELECT COUNT(*) FROM user_tablename WHERE username = :username";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->execute([':username' => $username]);
        if ($checkStmt->fetchColumn() > 0) {
            echo "Username already exists. Please choose another username.";
            exit;
        }

        // Insert the new user
        $insertQuery = "INSERT INTO user_tablename (username, userpassword, userposition) VALUES (:username, :userpassword, :userposition)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->execute([
            ':username' => $username,
            ':userpassword' => $password,  // In a real application, hash the password
            ':userposition' => $userposition
        ]);

        echo "Registration successful. You can now <a href='login.php'>login</a>.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} 
?>
</body>
</html>
