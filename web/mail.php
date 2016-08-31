<?php
$sendmail = "From: test@nkotb-cycling.com\r\n";
mail(
  'ragsnas@gmail.com', // recipient email address
  'Email Subject', // email subject
  'Email Body', // email body
  $sendmail . "\r\n"// additional headers
);
