<?php

unset($_SESSION["user"]);

App\Utils\FlashMessage::add(App\Utils\FlashMessage::TYPE_SUCCESS, "You were logged out.");
App\Utils\OtherUtils::redirect("/login");
