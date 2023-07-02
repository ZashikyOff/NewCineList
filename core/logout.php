<?php
session_name("cinelist");
session_start();
session_destroy();
header("Location: ../login.php");