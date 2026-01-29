<?php
defined('MOODLE_INTERNAL') || die();

function local_pdfwatermark_pluginfile(
    $course,
    $cm,
    $context,
    $filearea,
    $args,
    $forcedownload,
    array $options = []
) {
    global $USER;

    // Only File activity
    if ($context->contextlevel !== CONTEXT_MODULE || $cm->modname !== 'resource') {
        return false;
    }

    $filename = end($args);

    // Only PDFs
    if (pathinfo($filename, PATHINFO_EXTENSION) !== 'pdf') {
        return false;
    }

    $fs = get_file_storage();
    $file = $fs->get_file(
        $context->id,
        'mod_resource',
        'content',
        0,
        '/',
        $filename
    );

    if (!$file) {
        return false;
    }

    require_once(__DIR__ . '/classes/watermark.php');

    $watermarked = local_pdfwatermark\watermark::apply(
        $file,
        $USER
    );

    send_file(
        $watermarked,
        $filename,
        0,
        0,
        true,
        false,
        'application/pdf'
    );
}
