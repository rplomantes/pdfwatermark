<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage(
        'local_pdfwatermark',
        'PDF Watermark Settings'
    );

    $settings->add(new admin_setting_configtext(
        'local_pdfwatermark/watermarktext',
        'Watermark format',
        'You may use placeholders like {email}, {fullname}',
        'Downloaded by {email}'
    ));

    $ADMIN->add('localplugins', $settings);
}
