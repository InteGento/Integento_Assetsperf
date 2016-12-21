<?php

/**
 * @category    Integento
 * @package     Integento_Assetsperf
 * @copyright   Copyright (c) 2016 @Darklg
 * @license     MIT
 * @author      @Darklg
 */

/*
 * Reorder Magento Assets loading :
 * Original code by http://claudiucreanga.me/magento/magento-change-order-css-js.html
 */
class Integento_Assetsperf_Block_Page_Html_Head extends Mage_Page_Block_Html_Head {

    public function addItemFirst($type, $name, $params = null, $if = null, $cond = null) {

        if ($type === 'skin_css' && empty($params)) {
            $params = 'media="all"';
        }

        $itemToInsert = array();
        $itemToInsert[$type . '/' . $name] = array(
            'type' => $type,
            'name' => $name,
            'params' => $params,
            'if' => $if,
            'cond' => $cond
        );

        $this->_data['items'] = array_merge($itemToInsert, $this->_data['items']);

        return $this;

    }

    public function addItemAfter($after, $type, $name, $params = null, $if = null, $cond = null) {

        if ($type === 'skin_css' && empty($params)) {
            $params = 'media="all"';
        }

        $itemToInsert = array();
        $itemToInsert[$type . '/' . $name] = array(
            'type' => $type,
            'name' => $name,
            'params' => $params,
            'if' => $if,
            'cond' => $cond
        );

        // Search the "after" in the array
        $pos = 1;
        $elementFound = false;
        foreach ($this->_data['items'] as $key => $options) {
            if ($key == $after || $options['name'] == $after):
                $elementFound = true;
                break;
            endif;
            $pos++;
        }

        if ($elementFound) {
            array_splice($this->_data['items'], $pos, 0, $itemToInsert);
        }

        return $this;

    }

}
