<?php

/** Add a session token that will be invalidated in 1 hour */
function add_session_token($conn, $tokenBase64, int $userId, $publicKey) {
  $tokenBase64S = $conn->real_escape_string($tokenBase64);
  $publicKeyS = $conn->real_escape_string($publicKey);
  return $conn->query("
    INSERT INTO auth_Tokens (token, timeout, user_id, public_key)
    VALUES ('$tokenBase64S', NOW() + INTERVAL 1 HOUR, $userId, '$publicKeyS')
  ");
}

/** Validate a session token. 
  * Returns false if the token has expired, or the token does not exist
  */
function validate_session_token($conn, $tokenBase64, int $userId): bool {
  $tokenBase64S = $conn->real_escape_string($tokenBase64);
  $result = $conn->query("
    SELECT timeout > NOW() FROM auth_Tokens
    WHERE user_id = $userId
    AND token = '$tokenBase64S'
  ");
  $total = 0;
  while ($row = $result->fetch_asso()) {
    $total += 1;
  }

  if ($total != 1) {
    error_log("Error [validate_session_token]: $total != 1 entries got");
    return false;
  }

  if ($total[0] == 1) {
    return true;
  } else {
    return false;
  }
}

/** get user id for user
 * @param $user: encrypted and base64 encoded
 */
function query_user_id($conn, $user) {
  $userS = $conn->real_escape_string($user);
	error_log("Querying user id for user $userS");
  return $conn->query("
    SELECT id FROM auth_Users
    WHERE user = '$userS'
  ");
}

function add_user($conn, $user, $pass) {
  $userS = $conn->real_escape_string($user);
  $passS = $conn->real_escape_string($pass);
  return $conn->query("
    INSERT INTO auth_Users (user, pass)
    VALUES ('$userS', '$passS')
  ");
}

# Query the latest valid token entry for the specified user
function query_token($conn, int $userid) {
	return $conn->query("
		SELECT * FROM auth_Tokens
		WHERE user_id = $userid
		AND timeout > now()
		ORDER BY timeout DESC
		LIMIT 1
	");
}

# Query user permissions
function query_permissions($conn, int $userid) {
	return $conn->query("
		SELECT permission_id FROM auth_Permissions
		WHERE user_id = $userid
	");
}

function query_permission_names($conn) {
	return $conn->query("
		SELECT * FROM auth_par_Permissions
	");
}

function query_has_permissions($conn, int $userid, array permissions): bool {
	$result = $conn->query("
		SELECT * FROM auth_Permissions
		WHERE user_id = $userid
	");
	if (!$result) {
		return false;
	}

	while ($row = $result->fetch_assoc) {
		$permission_id = $row["permission_id"];
		$current_has_perm = false;
		foreach ($permissions as $permission) {
			if ($permission_id == $permission) {
				$current_has_perm = true;
				break;
			}
		}
		if (!$current_has_perm) {
			return false;
		}
	}
	return true;
}

?>
