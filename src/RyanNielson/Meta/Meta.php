<?php namespace RyanNielson\Meta;

class Meta {

    /**
     * The current stored meta attributes to be rendered at a later stage.
     * @var array
     */
    private $attributes = array();

    /**
     * Sets the meta attributes.
     * @param array $attributes
     * @return array
     */
    public function set($attributes = array())
    {
        $this->attributes = array_replace_recursive($this->attributes, $attributes);

        return $this->attributes;
    }

    /**
     * Clears the meta attributes array.
     * @return array
     */
    public function clear()
    {
        $this->attributes = [];

        return $this->attributes;
    }

    /**
     * Returns the current meta attributes.
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Display the meta tags with the set attributes
     * @return string The meta tags
     */
    public function display()
    {
        $results = array();

        $title = $this->getAttribute('title');
        if ($title !== null)
            $results[] = '<meta name="title" content="' . $title . '"/>';

        $description = $this->getAttribute('description');
        if ($description !== null)
            $results[] = '<meta name="description" content="' . $description .'"/>';

        $keywords = $this->prepareKeywords();
        if ($keywords !== null)
            $results[] = '<meta name="keywords" content="' . $keywords .'"/>';

        foreach($this->attributes as $key => $value) {
            if ($this->isAssociativeArray($value)) {
                $results = array_merge($results, $this->processNestedAttributes($key, $value));
            }
        }

        return implode("\n", $results);
    }

    /**
     * Prepares keywords and converts the array to a comma separated string if required.
     * @return string Comma separated keywords.
     */
    private function prepareKeywords()
    {
        $keywords = $this->getAttribute('keywords');
        if ($keywords === null)
            return null;

        if (is_array($keywords))
            $keywords = implode(', ', $keywords);

        return strtolower(strip_tags($keywords));
    }

    /**
     * Removes an item from the array and returns its value.
     *
     * @param array $arr The input array
     * @param $key The key pointing to the desired value
     * @return string The value mapped to $key or null if none
     */
    private function removeFromAttributes($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            $val = $this->attributes[$key];
            unset($this->attributes[$key]);
            return $val;
        }

        return null;
    }

    /**
     * Returns an attribute using the key, and null if it hasn't been set.
     * @param  string $key
     * @return string The value mapped to $key, or null if none
     */
    private function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes))
            return $this->attributes[$key];

        return null;
    }

    /**
     * Process nested attributes recursively.
     * @param  string $property
     * @param  array $content
     * @return array An array of meta tags for the nested attributes
     */
    private function processNestedAttributes($property, $content)
    {
        $results = array();

        if ($this->isAssociativeArray($content)) {
            foreach ($content as $key => $value) {
                $results = array_merge($results, $this->processNestedAttributes("{$property}:{$key}", $value));
            }
        }
        else {
            foreach((array)$content as $con) {
                if ($this->isAssociativeArray($con))
                    $results = array_merge($results, $this->processNestedAttributes($property, $con));
                else
                     $results[] =  '<meta name="' . $property . '" content="' . $con .'"/>';
            }
        }

        return $results;
    }

    /**
     * Determines if an array is associative.
     * @param  string  $value
     * @return boolean
     */
    private function isAssociativeArray($value)
    {
        return is_array($value) && (bool)count(array_filter(array_keys($value), 'is_string'));
    }

}