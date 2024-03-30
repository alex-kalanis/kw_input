<?php

namespace TraitsTests;


use CommonTestClass;
use kalanis\kw_input\Entries;


abstract class ATraitsTest extends CommonTestClass
{
    protected function inputEntries(): array
    {
        return [
            (new Entries\Entry())->setEntry(Entries\Entry::SOURCE_EXTERNAL, 'foo', 'fhdfh'),
            (new Entries\Entry())->setEntry(Entries\Entry::SOURCE_EXTERNAL, 'bar', 'hjyxn'),
            (new Entries\Entry())->setEntry(Entries\Entry::SOURCE_EXTERNAL, 'baz', (new Entries\Entry())->setEntry(Entries\Entry::SOURCE_RAW, 'baz', 'gnbgy')),
            (new Entries\Entry())->setEntry(Entries\Entry::SOURCE_ENV, 'foo', 'mjgdf'),
            (new Entries\Entry())->setEntry(Entries\Entry::SOURCE_ENV, 'bar', 'shshh'),
        ];
    }
}
