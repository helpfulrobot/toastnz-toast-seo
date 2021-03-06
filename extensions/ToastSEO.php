<?php

/**
 * Pinc
 */
class ToastSEO extends DataExtension {

    /**
     * @var array
     */
    private static $db = array(
        'SEOTitle' => 'Varchar(512)',
        'FocusKeyword' => 'Varchar(512)',
        'MetaAuthor' => 'Varchar(512)',
        'robotsIndex' => 'Varchar(512)',
        'robotsFollow' => 'Varchar(512)'
    );

    /**
     * @var array
     */
    private static $has_one = array();

    /**
     * @param FieldList $fields
     * @return Object
     */
    public function updateCMSFields(FieldList $fields) {

        Requirements::javascript(TOAST_DIR . '/javascript/toast-seo.js');

        $fields->removeByName('Metadata');


        $fields->addFieldsToTab('Root.Main', ToggleCompositeField::create('Toast SEO', 'Toast SEO',
            array(
                LiteralField::create('', '<h2>&nbsp;&nbsp;&nbsp;Toast SEO<img style="position:relative;top:8px;" src="' . Director::absoluteBaseURL() . 'toast-seo/Images/seo.png"></h2>'),
                LiteralField::create('', '<div class="toastSeo" style="margin-left:12px;">'),
                LiteralField::create('', '<br><strong>Focus Keyword Usage</strong>'),
                LiteralField::create('', '<br>Your focus keyword was found in:'),
                LiteralField::create('', '<br><ul>'),
                LiteralField::create('', '<li>Page Title:<strong class="toastSEOTitle"></strong></li>'),

                LiteralField::create('', '<li>Page URL: <strong class="toastURLMatch"></strong></li>'),
                LiteralField::create('', '<li>First Paragraph:<strong class="toastSEOSummary"></strong></li>'),
                LiteralField::create('', '<li>Meta Description:<strong class="toastSEOMeta"></strong></li>'),
                LiteralField::create('', '</ul>'),
                LiteralField::create('', '<div class="toastSEOSnippet" style="padding:0 20px 10px;background:white;margin:20px  20px 20px 0;display:block;border: 1px solid grey;"></div>'),
                LiteralField::create('', '</div>'),
                TextField::create('FocusKeyword', 'Page Subject')->addExtraClass('focusWords')->setRightTitle('Pick the main keywords or keyphrase that this page is about.'),
                TextField::create('SEOTitle', 'Meta Title')->setRightTitle('This meta title is generated automatically from the page name. Editing this will change how the page title shows up in google search. Each page title must be unique.'),
                LiteralField::create('', '<br><p class="toastSEOMetaCount" style="margin-left: 12px;">The meta description should be limited to 156 characters, <span class="toastSeoChars">6</span> chars left.</p>'),
                TextareaField::create('MetaDescription', 'Meta Description')->addExtraClass('toastSEOMetaText')->setRightTitle('The meta description is often shown as the black text under the title in a search result. For this to work it has to contain the keyword that was searched for.'),
                LiteralField::create('', '<div class="toastSEOSummaryText" style="opacity:0;position:relative;height:0;overflow:hidden;">::  ' . $this->owner->dbObject('Content')->Summary(25) . '</div>'),
                TextField::create('MetaAuthor', 'Author')->setRightTitle('Example: John Doe, j.doe@example.com'),
                HeaderField::create('', '&nbsp;&nbsp;&nbsp;Robots'),
                OptionsetField::create('robotsIndex', 'Index', array(
                    'index' => 'INDEX',
                    'noindex' => 'NOINDEX'
                ), 'index'),
                OptionsetField::create('robotsFollow', '&nbsp;&nbsp;&nbsp;Follow', array(
                    'follow' => 'FOLLOW',
                    'nofollow' => 'NOFOLLOW'
                ), 'follow')
            )
        ));
    }

    public function onBeforeWrite() {
        parent::onBeforeWrite();
        if ($this->owner->MetaDescription == '') {
            $this->owner->MetaDescription = $this->owner->dbObject('Content')->Summary(25);
        }
        if ($this->owner->SEOTitle == '') {
            $this->owner->SEOTitle = $this->owner->Title;
        }
        if (SiteConfig::current_site_config()->DefaultSEOMetaTitlePosition) {
            if (SiteConfig::current_site_config()->DefaultSEOMetaTitlePosition === 'before') {
                $this->owner->SEOTitle = str_replace(SiteConfig::current_site_config()->DefaultSEOMetaTitle, '', $this->owner->SEOTitle);
                $this->owner->SEOTitle = SiteConfig::current_site_config()->DefaultSEOMetaTitle . $this->owner->SEOTitle;
            } else {
                $this->owner->SEOTitle = str_replace(SiteConfig::current_site_config()->DefaultSEOMetaTitle, '', $this->owner->SEOTitle);
                $this->owner->SEOTitle = $this->owner->SEOTitle . SiteConfig::current_site_config()->DefaultSEOMetaTitle;
            }
        }
    }

}
