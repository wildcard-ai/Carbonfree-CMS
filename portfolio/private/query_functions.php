<?php

  // Admins

  function find_admin_by_id($id) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
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

  function update_admin($admin) {
    global $db;

    $password_sent = !is_blank($admin['password']);

    $errors = validate_admin($admin, ['password_required' => $password_sent]);
    if (!empty($errors)) {
      return $errors;
    }

    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

    $sql = "UPDATE admins SET ";
    if($password_sent) {
      $sql .= "hashed_password='" . db_escape($db, $hashed_password) . "', ";
    }
    $sql .= "username='" . db_escape($db, $admin['username']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $admin['id']) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);

    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  // Images

  // function find_all_media() {
  //   global $db;

  //   $sql = "SELECT * FROM media ";
  //   $sql .= "ORDER BY id ASC";
  //   $result = mysqli_query($db, $sql);
  //   return $result; // returns an assoc. array
  // }

  // function delete_media($id) {
  //   global $db;

  //   $old_media = find_media_by_id($id);
  //   $old_position = $old_media['position'];
  //   shift_media_positions($old_position, 0, $id, $old_media['project_id']);

  //   $sql = "DELETE FROM media ";
  //   $sql .= "WHERE id='" . $id . "' ";
  //   $sql .= "LIMIT 1";
  //   $result = mysqli_query($db, $sql);

  //   // For DELETE statements, $result is true/false
  //   if($result) {
  //     return true;
  //   } else {
  //     // DELETE failed
  //     echo mysqli_error($db);
  //     db_disconnect($db);
  //     exit;
  //   }

  // }

  function insert_project_image($project_id, $image_file) {
    global $db;
  
    $file_name = $_FILES['image_file']['name'];
    $file_path = 'images/' . $file_name;
  
    // Prepare and execute the query to insert image into the database
    $query = "INSERT INTO images (project_id, file_name, file_path, created_at) VALUES (:project_id, :file_name, :file_path, :created_at)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':file_name', $file_name);
    $stmt->bindParam(':file_path', $file_path);
    $created_at = date('Y-m-d H:i:s');
    $stmt->bindParam(':created_at', $created_at);
  
    // Upload the image file to the server
    move_uploaded_file($_FILES['image_file']['tmp_name'], WWW_ROOT . $file_path);
  
    // Execute the query and return the result
    $result = $stmt->execute();
    return $result;
  }
  

  // function upload_media($image) {
  //   global $db;
    
  //   $sql = "INSERT into media ";
  //   $sql .= "(project_id, file_name, position) ";
  //   $sql .= "VALUES (";
  //   $sql .= "'" . db_escape($db, $image["project_id"]) . "',";
  //   $sql .= "'" . db_escape($db, $image['file_name']) . "',";
  //   $sql .= "'" . db_escape($db, $image['position']) . "'";
  //   $sql .= ")";
  //   $result = mysqli_query($db, $sql);

  //   // For UPDATE statements, $result is true/false
  //   if($result) {
  //     return true;
  //   } else {
  //     // UPDATE failed
  //     echo mysqli_error($db);
  //     db_disconnect($db);
  //     exit;
  //   }
  // }

  // function update_media($media) {
  //   global $db;

  //   $sql = "UPDATE media SET ";
  //   $sql .= "position='" . db_escape($db, $media['position']) . "' ";
  //   $sql .= "WHERE id='" . db_escape($db, $media['id']) . "' ";
  //   $sql .= "LIMIT 1";

  //   $result = mysqli_query($db, $sql);
  //   // For UPDATE statements, $result is true/false
  //   if($result) {
  //     return true;
  //   } else {
  //     // UPDATE failed
  //     echo mysqli_error($db);
  //     db_disconnect($db);
  //     exit;
  //   }

  // }

  // function shift_media_positions($start_pos, $end_pos, $current_id=0, $project_id=0) {
  //   global $db;

  //   if($start_pos == $end_pos) { return; }

  //   $sql = "UPDATE media ";
  //   if($start_pos == 0) {
  //     // new item, +1 to items greater than $end_pos
  //     $sql .= "SET position = position + 1 ";
  //     $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
  //   } elseif($end_pos == 0) {
  //     // delete item, -1 from items greater than $start_pos
  //     $sql .= "SET position = position - 1 ";
  //     $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
  //   } elseif($start_pos < $end_pos) {
  //     // move later, -1 from items between (including $end_pos)
  //     $sql .= "SET position = position - 1 ";
  //     $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
  //     $sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
  //   } elseif($start_pos > $end_pos) {
  //     // move earlier, +1 to items between (including $end_pos)
  //     $sql .= "SET position = position + 1 ";
  //     $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
  //     $sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
  //   }
  //   // Exclude the current_id in the SQL WHERE clause
  //   $sql .= "AND id != '" . db_escape($db, $current_id) . "' ";
  //   $sql .= "AND project_id = '" . db_escape($db, $project_id) . "' ";

  //   $result = mysqli_query($db, $sql);
  //   // For UPDATE statements, $result is true/false
  //   if($result) {
  //     return true;
  //   } else {
  //     // UPDATE failed
  //     echo mysqli_error($db);
  //     db_disconnect($db);
  //     exit;
  //   }
  // }

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

  function old_find_all_projects($options=[]) {
    global $db;
  
    $sql = "SELECT * FROM projects ORDER BY id DESC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
  
    $projects = [];
    while ($project = mysqli_fetch_assoc($result)) {
      $projects[] = [
        'id' => $project['id'],
        'project_name' => $project['project_name'],
        // Add more project data as needed
      ];
    }
  
    return json_encode($projects);
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

  function find_images_by_project_id($project_id) {
    global $db;
  
    $sql = "SELECT * FROM images ";
    $sql .= "WHERE project_id='" . db_escape($db, $project_id) . "' ";
    $sql .= "ORDER BY id DESC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
    echo $result;
  }

  function find_first_image_by_project_id($project_id) {
    global $db;

    //$visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM images ";
    $sql .= "WHERE project_id='" . db_escape($db, $project_id) . "' ";
    $sql .= "ORDER BY id ASC ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $image = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $image; // returns an assoc. array
  }

  // function count_all_media() {
  //   global $db;

  //   //$visible = $options['visible'] ?? false;

  //   $sql = "SELECT COUNT(*) FROM media";
  //   $result = mysqli_query($db, $sql);
  //   confirm_result_set($result);
  //   $row = mysqli_fetch_row($result);
  //   mysqli_free_result($result);
  //   $count = $row[0];
  //   return $count;
  // }

  // function count_media_by_project_id($project_id) {
  //   global $db;

  //   //$visible = $options['visible'] ?? false;

  //   $sql = "SELECT COUNT(id) FROM media ";
  //   $sql .= "WHERE project_id='" . db_escape($db, $project_id) . "' ";
  //   //if($visible) {
  //   //  $sql .= "AND visible = true ";
  //   //}
  //   $sql .= "ORDER BY id ASC";
  //   $result = mysqli_query($db, $sql);
  //   confirm_result_set($result);
  //   $row = mysqli_fetch_row($result);
  //   mysqli_free_result($result);
  //   $count = $row[0];
  //   return $count;
  // }

  function insert_project($project) {
    global $db;

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

  function count_all_projects() {
    global $db;

    //$visible = $options['visible'] ?? false;

    $sql = "SELECT COUNT(*) FROM projects";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $row = mysqli_fetch_row($result);
    mysqli_free_result($result);
    $count = $row[0];
    return $count;
  }

  function delete_project($id) {
    global $db;

    $old_project = find_project_by_id($id);
    $old_position = $old_project['position'];
    shift_project_positions($old_position, 0, $id);

    $sql = "DELETE FROM projects ";
    $sql .= "WHERE id='" . $id . "' ";
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

  function shift_project_positions($start_pos, $end_pos, $current_id=0) {
    global $db;

    if($start_pos == $end_pos) { return; }

    $sql = "UPDATE projects ";
    if($start_pos == 0) {
      // new item, +1 to items greater than $end_pos
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
    } elseif($end_pos == 0) {
      // delete item, -1 from items greater than $start_pos
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
    } elseif($start_pos < $end_pos) {
      // move later, -1 from items between (including $end_pos)
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
      $sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
    } elseif($start_pos > $end_pos) {
      // move earlier, +1 to items between (including $end_pos)
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
      $sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
    }
    // Exclude the current_id in the SQL WHERE clause
    $sql .= "AND id != '" . db_escape($db, $current_id) . "' ";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  // function delete_media_by_project_id($id) {
  //   global $db;

  //   $sql = "DELETE FROM media ";
  //   $sql .= "WHERE project_id='" . $id . "'";
  //   $result = mysqli_query($db, $sql);

  //   // For DELETE statements, $result is true/false
  //   if($result) {
  //     return true;
  //   } else {
  //     // DELETE failed
  //     echo mysqli_error($db);
  //     db_disconnect($db);
  //     exit;
  //   }

  // }

  function rename_project($project) {
    global $db;

    // $errors = validate_subject($subject);
    // if(!empty($errors)) {
    //   return $errors;
    // }

    $sql = "UPDATE projects SET ";
    $sql .= "project_name='" . db_escape($db, $project['project_name']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $project['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

  function update_project_position($project) {
    global $db;

    $sql = "UPDATE projects SET ";
    $sql .= "position='" . db_escape($db, $project['position']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $project['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

  function update_project_visibility($project) {
    global $db;

    $sql = "UPDATE projects SET ";
    $sql .= "visible='" . db_escape($db, $project['visible']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $project['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

?>