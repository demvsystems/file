<?php

namespace DEMV\File\Test;

use Codeception\Specify;
use DEMV\File\Filename;
use PHPUnit\Framework\TestCase;

/**
 * Class FilenameTest
 *
 * @package Tests\Modules\Bipro\Backend\File
 */
final class FilenameTest extends TestCase
{
    use Specify;

    /**
     * Test für clean
     */
    public function testClean()
    {
        $testfilenames = [
            'test.pdf'       => 'test_pdf',
            'test'           => 'test',
            'test.pdf.pdf'   => 'test_pdf_pdf',
            'test_pdf.pdf'   => 'test_pdf_pdf',
            'test-pdf.pdf'   => 'test-pdf_pdf',
            'test-_-pdf.pdf' => 'test-pdf_pdf',
            'test-__pdf.pdf' => 'test-pdf_pdf',
            'test__pdf.pdf'  => 'test_pdf_pdf',
            'test pdf.pdf'   => 'test_pdf_pdf',
            'test - pdf.pdf' => 'test-pdf_pdf',
            'test _ pdf.pdf' => 'test_pdf_pdf',
            'testpdf_.pdf'   => 'testpdf_pdf',
            '_testpdf_.pdf'  => 'testpdf_pdf',
            '_testpdf.pdf'   => 'testpdf_pdf',
        ];

        foreach ($testfilenames as $beforeClean => $expected) {
            $this->assertEquals($expected, Filename::clean($beforeClean, []));
        }
    }

    /**
     * Test für limitLength
     */
    public function testLimitLength()
    {
        $name     = 'Dies_ist_eine_testdatei.pdf';
        $filename = new Filename($name);
        $filename->limitLength(8);
        $this->assertEquals('dies_ist.pdf', $filename->assemble());
    }

    /**
     * Test für getExtension
     */
    public function testGetExtension()
    {
        $name     = 'Dies_ist_eine_testdatei.pdf';
        $filename = new Filename($name);
        $this->assertEquals('pdf', $filename->getExtension());
    }

    /**
     * Test für hasExtension
     */
    public function testHasExtension()
    {
        $name     = 'Dies_ist_eine_testdatei.pdf';
        $filename = new Filename($name);
        $this->assertTrue($filename->hasExtension());

        $name     = 'Dies_ist_eine_testdatei';
        $filename = new Filename($name);
        $this->assertFalse($filename->hasExtension());
    }

    /**
     * Test für setExtension
     */
    public function testSetExtension()
    {
        $name     = 'Dies_ist_eine_testdatei';
        $filename = new Filename($name);
        $filename->setExtension('pdf');
        $this->assertEquals('pdf', $filename->getExtension());
        $this->assertEquals('dies_ist_eine_testdatei.pdf', $filename->assemble());

        $name     = 'Dies_ist_eine_testdatei';
        $filename = new Filename($name);
        $filename->setExtension('weirdext');
        $this->assertEquals(null, $filename->getExtension());
        $this->assertEquals('dies_ist_eine_testdatei', $filename->assemble());
    }

    /**
     * Test für getBasename
     */
    public function testGetBasename()
    {
        $name     = 'path/to/file/Dies_ist_eine_testdatei.pdf';
        $filename = new Filename($name);
        $filename->setExtension('pdf');
        $this->assertEquals('path_to_file_dies_ist_eine_testdatei.pdf', $filename->assemble());
    }

    /**
     * Test für isValid
     */
    public function testIsValid()
    {
        $name     = 'Dies_ist_eine_testdatei';
        $filename = new Filename($name);
        $this->assertFalse($filename->isValid());

        $name     = '';
        $filename = new Filename($name);
        $this->assertFalse($filename->isValid());

        $name     = 'Dies_ist_eine_testdatei.pdf';
        $filename = new Filename($name);
        $this->assertTrue($filename->isValid());
    }

