<?php

/**
 * @category    Integento
 * @package     Integento_Assetsperf
 * @copyright   Copyright (c) 2016 @Darklg
 * @license     MIT
 * @author      @Darklg
 */

class Integento_Assetsperf_Helper_Core_Data extends Mage_Core_Helper_Data {

    /**
     * Merge specified files into one
     *
     * By default will not merge, if there is already merged file exists and it
     * was modified after its components
     * If target file is specified, will attempt to write merged contents into it,
     * otherwise will return merged content
     * May apply callback to each file contents. Callback gets parameters:
     * (<existing system filename>, <file contents>)
     * May filter files by specified extension(s)
     * Returns false on error
     *
     * @param array $srcFiles
     * @param string|false $targetFile - file path to be written
     * @param bool $mustMerge
     * @param callback $beforeMergeCallback
     * @param array|string $extensionsFilter
     * @return bool|string
     */
    public function mergeFiles(array $srcFiles, $targetFile = false, $mustMerge = false, $beforeMergeCallback = null, $extensionsFilter = array()) {

        $content_type = pathinfo($targetFile, PATHINFO_EXTENSION);

        switch ($content_type) {
        case 'css':
            if ($this->_getConfig('enablecompression_js')) {
                $beforeMergeCallback = array(&$this, 'integento_filter_css');
            }
            break;
        case 'js':
            if ($this->_getConfig('enablecompression_js')) {
                $beforeMergeCallback = array(&$this, 'integento_filter_js');
            }
            break;
        }

        return parent::mergeFiles($srcFiles, $targetFile, $mustMerge, $beforeMergeCallback, $extensionsFilter);
    }

    public function integento_filter_css($file, $content) {

        /* Call initial method */
        $content = Mage::getModel('core/design_package')->beforeMergeCss($file, $content);

        /* Clean content */
        $content = $this->integento_clean_content($content);

        /* Remove spaces around some chars */
        $content = preg_replace('/( *([{:,;}]) *)/', '$2', $content);

        /* Clean up some special cases */
        $content = str_replace(';}', '}', $content);

        return $content;
    }

    public function integento_filter_js($file, $content) {

        /* Ignore minified files */
        if ($this->integento_endsWith($file, '.min.js')) {
            /* But add some semicolons around the file content. */
            /* Because jQuery.min.js is not concatenation friendly */
            return ';' . $content . ';';
        }

        /* Clean content */
        $content = $this->integento_clean_content($content);

        return $content;
    }

    public function integento_clean_content($content) {

        /* Remove comments like this one */
        $pattern = '#\/\*([^\'])(.*)\*\/#ismU';
        $content = preg_replace($pattern, '', $content);

        /* Remove multiple spaces */
        $content = preg_replace("/[ ]{2,}/", " ", $content);

        /* Remove multiple line breaks */
        $content = preg_replace("/[\n]{2,}/", "\n", $content);

        return $content;
    }

    /* thx: http://stackoverflow.com/a/834355 */
    public function integento_endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    private function _getConfig($path) {
        return Mage::getStoreConfig('integento_assetsperf/settings/' . $path);
    }
}
