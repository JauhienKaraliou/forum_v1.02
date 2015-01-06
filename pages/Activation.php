<?php
if (isset($_GET['code'])) {
    Utils::checkActivationCode($_GET['code']);
    Utils::redirect(BASE_URL);
}