    /**
     * Test für assemble
     */
    public function testAssemble()
    {
        $name     = 'Dies_ist_eine_testdatei';
        $filename = new Filename($name);
        $this->assertEquals('dies_ist_eine_testdatei', $filename->assemble());

        $name     = 'Dies_ist_eine_testdatei.pdf';
        $filename = new Filename($name);
        $this->assertEquals('dies_ist_eine_testdatei.pdf', $filename->assemble());

        $name     = 'Dies_ist_eine_testdatei';
        $filename = new Filename($name);
        $filename->setExtension('zip');
        $this->assertEquals('dies_ist_eine_testdatei.zip', $filename->assemble());

        $this->specify('Test für Filenames mit Sonderzeichen', function () {
            $names = [
                'testdatei Nr 01.pdf' => 'testdatei_nr_01.pdf',
                'testdätei.pdf'       => 'testdaetei.pdf',
                'testdötei.pdf'       => 'testdoetei.pdf',
                'teßtdatei.pdf'       => 'tesstdatei.pdf',
                'testdütei.pdf'       => 'testduetei.pdf',
                'Ätestdatei.pdf'      => 'aetestdatei.pdf',
                'Ötestdatei.pdf'      => 'oetestdatei.pdf',
                'Ütestdatei.pdf'      => 'uetestdatei.pdf',
            ];

            foreach ($names as $base => $expected) {
                $filename = new Filename($base);
                $this->assertEquals($expected, $filename->assemble());
            }
        });

        $this->specify('Test cases', function () {
            $names = [
                'Foo/bär.pdf'                                                                 => 'foo_baer.pdf',
                'Änderungsschneiderei.pdf'                                                    => 'aenderungsschneiderei.pdf',
                'Allgemeine Gartenarbeiten.pdf'                                               => 'allgemeine_gartenarbeiten.pdf',
                'Liebesbrief 654654654 Verliebte 5454654654.pdf'                              => 'liebesbrief_654654654_verliebte_5454654654.pdf',
                'Bärige Leidenschaft.pdf'                                                     => 'baerige_leidenschaft.pdf',
                'Bärige Leidenschaft.PDF'                                                     => 'baerige_leidenschaft.pdf',
                'ABC_215467_Traffic_Kultur_4-758-6554656465_65.23.1985_FB-F7UJ_987987987.pdf' => 'abc_215467_traffic_kultur_4-758-6554656465_65_23_1985_fb-f7uj_987987987.pdf',
                'elektr. Bitter Lemmon für Zitronen 465465654654.pdf'                         => 'elektr_bitter_lemmon_fuer_zitronen_465465654654.pdf',
                'Brief an Genosse Jack-Sparrow, BlackPearl 465654465.pdf'                     => 'brief_an_genosse_jack-sparrow_blackpearl_465654465.pdf',
                '12213_31123213_45655_nachtrag_eingangszoo_13132213_abc.pdf'                  => '12213_31123213_45655_nachtrag_eingangszoo_13132213_abc.pdf',
                '590ad75d527_warten-auf_godot_42________.pdf'                                 => '590ad75d527_warten-auf_godot_42.pdf',
                'Profi/Bäcker(1).pdf'                                                         => 'profi_baecker(1).pdf',
                'Fußball (HSV)'                                                               => 'fussball_(hsv)',
                'L_4563_1950-02-22T21-32-10441_4546566556_Profi-Apfel_______.csv'             => 'l_4563_1950-02-22t21-32-10441_4546566556_profi-apfel.csv',
                'Polzei/Feldarzt/Nachbarn.pdf'                                                => 'polzei_feldarzt_nachbarn.pdf',
                'Auftrag/Pfirsich * Bestätigung *.pdf'                                        => 'auftrag_pfirsich_bestaetigung.pdf',
                'Elimnierungsvorteile Batman + Robin.pdf'                                     => 'elimnierungsvorteile_batman_robin.pdf',
                'Profi-/Commander (HSV) Feldarzt 9877983546'                                  => 'profi_commander_(hsv)_feldarzt_9877983546',
                'Brief an 
                irgendwen.pdf'                                                => 'brief_an_irgendwen.pdf',
            ];

            foreach ($names as $base => $expected) {
                $filename = new Filename($base);
                $this->assertEquals($expected, $filename->assemble());
            }
        });
    }
}