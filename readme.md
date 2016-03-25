YOURLS Plugin: Upload and Shorten
=================================

Plugin for [YOURLS](http://yourls.org) `1.7+`.

Description
-----------
Upload files to your server with API and create short-URLs to them. Now you can share your files using shortlinks as well as URL?s. 

Installation
------------
1. Navigate to the folder `./user/plugins/` inside your YOURLS-install directory

2. *Either* clone this repo using `git` *or*  
  create a new folder named ?api-upload-and-shorten? and  
  download all files from here and drop them into that directory. 

3. * open `./user/config.php` in your YOURLS-directory with any text editor
   * add two definitions at the end of that file:  
   `define( 'SHARE_URL', 'http://my.domain.tld/directory/' );`  
   `define( 'SHARE_DIR', '/full/path/to/httpd/directory/' );`  
   (both must point to the (existing) directory where your files should be uploaded and accessed from the web)
   * If necessary create a folder matching the name you defined in the above setting 
   * Depending on your webserver?s setup you may have to 'chmod +rw /full/path/to/httpd/directory' 

4. Go to the Plugins administration page (*eg* `http://sho.rt/admin/plugins.php`) and activate the plugin.

5. Have fun!

How to use
----------
This plugin to add additional command to the YOURLS API.

- upload_and_shorten - a function to upload file and create short-URL

need send file in POST with field name "file_upload"