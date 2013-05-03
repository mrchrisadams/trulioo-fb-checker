Facebook/Heroku/Trulioo sample app -- PHP
=================================

This is a sample app showing use of the Facebook API and Trulioo, written in PHP, designed for deployment to [Heroku](http://www.heroku.com/).

Running locally with Apache
---------------------------

Configure Apache with a `VirtualHost` that points to the location of this code checkout on your system.

[Create an app on Facebook](https://developers.facebook.com/apps) and set the Website URL to your local VirtualHost.

Copy the App ID and Secret from the Facebook app settings page into your `VirtualHost` config, something like:

    <VirtualHost *:80>
        DocumentRoot /path/to/this/script
        ServerName myapp.localhost
        SetEnv FACEBOOK_APP_ID 12345
        SetEnv FACEBOOK_SECRET abcde
        SetEnv TRULIOO_PROFILEPLUS_API_KEY abcde
    </VirtualHost>

Restart Apache, and you should be able to visit your app at its local URL.

Running locally with PHP 5.4, and Foreman
-----------------------------------------

Alternatively, if you have PHP 5.4 installed on your development box, you can run this app locally with Foreman.

Create a sample procfile called `Procfile.local`, that looks like this:

    web: php -S localhost:$PORT

Then run foreman like so:

    foreman start -f Procfile.local


Deploying to Heroku directly
-------------------------

If you prefer to deploy yourself, push this code to a new Heroku app on the Cedar stack, then copy the App ID and Secret into your config vars:

    heroku create --stack cedar
    git push heroku master
    heroku config:add FACEBOOK_APP_ID=12345 FACEBOOK_SECRET=abcde TRULIOO_PROFILEPLUS_API_KEY=abcde

Enter the URL for your Heroku app into the Website URL section of the Facebook app settings page, then you can visit your app on the web.

