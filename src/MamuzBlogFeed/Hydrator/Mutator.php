<?php

namespace MamuzBlogFeed\Hydrator;

use Zend\Stdlib\Hydrator\HydrationInterface;

class Mutator implements HydrationInterface
{
    public function hydrate(array $data, $object)
    {
        foreach ($data as $key => $value) {
            $setMethod = 'set' . ucfirst($key);
            $addMethod = 'add' . ucfirst($key);
            if (method_exists($object, $setMethod)
                && is_callable(array($object, $setMethod))
            ) {
                $object->$setMethod($value);
            } elseif (method_exists($object, $addMethod)
                && is_callable(array($object, $addMethod))
            ) {
                $object->$addMethod($value);
            }
        }

        return $object;
    }
}
