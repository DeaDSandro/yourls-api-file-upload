<?php
/*
Plugin Name: Upload & Shorten
Plugin URI: https://github.com/fredl99/YOURLS-Upload-and-Shorten
Description: Upload a file with API and create a short-YOURL for it in one step.

Based on: "Upload & Shorten" by fredl99
Forked from: https://github.com/fredl99/YOURLS-Upload-and-Shorten
Version: 1.2
Author: DeaDSandro
Author URI: https://github.com/DeaDSandro
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

yourls_add_filter( 'api_action_upload_and_shorten', 'api_upload_and_shorten_save_files' );

// Update option in database
function api_upload_and_shorten_save_files() {
    if (!isset($_FILES['file_upload']) || $_FILES['file_upload']['error'] == UPLOAD_ERR_NO_FILE) {
        return array(
            'statusCode' => 400,
            'simple '    => 'Error: file not found',
            'message'    => 'error: file not found',
        );
    }

    $my_url = SHARE_URL;
    $my_uploaddir = SHARE_DIR;
    // has to be defined in user/config.php like this:
    // define( 'SHARE_URL', 'http://my.domain.tld/directory/' );
    // define( 'SHARE_DIR', '/full/path/to/httpd/directory/' );

    $my_extension = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);
    $my_filename = pathinfo($_FILES['file_upload']['name'], PATHINFO_FILENAME);

    if (isset($_POST['randomize_filename']))
    {
        // make up a random name for the uploaded file
        // see http://www.mattytemple.com/projects/yourls-share-files/?replytocom=26686#respond
        $my_safe_filename = substr(md5($my_filename.strtotime("now")), 0, 12);
        // end randomize filename
    }
    else
    {
        // original code:
        $my_filename_trim = trim($my_filename);
        $my_RemoveChars  = array( "([\40])" , "([^a-zA-Z0-9-])", "(-{2,})" );
        $my_ReplaceWith = array("-", "", "-");
        $my_safe_filename = preg_replace($my_RemoveChars, $my_ReplaceWith, $my_filename_trim);
        // end original code
    }

    // avoid duplicate filenames
    $my_count = 2;
    $my_path = $my_uploaddir.$my_safe_filename.'.'.$my_extension;
    $my_final_file_name = $my_safe_filename.'.'.$my_extension;
    while (file_exists($my_path))
    {
        $my_path = $my_uploaddir.$my_safe_filename.'-'.$my_count.'.'.$my_extension;
        $my_final_file_name = $my_safe_filename.'-'.$my_count.'.'.$my_extension;
        $my_count++;
    }

    // move the file from /tmp/ to destination and initiate link creation
    if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $my_path))
    {
        $my_custom_keyword = NULL;
        if (isset($_POST['custom_keyword']) && $_POST['custom_keyword'] != '')
        {
            $my_custom_keyword = $_POST['custom_keyword'];
        }

        $my_short_url = yourls_add_new_link($my_url.$my_final_file_name, $my_custom_keyword, $my_final_file_name);
        return array(
            'statusCode' => 200,
            'simple'     => $my_short_url['shorturl'],
            'message'    => 'success: file uploaded',
        );
    }
    else
    {
        return array(
            'statusCode' => 500,
            'simple'     => "Upload failed! Something went wrong, sorry!",
            'message'    => 'error: upload failed',
        );
    }
}
