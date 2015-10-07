<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");

$current_subject = find_subject_by_id($_GET["subject"], false);
if (!$current_subject) {
  # Subject ID was missing or invalid or
  # Subject couldn't be found in database.
  redirect_to("manage_content.php");
}

$pages_set = find_pages_for_subject($current_subject["id"]);
if (mysqli_num_rows($pages_set) > 0) {
  $_SESSION["message"] = "Subject deletion failed. Delete pages first.";
  redirect_to("manage_content.php?subject={$current_subject['id']}");
}

$id = $current_subject["id"];
$query = "DELETE FROM subject WHERE id = {$id} LIMIT 1";
$result = mysqli_query($connection, $query);

if ($result && mysqli_affected_rows($connection) == 1) {
  # Success
  $_SESSION["message"] = "Subject deleted successfuly.";
  redirect_to("manage_content.php");
} else {
  # Failure
  $_SESSION["message"] = "Subject deletion failed.";
  redirect_to("manage_content.php?subject={$id}");
}