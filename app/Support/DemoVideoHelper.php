<?php

namespace App\Support;

class DemoVideoHelper
{
    /**
     * Normalize the stored demo syllabus modules so every module contains a
     * flattened array of unique video URLs and the legacy single video_url.
     */
    public static function normalizeModules($modules): array
    {
        if (empty($modules)) {
            return [];
        }

        if (is_string($modules)) {
            $modules = json_decode($modules, true) ?? [];
        }

        if (!is_array($modules)) {
            return [];
        }

        foreach ($modules as &$module) {
            $videoUrls = [];

            $existing = $module['video_urls'] ?? [];
            if (is_array($existing)) {
                foreach ($existing as $url) {
                    $normalized = self::normalizeUrl($url);
                    if ($normalized !== null) {
                        $videoUrls[] = $normalized;
                    }
                }
            } elseif (!empty($existing)) {
                $normalized = self::normalizeUrl($existing);
                if ($normalized !== null) {
                    $videoUrls[] = $normalized;
                }
            }

            $singleUrl = self::normalizeUrl($module['video_url'] ?? null);
            if ($singleUrl !== null && !in_array($singleUrl, $videoUrls, true)) {
                array_unshift($videoUrls, $singleUrl);
            }

            $videoUrls = array_values(array_unique($videoUrls));
            $module['video_urls'] = $videoUrls;
            $module['video_url'] = $videoUrls[0] ?? null;
        }

        return $modules;
    }

    public static function normalizeUrl(?string $url): ?string
    {
        $trimmed = trim((string) $url);
        return $trimmed === '' ? null : $trimmed;
    }
}
