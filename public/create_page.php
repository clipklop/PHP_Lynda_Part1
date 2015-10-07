<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation.php");

find_selected_page();

# Can't add a new page unless we have a subject as a parent!
if (!$current_subject) {
  # Subject ID was missing or invlaid
  # Subject couldn't be found in database
  redirect_to("manage_content.php");
}

if (isset($_POST['submit'])) {
  # Validations
  $required_fields = array("menu_name", "position", "visible", "content");
  validate_presences($required_fields);

  $fields_with_max_lengths = array("menu_name" => 30);
  validate_max_lengths($fields_with_max_lengths);

  if (!empty($errors)) {
    # Process the form...
    $subject_id = $current_subject["id"];
    $menu_name = $_POST["menu_name"];
    $position = (int) $_POST["position"];
    $visible = (int) $_POST["visible"];

    # Escape all strings with user function
    $content = mysql_prep($_POST["content"]);

    # Perform a database query
    $query = "INSERT INTO pages (subject_id, menu_name, position, visible, content)";
    $query .= " VALUES ({$subject_id}, '{$menu_name}', {$position}, {$visible}, '{$content}')";

    $result = mysqli_query($connection, $query);

    if ($result) {
      # Success
      $_SESSION["message"] = "Page created successfully.";
      redirect_to("manage_content.php");
    } else {
      # Failure
      $_SESSION["message"] = "Page creation failed.";
      redirect_to("new_page.php");
    }

} else {
  # this is probably a GET request
  redirect_to("new_page.php");
}

# close mysql connection
if (isset($connection)) {
  mysqli_close($connection);
}