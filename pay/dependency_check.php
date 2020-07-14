<?php

require_once dirname(__FILE__).'/conf.php';

if (!extension_loaded("openssl")) {
  exit("OpenSSL Extension Not Loaded! OpenSSL PHP Extension is required by ".strtoupper(_PAYMENT_PLATFORM)." PHP API Client to function properly.");
}

if (!extension_loaded("curl")) {
  exit("Curl Extension Not Loaded! Curl PHP Extension is required by ".strtoupper(_PAYMENT_PLATFORM)." PHP API Client to function properly.");
}