<?php
Utils::checkActivationCode($_GET['code']);
Utils::redirect(BASE_URL);