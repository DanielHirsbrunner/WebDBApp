<?php

unset($_SESSION["user"]);

App\Utils::redirect("/login");
