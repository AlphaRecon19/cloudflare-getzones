cloudflare-getzones
===================

Get all zone information for every domain in your CloudFlare account using curl and php

Required Prerequisites
===================
* A webserver running php
* CloudFlare account
* CloudFlare API key
* php-curl

If you are getting the error 'cURL not installed or available' and are running a Ubuntu server, all you need to do is install curl for php. Centos should already have curl compiled with php by default

    sudo apt-get install php5-curl
 
You will need to restart the webserver afterwards:

    sudo service apache2 restart

Installation
===================

    git clone git@github.com:AlphaRecon19/cloudflare-getzones.git

Now that you have cloudflare-getzones you will need to edit 2 variables in index.php `$cloudflare_tkn` and `$$cloudflare_email` with your information from https://www.cloudflare.com/my-account page.

Once you have made you changes above everything should just work. There will be a dropdown with all of you domains in your account.

Everything after line 98 is not required for this to function, it is just here to show the you data that has been received and processed from CloudFlare and can be deleted

Usage
===================

All the data from CloudFlare is store in the array $zones and can be used like this

    <?php
    echo $zones['example.com']['zone_id'];
    ?>

The above code would show the zone_id for example.com providing that example.com is in your account.

Error Handling
===================
Please refer to the CloudFlare Client API Documentation located at https://www.cloudflare.com/docs/client-api.html if you get a error.
