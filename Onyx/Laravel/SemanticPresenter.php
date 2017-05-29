<?php

namespace Onyx\Laravel;

use Illuminate\Pagination\BootstrapThreePresenter;

class SemanticPresenter extends BootstrapThreePresenter
{
    /**
     * Get HTML wrapper for active text.
     *
     * @param string $text
     *
     * @return string
     */
    public function getActivePageWrapper($text)
    {
        return '<div class="active item">'.$text.'</div>';
    }

    /**
     * Get HTML wrapper for disabled text.
     *
     * @param string $text
     *
     * @return string
     */
    public function getDisabledTextWrapper($text)
    {
        return '<div class="disabled item">'.$text.'</div>';
    }

    /**
     * Get HTML wrapper for a page link.
     *
     * @param string $url
     * @param int    $page
     * @param string $rel
     *
     * @return string
     */
    public function getPageLinkWrapper($url, $page, $rel = null)
    {
        $rel = is_null($rel) ? '' : ' rel="'.$rel.'"';

        return '<a class="item" href="'.$url.'"'.$rel.'>'.$page.'</a>';
    }

    /**
     * Get a pagination "dot" element.
     *
     * @return string
     */
    public function getDots()
    {
        return $this->getDisabledTextWrapper('<i class="ellipsis horizontal icon"></i>');
    }

    /**
     * Render the Pagination contents.
     *
     * @return string
     */
    public function render()
    {
        if ($this->hasPages()) {
            return '<div class="ui pagination menu">'.sprintf(
                '%s %s %s',
                $this->getPreviousButton(),
                $this->getLinks(),
                $this->getNextButton()
            ).'</div>';
        }

        return '';
    }
}
