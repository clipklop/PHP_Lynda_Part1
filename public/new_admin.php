<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation.php");

if (isset($_POST['submit'])) {
  # Validations
  $required_fields = array("username", "password");
  validate_presences($required_fields);

  $fields_with_max_lengths = array("username" => 30);
  validate_max_lengths($fields_with_max_lengths);

  if (empty($errors)) {
    # Process the form...
    # Escape all strings with user function
    $username = mysql_prep($_POST["username"]);
    $password = password_encrypt($_POST["password"]);

    # Perform a database query
    $query = "INSERT INTO admins (username, hashed_password)";
    $query .= " VALUES ('{$username}', '{$password}')";

    $result = mysqli_query($connection, $query);

    if ($result) {
      # Success
      $_SESSION["message"] = "The admin user has created successfully.";
      redirect_to("manage_admins.php");
    } else {
      # Failure
      $_SESSION["message"] = "The admin user creation failed.";
      redirect_to("new_admin.php");
    }
}
} else {
  # this is probably a GET request
} # end: if (isset($_POST['submit']))
?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main">
  <div id="navigation">
    &nbsp;
  </div>

  <div id="page">
  <?php echo message(); ?>
  <?php echo form_errors($errors); ?>
    <h2>Create Amin</h2>
    <form action="new_admin.php" method="post">
      <p>Username: <input type="text" name="username"></p>
      <p>Password: <input type="text" name="password"></p>
      <input type="submit" name="submit" value="Create Admin">
    </form>
    <br>
    <a href="manage_admins.php">Cancel</a>
  </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>