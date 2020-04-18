<?php
/* Pi-hole: A black hole for Internet advertisements
*  (c) 2017 Pi-hole, LLC (https://pi-hole.net)
*  Network-wide ad blocking via your own hardware.
*
*  This file is copyright under the latest version of the EUPL.
*  Please see LICENSE file for your rights under this license. */

function is_valid_domain_name($domain_name)
{
    return (preg_match("/^((-|_)*[a-z\d]((-|_)*[a-z\d])*(-|_)*)(\.(-|_)*([a-z\d]((-|_)*[a-z\d])*))*$/i", $domain_name) && // Valid chars check
        preg_match("/^.{1,253}$/", $domain_name) && // Overall length check
        preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)); // Length of each label
}

function get_ip_type($ip)
{
    return  filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? 4 :
           (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 6 :
            0);
}

function checkfile($filename) {
    if(is_readable($filename))
    {
        return $filename;
    }
    else
    {
        // substitute dummy file
        return "/dev/null";
    }
}

// Credit: http://php.net/manual/en/function.hash-equals.php#119576
if(!function_exists('hash_equals')) {
    function hash_equals($known_string, $user_string) {
        $ret = 0;

        if (strlen($known_string) !== strlen($user_string)) {
         $user_string = $known_string;
         $ret = 1;
        }

        $res = $known_string ^ $user_string;

        for ($i = strlen($res) - 1; $i >= 0; --$i) {
         $ret |= ord($res[$i]);
        }

        return !$ret;
   }
}

/**
 * More safely execute a command with pihole shell script.
 *
 * For example,
 *
 *   pihole_execute("-h");
 *
 * would execute command
 *
 *   sudo pihole -h
 *
 * and returns output of that command as a string.
 *
 * @param $argument_string String of arguments to run pihole with.
 * @param $error_on_failure If true, a warning is raised if command execution fails. Defaults to true.
 */
function pihole_execute($argument_string, $error_on_failure = true) {
    $escaped = escapeshellcmd($argument_string);
    $output = null;
    $return_status = -1;
    $command = "sudo pihole " . $escaped;
    exec($command, $output, $return_status);
    if($return_status !== 0)
    {
        trigger_error("Executing {$command} failed.", E_USER_WARNING);
    }
    return $output;
}

?>
