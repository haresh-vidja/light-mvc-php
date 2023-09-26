<?php

/**
 * ================================================================
 * Permanent Redirect Rules (301)
 * ================================================================
 *
 * This array is used to define **permanent (301) redirection rules**
 * within the website. It maps outdated or moved URLs to their
 * new destinations.
 *
 * Usage:
 * - The key represents the **old URL path** (relative to the domain).
 * - The value represents the **new target path** or destination URL.
 *
 * These rules are typically processed early in the request lifecycle
 * to ensure SEO-friendly and user-safe redirections.
 *
 * ---------------------------------------------------------------
 * Example:
 * "old-page"            => "new-page"
 * "services/web"        => "services/web-development"
 * "blog/old-post-slug"  => "blog/new-post-slug"
 *
 * This will result in:
 * https://yoursite.com/old-page        → 301 → https://yoursite.com/new-page
 * https://yoursite.com/services/web    → 301 → https://yoursite.com/services/web-development
 *
 * Note:
 * - Do NOT include domain names, only URI paths.
 * - Ensure values do NOT cause circular redirects.
 * - Always test after adding new rules.
 */

return [
    // Replace the following with actual mappings
    "old/site/uri-1" => "new/site/uri-1",
    "old/site/uri-2" => "new/site/uri-2",
];
