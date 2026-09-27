<?php

namespace Tests\Unit;

use Tests\TestCase;

class BladeModelMethodAccessTest extends TestCase
{
    /**
     * Model helper methods that return a scalar. Eloquent resolves an unknown
     * property as a relationship, so `$user->isAdmin` throws
     * "isAdmin must return a relationship instance" and 500s the page, while
     * `$user->isAdmin()` is correct.
     *
     * @var list<string>
     */
    private const SCALAR_METHODS = [
        'isAdmin',
        'isSuperAdmin',
        'hasRole',
        'canAccess',
    ];

    public function test_views_never_access_a_scalar_model_method_as_a_property(): void
    {
        $pattern = '/->('.implode('|', self::SCALAR_METHODS).')(?!\s*\()/';

        $offenders = [];

        foreach ($this->bladeFiles() as $file) {
            foreach (explode("\n", (string) file_get_contents($file)) as $number => $line) {
                if (preg_match($pattern, $line) === 1) {
                    $offenders[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file).':'.($number + 1);
                }
            }
        }

        $this->assertSame(
            [],
            $offenders,
            "Call these as methods, not properties, or the page will 500:\n".implode("\n", $offenders)
        );
    }

    /**
     * @return list<string>
     */
    private function bladeFiles(): array
    {
        $files = [];

        /** @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views'))) as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }
}
