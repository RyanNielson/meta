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
     * Display the meta tags with the set attributes
     * @param string $defaults The default meta attributes
     * @return string The meta tags
     */
    public function display($defaults = array(), $displayTitle = false)
    {
        $metaAttributes = array_replace_recursive($defaults, $this->attributes);
        $results = array();

         // Handle other custom properties.
        foreach($metaAttributes as $name => $content) {
            if ($name === 'keywords') {
                $keywords = $this->prepareKeywords($content);
                $results[] = $this->metaTag('keywords', $keywords);
            }
            elseif ($this->isAssociativeArray($content)) {
                $results = array_merge($results, $this->processNestedAttributes($name, $content));
            }
            else {
                foreach((array)$content as $con) {
                    $results[] =  $this->metaTag($name, $con);
                }
            }
        }

        if ($displayTitle && array_key_exists('title', $metaAttributes)) {
            $results[] = $this->titleTag($metaAttributes['title']);
        }

        return implode("\n", $results);
    }

    /**
     * Clears the meta attributes array.
     * @return array
     */
    public function clear()
    {
        $this->attributes = array();

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
     * Prepares keywords and converts the array to a comma separated string if required.
     * @return string Comma separated keywords.
     */
    private function prepareKeywords($keywords)
    {
        if ($keywords === null)
            return null;

        if (is_array($keywords))
            $keywords = implode(', ', $keywords);

        return strtolower(strip_tags($keywords));
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
                     $results[] =  $this->metaTag($property, $con);
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

    /**
     * Returns a meta tag with the given name and content.
     *
     * @param  string $name The name of the meta tag
     * @param  string $content  The meta tag content
     * @return string           The constructed meta tag
     */
    private function metaTag($name, $content)
    {
        if(substr($name, 0, 3) == 'og:')
            return "<meta property=\"$name\" content=\"$content\"/>";
        else
            return "<meta name=\"$name\" content=\"$content\"/>";
    }

    /**
     * Renders a title tag with the given content.
     *
     * @param  string $content The title tag content
     * @return string The value mapped to $key or null if none
     */
    private function titleTag($content)
    {
        return "<title>$content</title>";
    }

    /**
     * Removes an item from the array and returns its value.
     *
     * @param array $arr The input array
     * @param string $key The key pointing to the desired value
     * @return string The value mapped to $key or null if none
     */
    private function removeFromArray(&$array, $key)
    {
        if (array_key_exists($key, $array)) {
            $val = $array[$key];
            unset($array[$key]);
            return $val;
        }

       return null;
    }
}
