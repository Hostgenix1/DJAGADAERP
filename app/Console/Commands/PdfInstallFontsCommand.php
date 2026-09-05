<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PdfInstallFontsCommand extends Command
{
    protected $signature = 'pdf:install-fonts';

    protected $description = 'Copy Lato TTFs into the dompdf font dir and register them (Lato Medium as normal, Bold as bold, Italic as italic)';

    public function handle(): int
    {
        $fontDir = config('dompdf.font_dir', storage_path('fonts'));
        $cacheDir = config('dompdf.font_cache', $fontDir);

        if (!is_dir($fontDir)) {
            mkdir($fontDir, 0775, true);
        }

        $sourceDir = resource_path('fonts');

        $faces = [
            // BHANTAL reference: normal body text is Lato Medium, bold labels/headings are Lato Bold.
            ['family' => 'Lato', 'style' => 'normal', 'weight' => 400, 'file' => 'Lato-Medium.ttf'],
            ['family' => 'Lato', 'style' => 'bold', 'weight' => 700, 'file' => 'Lato-Bold.ttf'],
            ['family' => 'Lato', 'style' => 'italic', 'weight' => 400, 'file' => 'Lato-Italic.ttf'],
            ['family' => 'Lato', 'style' => 'italic', 'weight' => 700, 'file' => 'Lato-BoldItalic.ttf'],
        ];

        if (!is_dir($sourceDir)) {
            $this->error("Font source directory not found: {$sourceDir}");
            return self::FAILURE;
        }

        foreach ($faces as $face) {
            $src = $sourceDir.DIRECTORY_SEPARATOR.$face['file'];
            if (!is_file($src)) {
                $this->warn("Skipping {$face['file']} (not present in resources/fonts)");
                continue;
            }

            copy($src, $fontDir.DIRECTORY_SEPARATOR.$face['file']);

            $dompdf = new \Dompdf\Dompdf([
                'font_dir' => $fontDir,
                'font_cache_dir' => $cacheDir,
                'chroot' => realpath(base_path()),
            ]);

            $metrics = $dompdf->getFontMetrics();
            $metrics->registerFont(
                ['family' => $face['family'], 'style' => $face['style'], 'weight' => $face['weight']],
                $fontDir.DIRECTORY_SEPARATOR.$face['file']
            );

            $this->info("Registered {$face['family']} {$face['style']} {$face['weight']} <= {$face['file']}");
        }

        $this->info('Fonts installed into: '.$fontDir);

        return self::SUCCESS;
    }
}
