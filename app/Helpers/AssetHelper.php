<?php

/**
 * AssetHelper
 *
 * @author Ghulam Mustafa <ghulam.mustafa@vservices.com>
 * @date   03/12/18
 */

/**
 * Return's admin assets directory
 *
 * CALLING PROCEDURE
 *
 * In controller call it like this:
 * $adminAssetsDirectory = adminAssetsDir() . $site_settings->site_logo;
 *
 * In View call it like this:
 * {{ asset(adminAssetsDir() . $site_settings->site_logo) }}
 *
 * @param string $role
 *
 * @return bool
 */

function backend_url($uri='/')
{
    return call_user_func_array( 'url', ['admin/' . ltrim($uri,'/')] + func_get_args() );
}

function backend_view($file)
{
    return call_user_func_array( 'view', ['admin/' . $file] + func_get_args() );
}

function backendUserFile()
{
    return 'backends/users/';
}

function backendUserUrl($file = '')
{
    return $file != '' && file_exists('backends/users/' . $file) ? url('backends/users') . '/' . $file : '';
}

function backendTrainingFile()
{
    return 'backends/training/';
}
function backendJoeyDocumentVerificationFile()
{
    return 'backends/training/';
}

function backendTrainingUrl($file = '')
{
    return $file != '' && file_exists('backends/training/' . $file) ? url('backends/training') . '/' . $file : '';
}



function constants($key)
{
    return config( 'constants.' . $key );
}

function adminHasAssets($image)
{
    if (!empty($image) && file_exists(uploadsDir() . $image)) {
        return true;
    } else {
        return false;
    }
}

function defaultStoreCoverUrl()
{
	return 'assets/front/images/store-cover.png';
}

function getFileExtension($filename, $offset = 3)
{
    return substr(strtolower($filename), strlen($filename)-$offset, $offset);
}
