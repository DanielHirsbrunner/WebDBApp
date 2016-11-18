<?php

unset($_SESSION["user"]);

App\Utils\OtherUtils::redirect("/login");
