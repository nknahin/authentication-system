<?php include_once("header.php"); ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<?php

$errors= [];
if(isset($_POST['form1'])){
        if(empty($_POST['email'])){
            $errors[] = "Email can not be empty.";
        }else{
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                    $errors[] = "Email is invalid.";
            }
        }
        $q = $pdo->prepare("SELECT * FROM users WHERE email=? AND status=?");
        $q->execute([$_POST["email"],1]);
        $total = $q->rowCount();
        if(!$total){
            $errors[] = "Email is not found.";

        }

        $token = time();
        $statement = $pdo->prepare("UPDATE users SET token=? WHERE email=?");
        $statement->execute([$token,$_POST['email']]);
        $total = $statement->rowCount();
        if(!$total){
            $errors[] = "Email is not found.";
        }

            require 'vendor/autoload.php';
            $mail = new PHPMailer(true);
            try {
                
                $mail->isSMTP();
                $mail->Host = "smtp.mailtrap.io";
                $mail->SMTPAuth = true;
                $mail->Username = '101ca9061ab9f4';
                $mail->Password = '1293f04ba00efe';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 2525;

                $mail->setFrom('contact@example.com');
                $mail->addAddress($_POST["email"]);
                $mail->addReplyTo('contact@example.com');
                $mail->isHTML(true);
                $mail->Subject = "Reset password.";
                $link = BASE_URL . 'reset-password.php?email=' . $_POST["email"] . '&token=' . $token;
                $mail->Body = '<p>Please click on this link to reset your password":</p>' .
                    '<p><a href="' . $link . '">Click here</a></p>';

                if ($mail->send()) {
                    $success_message = "An email is send to your email to reset the password. Please check your email and follow the steps.";                
                } else {
                    $errors[] = "Message could not be sent. Mailer error: " . $mail->ErrorInfo;
                }
            } catch (Exception $e) {
                $errors[] = "Message could not be sent. Mailer error: " . $e->getMessage();
            }






}
    
?>

<h2 class="mb_10">Forget Password</h2>
<?php
// if(isset($errors[])){
//     echo "<div>";
//     echo $errors[];
//     echo "</div>";
// }

if (!empty($success_message)) {
    echo "<div class='success' >";
    echo $success_message;
    echo "</div>";
}
?>

<form action="" method="post">
    <table class="t2">
        <tr>
            <td>Email</td>
            <td><input type="text" name="email" id="email" autocomplete="off"
                       value="<?php if (isset($_POST["email"])) {
                           echo $_POST["email"];
                       } ?>"></td>
        </tr>
        <?php if (!empty($errors) && in_array("Email can not be empty.", $errors)): ?>
            <tr>
                <td></td>
                <td><p class="error">Email can not be empty.</p></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($errors) && in_array("Email is invalid.", $errors)): ?>
            <tr>
                <td></td>
                <td><p class="error">Email is invalid.</p></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="submit" name="form1">
                <a href="<?php echo BASE_URL; ?>login.php" class="primary_color">Back to Login Page</a>
            </td>
        </tr>
    </table>


</form>

<?php include_once("footer.php") ?>
