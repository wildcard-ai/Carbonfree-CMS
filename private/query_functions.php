<?php

  // Admins

  function find_admin() {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $admin; // returns an assoc. array
  }

  function find_admin_by_username($username) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $admin; // returns an assoc. array
  }

  function validate_admin($admin, $options=[]) {

    $password_required = $options['password_required'] ?? true;

    if(is_blank($admin['username'])) {
      $errors[] = "Username cannot be blank.";
    } elseif (!has_length($admin['username'], array('min' => 5, 'max' => 255))) {
      $errors[] = "Username must be between 5 and 255 characters.";
    } elseif (!has_unique_username($admin['username'], $admin['id'] ?? 0)) {
      $errors[] = "Username not allowed. Try another.";
    }

    if($password_required) {
      if(is_blank($admin['password'])) {
        $errors[] = "Password cannot be blank.";
      } elseif (!has_length($admin['password'], array('min' => 8))) {
        $errors[] = "Password must contain 8 or more characters";
      } elseif (!preg_match('/[A-Z]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 uppercase letter";
      } elseif (!preg_match('/[a-z]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 lowercase letter";
      } elseif (!preg_match('/[0-9]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 number";
      } elseif (!preg_match('/[^A-Za-z0-9\s]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 symbol";
      }

      if(is_blank($admin['confirm_password'])) {
        $errors[] = "Confirm password cannot be blank.";
      } elseif ($admin['password'] !== $admin['confirm_password']) {
        $errors[] = "Password and confirm password must match.";
      }
    }

    return $errors;
  }

  // Projects

  function find_all_projects($options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM projects ";
    if($visible) {
      $sql .= "WHERE visible = true ";
    }
    $sql .= "ORDER BY id DESC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_project_by_id($id) {
    global $db;

    $sql = "SELECT * FROM projects ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $page = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $page; // returns an assoc. array
  }

  function delete_project($id) {
    global $db;

    $sql = "DELETE FROM projects ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function validate_project($project) {
    $errors = [];

    // project_name
    if(is_blank($project['project_name'])) {
      $errors[] = "Name cannot be blank.";
    }

    return $errors;
  }

  function insert_project($project) {
    global $db;

    $errors = validate_project($project);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO projects ";
    $sql .= "(project_name) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $project['project_name']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function update_project_by_id($db, $project) {
    $fields = array_filter($project, function($value, $key) {
      return in_array($key, ['project_name', 'description', 'url', 'client', 'project_type', 'cover_path', 'visible', 'layout']);
    }, ARRAY_FILTER_USE_BOTH);
    
    $sql = "UPDATE projects SET ";
  
    foreach($fields as $key => $value) {
      $sql .= "$key='" . db_escape($db, $value) . "' ";
    }
  
    $sql .= "WHERE id='" . db_escape($db, $project['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

    if($result) {
        return true;
    } else {
        $error = mysqli_error($db);
        return ["success" => false, "error" => $error];
    }
  }

  function find_images_by_project_id($project_id, $options=[]) {
    global $db;

    $is_draft = isset($options['is_draft']) ? $options['is_draft'] : null;
  
    $sql = "SELECT * FROM images ";
    $sql .= "WHERE project_id='" . db_escape($db, $project_id) . "' ";
    if($is_draft === null) {
      $sql .= "AND is_draft = false ";
    } else {
      $sql .= "AND (is_draft = true OR is_draft = false) ";
    }
    $sql .= "ORDER BY id DESC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_first_image_by_project_id($project_id) {
    global $db;

    $sql = "SELECT * FROM images ";
    $sql .= "WHERE project_id='" . db_escape($db, $project_id) . "' ";
    $sql .= "AND is_draft=false "; // Add condition to select non-draft images
    $sql .= "ORDER BY id ASC ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $image = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $image; // returns an assoc. array
  }

  function insert_image_by_project_id($db, $project_id, $upload_name, $draft_status) {
    $sql = "INSERT INTO images ";
    $sql .= "(project_id, path, is_draft) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $project_id) . "',";
    $sql .= "'" . db_escape($db, $upload_name) . "',";
    $sql .= "'" . db_escape($db, $draft_status) . "'";
    $sql .= ")";
    
    $result = mysqli_query($db, $sql);
  
    if ($result) {
      return true;
    } else {
      $error = mysqli_error($db);
      return $error;
    }
  }   

  function delete_images_by_id($db, $image_ids) {
    $ids = implode(',', $image_ids);
    $sql = "DELETE FROM images WHERE id IN ($ids)";
    $result = mysqli_query($db, $sql);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      $error = mysqli_error($db);
      return ["success" => false, "error" => $error];
    }
  }

  function update_images_by_id($db, $image) {
    $sql = "UPDATE images SET ";
    $sql .= "caption='" . db_escape($db, $image['caption']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $image['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

    if($result) {
        return true;
    } else {
        $error = mysqli_error($db);
        return ["success" => false, "error" => $error];
    }
  }

  function hasDraftImages() {
    global $db;

    $sql = "SELECT COUNT(*) FROM images WHERE is_draft = 1";

    $result = mysqli_query($db, $sql);

    if (mysqli_fetch_row($result)[0] > 0) {
      return true;
    } else {
      $error = mysqli_error($db);
      return $error;
    }
  }

  function deleteDraftImages() {
    global $db;
    $sql = "DELETE FROM images WHERE is_draft = 1";

    $result = mysqli_query($db, $sql);

    if ($result) {
      return true;
    } else {
      $error = mysqli_error($db);
      return $error;
    }
  }

  function getDisplayOptions() {
    global $db;
    $sql = "SELECT * FROM display_options";
    $result = mysqli_query($db, $sql);
    return mysqli_fetch_assoc($result);
  }

?>