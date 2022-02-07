<?php

declare(strict_types=1);

namespace BEAR\QiqModule;

use Qiq\Template;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

final class TemplateProvidcer implements ProviderInterface
{
    public function __construct(
        #[Named('qiq_template_dir')] private string $templateDir,
        #[Named('qiq_cache_path')] private ?string $cachePath = null,
    ) {
    }

    public function get(): Template
    {
        return Template::new(paths: $this->templateDir, cachePath: $this->cachePath);
    }
}
