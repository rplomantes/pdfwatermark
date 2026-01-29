<?php
namespace local_pdfwatermark;

defined('MOODLE_INTERNAL') || die();

use setasign\Fpdi\Fpdi;

class watermark {

    public static function apply($file, $user) {
        global $CFG;

        require_once($CFG->dirroot . '/local/pdfwatermark/vendor/autoload.php');

        $tmpin  = tempnam(sys_get_temp_dir(), 'pdfin');
        $tmpout = tempnam(sys_get_temp_dir(), 'pdfout');

        file_put_contents($tmpin, $file->get_content());

        $pdf = new Fpdi();
        $pagecount = $pdf->setSourceFile($tmpin);

        $format = get_config('local_pdfwatermark', 'watermarktext');
        $text = str_replace(
            ['{email}', '{fullname}'],
            [$user->email, fullname($user)],
            $format
        );

        for ($i = 1; $i <= $pagecount; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);

            $pdf->SetFont('Helvetica', 'B', 18);
            $pdf->SetTextColor(200, 200, 200);
            $pdf->SetXY(10, $size['height'] - 20);
            $pdf->Cell(0, 10, $text, 0, 0, 'C');
        }

        $pdf->Output($tmpout, 'F');

        return $tmpout;
    }
}
