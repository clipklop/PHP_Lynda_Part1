<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation.php");

if (isset($_POST['submit'])) {
  # Process the form...
  $menu_name = $_POST["menu_name"];
  $position = (int) $_POST["position"];
  $visible = (int) $_POST["visible"];

  # Escape all strings with user function
  $menu_name = mysql_prep($menu_name);

  # Validations
  $required_fields = array("menu_name", "position", "visible");
  validate_presences($required_fields);

  $fields_with_max_lengths = array("menu_name" => 30);
  validate_max_lengths($fields_with_max_lengths);

  if (!empty($errors)) {
    $_SESSION["errors"] = $errors;
    redirect_to("new_subject.php");
  }

  # Perform a database query
  $query = "INSERT INTO subject (menu_name, position, visible)";
  $query .= " VALUES ('{$menu_name}', {$position}, {$visible})";

  $result = mysqli_query($connection, $query);

  if ($result) {
    # Success
    $_SESSION["message"] = "Subject created successfully.";
    redirect_to("manage_content.php");
  } else {
    # Failure
    $_SESSION["message"] = "Subject creation failed.";
    redirect_to("new_subject.php");
  }

} else {
  # this is probably a GET request
  redirect_to("new_subject.php");
}

# close mysql connection
if (isset($connection)) {
  mysqli_close($connection);
}