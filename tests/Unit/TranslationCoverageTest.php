<?php

namespace Tests\Unit;

use Illuminate\Support\Arr;
use Tests\TestCase;

class TranslationCoverageTest extends TestCase
{
    /**
     * Every literal translation key used in views or application code must be
     * translated for each supported locale, otherwise customers see raw keys.
     */
    public function test_every_literal_translation_key_exists_in_all_locales(): void
    {
        $supportedLocales = ['en', 'sw'];
        $translations = [];

        foreach ($supportedLocales as $locale) {
            $path = lang_path("{$locale}.json");
            $this->assertFileExists($path, "Missing translation file for [{$locale}].");

            $decoded = json_decode((string) file_get_contents($path), true);
            $this->assertIsArray($decoded, "Invalid JSON in [{$path}].");

            $translations[$locale] = $decoded;
        }

        $missing = [];

        foreach ($this->usedTranslationKeys() as $key => $locations) {
            foreach ($supportedLocales as $locale) {
                if (! $this->hasTranslation($locale, $key, $translations[$locale])) {
                    $missing[] = "[{$locale}] {$key} (first used in {$locations[0]})";
                }
            }
        }

        $this->assertSame([], $missing, "Missing translations:\n".implode("\n", $missing));
    }

    /**
     * Flat keys live in lang/{locale}.json, group keys such as "order.status.new"
     * live in lang/{locale}/{group}.php. A key only counts as a group key when
     * every dot separated segment is a lower snake case identifier, so English
     * source strings such as "e.g. Amina Khamis" stay flat keys.
     *
     * @param  array<string, mixed>  $jsonTranslations
     */
    private function hasTranslation(string $locale, string $key, array $jsonTranslations): bool
    {
        $segments = explode('.', $key);
        $isGroupKey = count($segments) > 1;

        foreach ($segments as $segment) {
            if (preg_match('/^[a-z][a-z0-9_]*$/', $segment) !== 1) {
                $isGroupKey = false;
                break;
            }
        }

        if (! $isGroupKey) {
            return array_key_exists($key, $jsonTranslations);
        }

        $group = $segments[0];
        $groupFile = lang_path("{$locale}/{$group}.php");

        if (! is_file($groupFile)) {
            return false;
        }

        $items = require $groupFile;

        return is_array($items) && Arr::has($items, implode('.', array_slice($segments, 1)));
    }

    public function test_english_and_swahili_translation_files_have_identical_keys(): void
    {
        $english = array_keys((array) json_decode((string) file_get_contents(lang_path('en.json')), true));
        $swahili = array_keys((array) json_decode((string) file_get_contents(lang_path('sw.json')), true));

        sort($english);
        sort($swahili);

        $this->assertSame($english, $swahili, 'en.json and sw.json must define exactly the same keys.');
    }

    /**
     * A flat key whose text matches a lang/{locale}/{name}.php file name resolves to
     * the whole PHP array instead of a string, which breaks rendering. File name
     * lookups are case insensitive, so "Order" would collide with lang/en/order.php.
     */
    public function test_no_flat_key_collides_with_a_group_translation_file(): void
    {
        $groupNames = [];

        foreach (glob(lang_path('en/*.php')) ?: [] as $file) {
            $groupNames[] = strtolower(basename($file, '.php'));
        }

        $collisions = [];

        foreach (array_keys((array) json_decode((string) file_get_contents(lang_path('en.json')), true)) as $key) {
            if (in_array(strtolower((string) $key), $groupNames, true)) {
                $collisions[] = $key;
            }
        }

        $this->assertSame([], $collisions, 'These keys collide with a lang/*.php group file: '.implode(', ', $collisions));
    }

    public function test_no_translation_value_is_empty(): void
    {
        foreach (['en', 'sw'] as $locale) {
            foreach ((array) json_decode((string) file_get_contents(lang_path("{$locale}.json")), true) as $key => $value) {
                $this->assertIsString($value, "[{$locale}] [{$key}] must be a string.");
                $this->assertNotSame('', trim($value), "[{$locale}] [{$key}] must not be empty.");
            }
        }
    }

    /**
     * @return array<string, array<int, string>> Translation key => list of "file:line" locations.
     */
    private function usedTranslationKeys(): array
    {
        $files = array_merge(
            $this->phpFiles(app_path()),
            $this->bladeFiles(resource_path('views')),
        );

        $pattern = '/(?:\b__|@lang|\btrans)\(\s*([\'"])((?:\\\\.|(?!\1).)*)\1/';
        $keys = [];

        foreach ($files as $file) {
            // Published vendor views (resources/views/vendor/**) are framework code
            // lifted verbatim out of a package. Their __() keys — "pagination.previous",
            // "Showing", "Go to page :page" and friends — are already translated by
            // Laravel itself, from its own lang files and its built-in fallback
            // catalogue, so requiring this application's lang/{locale}.json to
            // redefine them would be wrong.
            if (str_contains(str_replace('\\', '/', $file), '/resources/views/vendor/')) {
                continue;
            }

            $contents = (string) file_get_contents($file);
            $lines = explode("\n", $contents);

            foreach ($lines as $index => $line) {
                if (preg_match_all($pattern, $line, $matches) === false) {
                    continue;
                }

                foreach ($matches[2] as $key) {
                    $key = stripcslashes($key);

                    if ($key === '') {
                        continue;
                    }

                    $relative = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file);
                    $keys[$key][] = $relative.':'.($index + 1);
                }
            }
        }

        return $keys;
    }

    /**
     * @return array<int, string>
     */
    private function phpFiles(string $directory): array
    {
        $files = [];

        /** @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * @return array<int, string>
     */
    private function bladeFiles(string $directory): array
    {
        $files = [];

        /** @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }
}
