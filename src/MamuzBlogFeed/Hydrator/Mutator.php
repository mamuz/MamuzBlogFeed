<?php

namespace MamuzBlogFeed\Hydrator;

use Zend\Stdlib\Hydrator\HydrationInterface;

class Mutator implements HydrationInterface
{
    public function hydrate(array $data, $object)
    {
        foreach ($data as $key => $value) {
            $property = ucfirst($key);
            if (!$this->mutate($object, 'set' . $property, $value)) {
                $this->mutate($object, 'add' . $property, $value);
            }
        }

        return $object;
    }

    /**
     * @param object $object
     * @param string $method
     * @param mixed  $value
     * @return bool
     */
    private function mutate($object, $method, $value)
    {
        if (method_exists($object, $method)
            && is_callable(array($object, $method))
        ) {
            $object->$method($value);
            return true;
        }

        return false;
    }
}
