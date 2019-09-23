<?php

namespace Tests;

use BadMethodCallException;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Pagination\AbstractPaginator;

class TestCollectionData
{
    private $collection;
    private $fallback;

    public static function assert($collection)
    {
        return new static($collection);
    }
    
    public function __construct($collection, $fallback = null)
    {
        if (! $collection instanceof Collection && ! $collection instanceof AbstractPaginator) {
            PHPUnit::fail('The data is not a collection');
        }

        $this->collection = $collection;

        $this->fallback = $fallback;
    }

    public function equals(Collection $collection, $fieldToCompare = 'id')
    {
        $diff = $this->collection->pluck($fieldToCompare)
            ->diff($collection->pluck($fieldToCompare))
            ->all();

        PHPUnit::assertEmpty(
            $diff ,
            sprintf(
                "The collection contains unexpected elements with [%s]: [%s]",
                $fieldToCompare, join(', ', $diff)
            )
        );
    }

    public function contains($data)
    {
        PHPUnit::assertTrue(
            $this->collection->contains($data),
            "The collection is missing expected data [{$data}]."
        );

        return $this;
    }

    public function notContains($data)
    {
        PHPUnit::assertFalse(
            $this->collection->contains($data),
            "The collection has unexpected data [{$data}]."
        );

        return $this;
    }

    public function __call($method, $arguments)
    {
        if ($this->fallback) {
            return $this->fallback->$method(...$arguments);
        }

        throw new BadMethodCallException("Call to undefined method Tests\TestCollectionData::{$method}()");
    }
}
