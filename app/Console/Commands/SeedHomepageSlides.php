<?php

namespace App\Console\Commands;

use App\Models\Banner;
use App\Support\SlideArtwork;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class SeedHomepageSlides extends Command
{
    protected $signature = 'storefront:slides
                            {--fresh : Remove the slides and artwork this command created before writing them again}';

    protected $description = 'Create the Sozie Collection homepage hero slides and their campaign artwork';

    /**
     * The copy is the storefront's own, in Kiswahili, and every link points at a
     * real route. The hero renders these strings as HTML above the artwork, so the
     * designs themselves stay text free.
     *
     * @var list<array<string, mixed>>
     */
    private const SLIDES = [
        [
            'key' => 'golden-aura',
            'title' => 'Golden Aura',
            'eyebrow' => 'KIKOMO CHA MIPAKA',
            'headline' => 'SOZIE GOLDEN',
            'highlight_text' => 'AURA.',
            'subtitle' => 'Zafranu ya Kashmiri, asali ya joto na amber safi iliyochanganywa kwa ustadi wa kisanii.',
            'button_text' => 'NUNUA SASA',
            'button_link' => '/shop',
            'secondary_button_text' => 'GUNDA AURA YAKO',
            'secondary_button_link' => '/#scent-finder',
        ],
        [
            'key' => 'noir-imperial',
            'title' => 'Noir Imperial',
            'eyebrow' => 'MPYA',
            'headline' => 'SOZIE NOIR',
            'highlight_text' => 'IMPERIAL.',
            'subtitle' => 'Miti ya nguvu, viungo vya moto na amber kwa alama isiyokumbushwa.',
            'button_text' => 'TAZAMA MAKUSANYO',
            'button_link' => '/shop',
            'secondary_button_text' => 'PATA HARUFU YAKO',
            'secondary_button_link' => '/#scent-finder',
        ],
        [
            'key' => 'scent-finder',
            'title' => 'Scent Finder',
            'eyebrow' => 'USHIKAMIZI WA FARAGHA',
            'headline' => 'GUNUA',
            'highlight_text' => 'HARAFU YAKO.',
            'subtitle' => 'Chagua aina ya harufu unayoipenda na ugundue marashi yanayokufaa mwenyewe kwa sekunde chini ya sita.',
            'button_text' => 'ANZA UTAFUTAJI',
            'button_link' => '/#scent-finder',
            'secondary_button_text' => 'FUTILIA ODA',
            'secondary_button_link' => '/track-order',
        ],
        [
            'key' => 'vip-circle',
            'title' => 'VIP Circle',
            'eyebrow' => 'MTOKO WA VIP',
            'headline' => 'ANZA',
            'highlight_text' => 'NA SOZIE.',
            'subtitle' => 'Pata taarifa za mapema ya vipya vya marashi, sampuli za bure na ofa zilizo mfaloni kwa wanachama.',
            'button_text' => 'JIUNGE VIP',
            'button_link' => '/register',
            'secondary_button_text' => 'NUNUA SOZIE',
            'secondary_button_link' => '/shop',
        ],
    ];

    public function handle(): int
    {
        $disk = Storage::disk('public');

        if ($this->option('fresh')) {
            $this->removeManagedSlides($disk);
        }

        $variants = SlideArtwork::variants();
        $created = 0;
        $updated = 0;

        foreach (self::SLIDES as $order => $slide) {
            $path = 'banners/sozie-'.$slide['key'].'.svg';
            $disk->put($path, $variants[$slide['key']]);

            $attributes = [
                'title' => $slide['title'],
                'eyebrow' => $slide['eyebrow'],
                'headline' => $slide['headline'],
                'highlight_text' => $slide['highlight_text'],
                'subtitle' => $slide['subtitle'],
                'image' => $path,
                'mobile_image' => $path,
                'button_text' => $slide['button_text'],
                'button_link' => $slide['button_link'],
                'secondary_button_text' => $slide['secondary_button_text'],
                'secondary_button_link' => $slide['secondary_button_link'],
                'is_active' => true,
                'sort_order' => $order + 1,
            ];

            $banner = Banner::query()->where('image', $path)->first();

            if ($banner instanceof Banner) {
                $banner->update($attributes);
                $updated++;

                $this->line("  <fg=gray>updated</> {$slide['title']}");

                continue;
            }

            Banner::query()->create($attributes);
            $created++;

            $this->line("  <fg=green>created</> {$slide['title']}");
        }

        $this->newLine();
        $this->info(count(self::SLIDES).' homepage slides are live (created: '.$created.', updated: '.$updated.').');
        $this->line('Edit or deactivate any of them at Admin -> Store Setup -> Website Content.');

        return self::SUCCESS;
    }

    private function removeManagedSlides(Filesystem $disk): void
    {
        foreach (self::SLIDES as $slide) {
            $path = 'banners/sozie-'.$slide['key'].'.svg';

            Banner::query()->where('image', $path)->orWhere('mobile_image', $path)->delete();

            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        }

        $this->line('  <fg=gray>removed the previously generated slides and artwork</>');
    }
}
