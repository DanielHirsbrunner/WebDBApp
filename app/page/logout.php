<?php

unset($_SESSION["user"]);

header("Location: /login", true, 303);

die();
