<?php
include 'db.php';
$session_uid = $D->uid;
include 'userUpdates.php';
$userUpdates = new userUpdates($db2);

if (isset($_POST['position']) && isset($session_uid)) {
    $position = $_POST['position'];
    $data = $userUpdates->userBackgroundPositionUpdate($session_uid, $position);
    if ($data)
        echo $position;
}
