<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Console\Command;
use Markdown;

class Docs extends Command
{
    use AppNamespaceDetectorTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:docs {action} {name=null} {version=null} {--related=null}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate docs, doc templates or Sami configs';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $actions = ['create', 'build', 'sami'];

        if (!in_array($this->argument('action'), $actions)) {
            $this->error('Please use one of the following rules: '.implode(', ', $actions));
        }

        if (!is_dir(base_path('documentation'))) {
            mkdir(base_path('documentation'), 0777, true);
            mkdir(base_path('documentation/rules'), 0777, true);
            mkdir(base_path('documentation/api'), 0777, true);
        }

        $name = str_replace(' ', '-', ucwords($this->argument('name')));

        if ($this->argument('action') === 'create') {
            $this->createDocs($name);
        }

        if ($this->argument('action') === 'build') {
            $this->buildDocs($name);
        }

        if ($this->argument('action') === 'sami') {
            $this->samiDocs();
        }
    }

    /**
     * Create the docs.
     *
     * @param string $name
     *
     * @return void
     */
    public function createDocs($name)
    {
        $ruleTemplate = file_get_contents(__DIR__.'/../Packages/Documentation/RuleTemplate.txt');

        $ruleTemplate = str_replace('_source_service_', 'App\Services\\'.ucfirst($name).'Service', $ruleTemplate);
        $ruleTemplate = str_replace('_source_model_', 'App\Repositories\\'.ucfirst($name).'\\'.ucfirst($name), $ruleTemplate);
        $ruleTemplate = str_replace('_source_repository_', 'App\Repositories\\'.ucfirst($name).'\\'.ucfirst($name).'Repository', $ruleTemplate);

        $related = $this->option('related');
        $ruleTemplate = str_replace('_related_service_', 'App\Services\\'.ucfirst($related).'Service', $ruleTemplate);
        $ruleTemplate = str_replace('_related_model_', 'App\Repositories\\'.ucfirst($related).'\\'.ucfirst($related), $ruleTemplate);
        $ruleTemplate = str_replace('_related_repository_', 'App\Repositories\\'.ucfirst($related).'\\'.ucfirst($related).'Repository', $ruleTemplate);

        $ruleBuild = str_replace('<name>', $name, $ruleTemplate);
        if (!file_exists(base_path('documentation/rules/index.md'))) {
            file_put_contents(base_path('documentation/rules/index.md'), file_get_contents(__DIR__.'/../Packages/Documentation/IndexTemplate.txt'));
        }
        if (file_put_contents(base_path('documentation/rules/01-'.$name.'.md'), $ruleBuild)) {
            $this->info('Built rule: '.$name);
        } else {
            $this->line('Could not build rule: '.$name);
        }
    }

    /**
     * Build the docs.
     *
     * @param string $name
     *
     * @return void
     */
    public function buildDocs($name)
    {
        if (!is_dir(base_path('documentation/build'))) {
            mkdir(base_path('documentation/build'), 0777, true);
            mkdir(base_path('documentation/build/rules'), 0777, true);
            mkdir(base_path('documentation/build/api'), 0777, true);
        }

        $this->copyTemplate();

        $section = base_path('documentation/rules');

        $this->line('Building section...');
        $files = glob($section.'/*');

        foreach ($files as $file) {
            if (is_dir($file)) {
                if (!file_exists(base_path('documentation/build/rules/'.basename($file)))) {
                    mkdir(base_path('documentation/build/rules/'.basename($file)));
                }
                $sectionIndexTemplate = file_get_contents(__DIR__.'/../Documentation/SiteTemplate/section_index.html');
                $sectionLinksContent = $this->getSectionLinks($file);

                $this->buildSection($file);

                $sectionIndex = str_replace('_section_title_', basename($section), $sectionIndexTemplate);
                $sectionIndex = str_replace('_section_links_', $sectionLinksContent, $sectionIndex);
                $sectionIndex = str_replace('_path_to_api_', '../../api/index.html', $sectionIndex);
                $sectionIndex = str_replace('_path_to_build_', '../', $sectionIndex);

                file_put_contents(base_path('documentation/build/'.basename($section).'/'.basename($file).'/index.html'), $sectionIndex);
            } else {
                $sectionLinksContent = $this->getSectionLinks($section);

                if (basename($file) === 'index.md') {
                    $realIndex = file_get_contents(__DIR__.'/../Documentation/SiteTemplate/index.html');
                    $realIndex = str_replace('_section_links_', $sectionLinksContent, $realIndex);
                    $realIndex = str_replace('_path_to_api_', '../api', $realIndex);
                    $realIndex = str_replace('_path_to_build_', '.', $realIndex);
                    file_put_contents(base_path('documentation/build/rules/index.html'), $realIndex);
                } else {
                    $parsed = $this->parser(file_get_contents($file), null, $sectionLinksContent);
                    file_put_contents(base_path('documentation/build/rules/'.str_replace('.md', '.html', basename($file))), $parsed);
                }
            }
        }

        $this->info('Building is complete');
    }

    /**
     * Create a SAMI config.
     *
     * @return void
     */
    public function samiDocs()
    {
        if (!file_exists(base_path('documentation/api/config.php'))) {
            $this->generateApiDocs();
            $this->line('Your Sami config can be found: '.base_path('documentation/api/config.php'));
        } else {
            $this->line('You already have a Sami config which can be found: '.base_path('documentation/api/config.php'));
        }
        $this->line("\n");
        $this->line('You need to install sami to generate your docs: composer global install sami/sami');
        $this->line("\n");
        $this->line('Then generate your docs please run: ');
        $this->line("\n");
        $this->line('sami.php update '.base_path('documentation/api/config.php'));
        $this->line("\n");
    }

    /**
     * Build a section of the site.
     *
     * @param string $section
     *
     * @return void
     */
    public function buildSection($section)
    {
        $this->line('Building section...'.basename($section));

        $files = glob($section.'/*');
        $sectionLinksContent = $this->getSectionLinks($section);

        foreach ($files as $file) {
            $unparsedFile = file_get_contents($file);
            $fileNameAsArray = explode('-', basename($file));
            $fileBasename = str_replace('.md', '.html', basename($file));
            $parsed = $this->parser($unparsedFile, $fileNameAsArray[0], $sectionLinksContent, 1);
            file_put_contents(base_path('documentation/build/rules/'.basename($section).'/'.$fileBasename), $parsed);
        }
    }

    /**
     * Get section links.
     *
     * @param string $section
     *
     * @return string
     */
    public function getSectionLinks($section)
    {
        $files = glob($section.'/*');
        $sectionLinks = '';
        $sectionLinksTemplate = file_get_contents(__DIR__.'/../Documentation/SiteTemplate/section.html');
        $linksTemplate = file_get_contents(__DIR__.'/../Documentation/SiteTemplate/section_link.html');
        $sectionLinksContent = '';

        foreach ($files as $file) {
            if (basename($section) === 'rules') {
                $sectionLinksContent = str_replace('_section_title_', 'Home', $sectionLinksTemplate);
            } else {
                $sectionLinksContent = str_replace('_section_title_', basename($section), $sectionLinksTemplate);
            }

            $parsedLink = $linksTemplate;
            if (is_dir($file)) {
                $parsedLink = str_replace('_link_file_', base_path('documentation/build/rules/').basename($file).'/index.html', $parsedLink);
            } else {
                if (basename($section) !== 'rules') {
                    $parsedLink = str_replace('_link_file_', base_path('documentation/build/rules/').basename($section).'/'.str_replace('.md', '.html', basename($file)), $parsedLink);
                } else {
                    $parsedLink = str_replace('_link_file_', base_path('documentation/build/').basename($section).'/'.str_replace('.md', '.html', basename($file)), $parsedLink);
                }
            }
            $parsedLink = str_replace('_link_name_', str_replace('.md', '', ucfirst(basename($file))), $parsedLink);
            $sectionLinks .= $parsedLink;
        }

        return str_replace('_section_links_', $sectionLinks, $sectionLinksContent);
    }

    /**
     * Generate the SAMI docs.
     *
     * @return void
     */
    public function generateApiDocs()
    {
        $configTemplate = file_get_contents(__DIR__.'/../Documentation/configTemplate.txt');
        $parseConfig = str_replace('_path_to_build_', base_path('documentation/build'), $configTemplate);
        $parseConfig = str_replace('_path_to_app_', app_path(), $parseConfig);

        file_put_contents(base_path('documentation/api/config.php'), $parseConfig);
    }

    /**
     * Copy the templates.
     *
     * @return void
     */
    public function copyTemplate()
    {
        copy(__DIR__.'/../Documentation/SiteTemplate/index.html', base_path('documentation/build/rules/index.html'));
        $this->recurseCopy(__DIR__.'/../Documentation/SiteTemplate/css', base_path('documentation/build/rules/css'));
        $this->recurseCopy(__DIR__.'/../Documentation/SiteTemplate/js', base_path('documentation/build/rules/js'));
        $this->recurseCopy(__DIR__.'/../Documentation/SiteTemplate/fonts', base_path('documentation/build/rules/fonts'));
    }

    /**
     * File copier.
     *
     * @param string $src
     * @param string $dst
     *
     * @return void
     */
    public function recurseCopy($src, $dst)
    {
        $dir = opendir($src);
        if (!file_exists($dst)) {
            mkdir($dst);
        }
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src.'/'.$file)) {
                    $this->recurseCopy($src.'/'.$file, $dst.'/'.$file);
                } else {
                    copy($src.'/'.$file, $dst.'/'.$file);
                }
            }
        }

        closedir($dir);
    }

    /**
     * File parser.
     *
     * @param string $contents
     * @param string $version
     * @param string $sectionLinks
     * @param int    $depth
     *
     * @return string
     */
    public function parser($contents, $version, $sectionLinks, $depth = 0)
    {
        $buildPath = '';
        if ($depth > 0) {
            foreach (range(1, $depth) as $x) {
                $buildPath .= '../';
            }
        } else {
            $buildPath .= './';
        }
        $parsed = Markdown::parse($contents, ['purifier' => false]);
        $content = str_replace('<version>', $version, $parsed);

        $pageTemplate = file_get_contents(__DIR__.'/../Documentation/SiteTemplate/page.html');
        $content = str_replace('_page_contents_', $content, $pageTemplate);
        $content = str_replace('_section_links_', $sectionLinks, $content);

        $content = str_replace('_path_to_api_', $buildPath.'../api', $content);
        $content = str_replace('_path_to_build_', $buildPath, $content);

        return $content;
    }
}
