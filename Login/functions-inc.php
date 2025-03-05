<?php

function emptyInputSignup($email, $username, $pwd, $pwdconfirm) {
    return empty($email) || empty($username) || empty($pwd) || empty($pwdconfirm);
}

function invalidUid($username) {
    return !preg_match("/^[a-zA-Z0-9]*$/", $username);
}

function invalidEmail($email) {
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

function pwdMatch($pwd, $pwdconfirm) {
    return $pwd !== $pwdconfirm;
}

function uidExists($conn, $username, $email) {
    // Updated table name to 'users' if that is the correct table
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../mainLogin/animlogin.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;  // Return user data if found
    } else {
        return false; // No user found
    }

    mysqli_stmt_close($stmt);
}

function createUser($conn, $email, $username, $pwd) {
    // Ensure table and column names match your database
    $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../mainLogin/animlogin.php?error=stmtfailed");
        exit();
    }

    $hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "sss", $email, $username, $hashedpwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../mainLogin/animlogin.php?error=none");
    exit();
}

function emptyInputLogin($username, $pwd) {
    return empty($username) || empty($pwd);
}

function loginUser($conn, $username, $pwd) {
    $uidExists = uidExists($conn, $username, $username);

    if ($uidExists === false) {
        header("location: ../mainLogin/animlogin.php?error=InvalidCredentials");
        exit();
    }

    $pwdHashed = $uidExists["password"];  // Updated column name
    $checkpwd = password_verify($pwd, $pwdHashed);

    if (!$checkpwd) {
        header("location: ../mainLogin/animlogin.php?error=wrongpassword");
        exit();
    } else {
        session_start();
        $_SESSION["userid"] = $uidExists["user_id"];  // Adjusted based on assumed database structure
        $_SESSION["username"] = $uidExists["username"];
        header("location: ../dashboard.php");  // Redirect to dashboard or homepage
        exit();
    }
}
