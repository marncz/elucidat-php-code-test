<?php

namespace Tests\Unit;

use App\Item;
use App\GildedRose;
use PHPUnit\Framework\TestCase;

class GuildedRoseTest extends TestCase
{
    /**
     * @dataProvider itemProvider
     *
     * @param Item $item
     * @param integer $expectedQuality
     * @param integer $expectedSellIn
     * @return void
     */
    public function testUpdatesItemCorrectlyForNextDay(
        Item $item,
        int $expectedQuality,
        int $expectedSellIn
    ): void {
        $gr = new GildedRose([$item]);

        $gr->nextDay();

        $this->assertEquals($expectedQuality, $gr->getItem(0)->quality);

        $this->assertEquals($expectedSellIn, $gr->getItem(0)->sellIn);
    }

    public function itemProvider(): array
    {
        return [
            /**
             * Normal items
             */
            'normal item before sell date' => [
                'item' => new Item('normal', 10, 5),
                'expectedQuality' => 9,
                'expectedSellIn' => 4,
            ],
            'normal item on sell date' => [
                'item' => new Item('normal', 10, 0),
                'expectedQuality' => 8,
                'expectedSellIn' => -1,
            ],
            'normal item after sell date' => [
                'item' => new Item('normal', 10, -5),
                'expectedQuality' => 8,
                'expectedSellIn' => -6,
            ],
            'normal item with quality of 0' => [
                'item' => new Item('normal', 0, 5),
                'expectedQuality' => 0,
                'expectedSellIn' => 4,
            ],

            /**
             * Brie items
             */
            'brie item before sell date' => [
                'item' => new Item('Aged Brie', 10, 5),
                'expectedQuality' => 11,
                'expectedSellIn' => 4,
            ],
            'brie item before sell date with maximum quality' => [
                'item' => new Item('Aged Brie', 50, 5),
                'expectedQuality' => 50,
                'expectedSellIn' => 4,
            ],
            'brie item on sell date' => [
                'item' => new Item('Aged Brie', 10, 0),
                'expectedQuality' => 12,
                'expectedSellIn' => -1,
            ],
            'brie item on sell date near maximum quality' => [
                'item' => new Item('Aged Brie', 49, 0),
                'expectedQuality' => 50,
                'expectedSellIn' => -1,
            ],
            'brie item on sell date with maximum quality' => [
                'item' => new Item('Aged Brie', 50, 0),
                'expectedQuality' => 50,
                'expectedSellIn' => -1,
            ],
            'brie item after sell date' => [
                'item' => new Item('Aged Brie', 10, -10),
                'expectedQuality' => 12,
                'expectedSellIn' => -11,
            ],
            'brie item after sell date with maximum quality' => [
                'item' => new Item('Aged Brie', 50, -10),
                'expectedQuality' => 50,
                'expectedSellIn' => -11,
            ],

            /**
             * Sulfuras items
             */
            'sulfuras item before sell date' => [
                'item' => new Item('Sulfuras, Hand of Ragnaros', 10, 5),
                'expectedQuality' => 10,
                'expectedSellIn' => 5,
            ],
            'sulfuras item on sell date' => [
                'item' => new Item('Sulfuras, Hand of Ragnaros', 10, 5),
                'expectedQuality' => 10,
                'expectedSellIn' => 5,
            ],
            'sulfuras item after sell date' => [
                'item' => new Item('Sulfuras, Hand of Ragnaros', 10, -1),
                'expectedQuality' => 10,
                'expectedSellIn' => -1,
            ],

            /**
             * Backstage passes
             */
            'backstage pass long before sell date' => [
                'item' => new Item('Backstage passes to a TAFKAL80ETC concert', 10, 11),
                'expectedQuality' => 11,
                'expectedSellIn' => 10,
            ],
            'backstage pass close to sell date' => [
                'item' => new Item('Backstage passes to a TAFKAL80ETC concert', 10, 10),
                'expectedQuality' => 12,
                'expectedSellIn' => 9,
            ],
            'backstage pass close to sell date at maximum quality' => [
                'item' => new Item('Backstage passes to a TAFKAL80ETC concert', 50, 10),
                'expectedQuality' => 50,
                'expectedSellIn' => 9,
            ],
            'backstage pass very close to sell date' => [
                'item' => new Item('Backstage passes to a TAFKAL80ETC concert', 10, 5),
                'expectedQuality' => 13,
                'expectedSellIn' => 4,
            ],
            'backstage pass very close to sell date at maximum quality' => [
                'item' => new Item('Backstage passes to a TAFKAL80ETC concert', 50, 5),
                'expectedQuality' => 50,
                'expectedSellIn' => 4,
            ],
            'backstage pass with one day left to sell' => [
                'item' =>  new Item('Backstage passes to a TAFKAL80ETC concert', 10, 1),
                'expectedQuality' => 13,
                'expectedSellIn' => 0,
            ],
            'backstage pass with one day left to sell at maximum quality' => [
                'item' =>  new Item('Backstage passes to a TAFKAL80ETC concert', 50, 1),
                'expectedQuality' => 50,
                'expectedSellIn' => 0,
            ],
            'backstage pass on sell date' => [
                'item' =>  new Item('Backstage passes to a TAFKAL80ETC concert', 10, 0),
                'expectedQuality' => 0,
                'expectedSellIn' => -1,
            ],
            'backstage pass after sell date' => [
                'item' =>  new Item('Backstage passes to a TAFKAL80ETC concert', 10, -1),
                'expectedQuality' => 0,
                'expectedSellIn' => -2,
            ],

            /**
             * Conjured items
             */
            'conjured item before sell date' => [
                'item' => new Item('Conjured Mana Cake', 10, 10),
                'expectedQuality' => 8,
                'expectedSellIn' => 9,
            ],
            'conjured item at zero quality' => [
                'item' => new Item('Conjured Mana Cake', 0, 10),
                'expectedQuality' => 0,
                'expectedSellIn' => 9,
            ],
            'conjured item on sell date' => [
                'item' => new Item('Conjured Mana Cake', 10, 0),
                'expectedQuality' => 6,
                'expectedSellIn' => -1,
            ],
            'conjured item on sell date at zero quality' => [
                'item' => new Item('Conjured Mana Cake', 0, 0),
                'expectedQuality' => 0,
                'expectedSellIn' => -1,
            ],
            'conjured item after sell date' => [
                'item' => new Item('Conjured Mana Cake', 10, -10),
                'expectedQuality' => 6,
                'expectedSellIn' => -11,
            ],
            'conjured item after sell date at zero quality' => [
                'item' => new Item('Conjured Mana Cake', 0, -10),
                'expectedQuality' => 0,
                'expectedSellIn' => -11,
            ],
       

            // Custom tests
            'normal item with quality of 52 (over the max)' => [
                'item' => new Item('normal', 52, 5),
                'expectedQuality' => 50,
                'expectedSellIn' => 4,
            ],
            'normal item with negative quality' => [
                'item' => new Item('normal', -5, 6),
                'expectedQuality' => 0,
                'expectedSellIn' => 5,
            ],
            'normal item with negavite sellIn' => [
                'item' => new Item('normal', 10, -6),
                'expectedQuality' => 8,
                'expectedSellIn' => -7,
            ],
        ];
    }
}
