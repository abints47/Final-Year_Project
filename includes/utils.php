<?php

/**
 * Checks if an email is technically valid and has a resolvable MX domain.
 * This is a "simple" real email verification that doesn't require sending an email.
 */
function is_email_valid($email)
{
    // 1. Basic syntax check
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // 2. Extract domain
    $domain = substr(strrchr($email, "@"), 1);

    // 3. Check for MX records (this verifies if the domain can receive mail)
    // Works on both Linux and Windows in modern PHP versions
    if (!checkdnsrr($domain, "MX")) {
        return false;
    }

    return true;
}
