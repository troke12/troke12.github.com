<?php
  $linkgan = $_POST['url'];
  $domain_data["fullName"] = "link.troke.id";
  $post_data["destination"] = "$linkgan";
  $post_data["domain"] = $domain_data;
  //$post_data["slashtag"] = "A_NEW_SLASHTAG";
  //$post_data["title"] = "Rebrandly YouTube channel";
  $ch = curl_init("https://api.rebrandly.com/v1/links");
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "apikey: 3ad044ffaadd4012adfebc66ed452db4",
      "Content-Type: application/json"
  ));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
  $result = curl_exec($ch);
  curl_close($ch);
  $response = json_decode($result, true);
  print "Short URL is: " . $response["shortUrl"];
?>