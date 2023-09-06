<?php include_once("header.php"); ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<?php


$errors = []; 
$success_message = '';

if (isset($_POST['form1'])) {
    if (empty($_POST["firstname"])) {
        $errors[] = "First name can not be empty.";
    }else{
         if (!preg_match("/^[a-zA-Z-' ]*$/",$_POST["firstname"])) {
            $errors[] = "Only letters and white space allowed";
    }
    }


    if (empty($_POST["lastname"])) {
        $errors[] = "Last name can not be empty.";
    }else{
         if (!preg_match("/^[a-zA-Z-' ]*$/",$_POST["lastname"])) {
            $errors[] = "Only letters and white space allowed";
    }
    }


    if (empty($_POST["email"])) {
        $errors[] = "Email can not be empty.";
    } else {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email is invalid.";
        }
    }

    // Check email existance
    $statement = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $statement->execute([$_POST['email']]);
    $total = $statement->rowCount();
    if ($total) {
        $errors[] = "Email already exists.";
    }

    if (empty($_POST["phone"])) {
        $errors[] = "Phone can not be empty.";
    }
    if (empty($_POST["password"]) || empty($_POST["retype_password"])) {
        $errors[] = "Password can not be empty.";
    } elseif ($_POST["password"] != $_POST["retype_password"]) {
        $errors[] = "Passwords must match.";
    }

    if (empty($errors)) {
        

        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $token = time();

        // Insert user data 
        $q = $pdo->prepare("INSERT INTO users (firstname, lastname, email, phone, password, token, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($q->execute([$_POST["firstname"], $_POST["lastname"], $_POST["email"], $_POST["phone"], $password, $token, 0])) {

            
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
                $mail->Subject = "Registration verification email";
                $link = BASE_URL . 'registration-verify.php?email=' . $_POST["email"] . '&token=' . $token;
                $mail->Body = '<p>Please click on this link to verify your registration:</p>' .
                    '<p><a href="' . $link . '">Click here</a></p>';

                if ($mail->send()) {
                    $success_message = 'Registration is completed. An email has been sent to your email address. Please check your email and verify the registration.';
                } else {
                    $errors[] = "Message could not be sent. Mailer error: " . $mail->ErrorInfo;
                }
            } catch (Exception $e) {
                $errors[] = "Message could not be sent. Mailer error: " . $e->getMessage();
            }
        } else {
            $errors[] = "Database error. Registration failed.";
        }
    }
}
?>
<h2 class="mb_10">Registration</h2>
<?php
// if (!empty($errors)) {
//     echo "<div class='error' >";
//     foreach ($errors as $err) {
//         echo "<p>$err</p>";
//     }
//     echo "</div>";
//}

if (!empty($success_message)) {
    echo "<div class='success' >";
    echo $success_message;
    echo "</div>";
}
?>
<form action="" method="post">
    <table class="t2">
        <tr>
            <td>First Name</td>
            <td><input type="text" name="firstname" id="firstname" autocomplete="off"
                       value="<?php if (isset($_POST["firstname"])) {
                           echo $_POST["firstname"];
                       } ?>"></td>
        </tr>
        <?php if (!empty($errors) && in_array("First name can not be empty.", $errors)): ?>
            <tr>
                <td></td>
                <td><p class="error">First name can not be empty.</p></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td>Last Name</td>
            <td><input type="text" name="lastname" id="lastname" autocomplete="off"
                       value="<?php if (isset($_POST["lastname"])) {
                           echo $_POST["lastname"];
                       } ?>"></td>
        </tr>
        <?php if (!empty($errors) && in_array("Last name can not be empty.", $errors)): ?>
            <tr>
                <td></td>
                <td><p class="error">Last name can not be empty.</p></td>
            </tr>
        <?php endif; ?>
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
            <td>Phone</td>
            <td><input type="text" name="phone" id="phone" autocomplete="off"
                       value="<?php if (isset($_POST["phone"])) {
                           echo $_POST["phone"];
                       } ?>"></td>
        </tr>
        <?php if (!empty($errors) && in_array("Phone can not be empty.", $errors)): ?>
            <tr>
                <td></td>
                <td><p class="error">Phone can not be empty.</p></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td>Password</td>
            <td><input type="password" name="password" id="password" autocomplete="off"></td>
        </tr>
        <?php if (!empty($errors) && in_array("Password can not be empty.", $errors)): ?>
            <tr>
                <td></td>
                <td><p class="error">Password can not be empty.</p></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($errors) && in_array("Passwords must match.", $errors)): ?>
            <tr>
                <td></td>
                <td><p class="error">Passwords must match.</p></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td>Re-Type Password</td>
            <td><input type="password" name="retype_password" id="retype_password" autocomplete="off"></td>
        </tr>
        <?php if (!empty($errors) && in_array("Passwords must match.", $errors)): ?>
            <tr>
                <td></td>
                <td><p class="error">Passwords must match.</p></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Submit" name="form1" id="">
                <!-- <a href="forget-password.php" class="primary_color">Forget Password</a> -->
            </td>
        </tr>
    </table>
</form>

<?php include_once("footer.php") ?>
