<?php

$password = 'password';

$hash = password_hash($password, CRYPT_SHA256);

echo $hash;
