<?php include_once("header.php"); ?>

<?php
    $statement = $pdo->prepare("SELECT * FROM users WHERE email=? AND token=?");
    $statement->execute([$_REQUEST['email'],$_REQUEST['token']]);
    $total = $statement->rowCount();
    if (!$total) {
        header('location:'.BASE_URL);
        exit;
    }

?>

<?php
if(isset($_POST["form1"])){
    try{
        
        if ($_POST["password"] == '' || $_POST["retype_password"] == '') {
            throw new Exception("Password can not be empty.");
        } elseif ($_POST["password"] != $_POST["retype_password"]) {
            throw new Exception("Password does not match.");

        }
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $statement = $pdo->prepare("UPDATE users SET token=?, password=? WHERE email=? AND token=?");
        $statement->execute(['', $password,$_REQUEST['email'], $_REQUEST['token']]);

        header('location:'.BASE_URL.'login.php?');
        echo $_REQUEST['email'];
        echo $_REQUEST['token'];


    }catch(Exception $e){
        $error_message = $e->getMessage();
    }
}

?>

<h2 class="mb_10">Reset Password</h2>
<?php
if(isset($error_message)){
    echo "<div>";
    echo $error_message;
    echo "</div>";
}

// if (!empty($success_message)) {
//     echo "<div class='success' >";
//     echo $success_message;
//     echo "</div>";
// }
?>

<form action="" method="post">
    <table class="t2">
        <tr>
            <td>New Password</td>
            <td><input type="password" name="password"  autocomplete="off"></td>
             
        <tr>
            <tr>
            <td>Retype Password</td>
            <td><input type="password" name="retype_password"  autocomplete="off"></td>
             
        <tr>
            <td></td>
            <td>
                <input type="submit" value="submit" name="form1">
            </td>
        </tr>
    </table>


</form>

<?php include_once("footer.php") ?>
