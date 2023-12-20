<?php

namespace paraqr\payment\base;

class Bean {

    /**
     * @param $data
     * @return $this
     */
    public function setData($data): Bean {
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $key1 = str_replace('_', ' ', $key);
                $key1 = lcfirst(ucwords(strtolower($key1)));
                $key1 = str_replace(' ', '', $key1);
                if (property_exists($this, $key1)) {
                    $this->{$key1} = $val;
                } elseif (property_exists($this, $key)) {
                    $this->{$key} = $val;
                }
            }
        }
        return $this;
    }
}
