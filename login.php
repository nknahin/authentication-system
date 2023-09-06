<?php include_once("header.php"); ?>



<?php

if(isset($_POST['form1'])){
    try{
        if($_POST['email'] == ''){
            throw new Exception("Email can not be empty");
        }else{
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                throw new Exception("Email is invalid.");
            }
        }
        if($_POST['password'] == ''){
            throw new Exception("Password can not be empty.");
        }
        $q = $pdo->prepare("SELECT * FROM users WHERE email=? AND status=?");
        $q->execute([$_POST["email"],1]);
        $total = $q->rowCount();
        if(!$total){
            throw new Exception("Email is not found.");
        }else{
            $result = $q->fetchALL(PDO::FETCH_ASSOC);
            foreach($result as $row){
                $password = $row['password'];
                if(!password_verify($_POST['password'], $password)){
                    throw new Exception("Password does not match.");
                }
            }
        }

        $_SESSION['user'] = $row;
        header('location:'.BASE_URL.'dashboard.php');
        
    


    }catch(Exception $e){
        $errors = $e->getMessage();
    }
}


?>






<h2 class="mb_10">Login</h2>
<?php
if(isset($errors)){
    echo "<div>";
    echo $errors;
    echo "</div>";
}

?>
<form action="" method="post">
    <table class="t2">
        <tr>
            <td>Email</td>
            <td><input type="text" name="email" id="" autocomplete="off"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" name="password" id="" autocomplete="off"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Submit" name="form1" id="">
                <a href="<?php echo BASE_URL;?>forget-password.php" class="primary_color">Forget Password</a>
            </td>
        </tr>
    </table>
</form>

<?php include_once("footer.php") ?>