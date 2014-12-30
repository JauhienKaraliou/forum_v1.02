<?php
Utils::checkActivationCode($_GET['code']);
header('Location: '.BASE_URL);