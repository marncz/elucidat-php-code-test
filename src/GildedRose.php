<?php

namespace App;

class GildedRose
{
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItem($which = null)
    {
        return ($which === null
            ? $this->items
            : $this->items[$which]
        );
    }

    public function nextDay()
    {
        foreach ($this->items as $item) {

            // These two items have a different logic
            if ($item->name != 'Aged Brie' && $item->name != 'Backstage passes to a TAFKAL80ETC concert') {

                // "Sulfuras", being a legendary item, never has to be sold or decreases in Quality
                if ($item->name != 'Sulfuras, Hand of Ragnaros') {

                    $item->quality--;
                    $item->sellIn--;

                    // Once the sell by date has passed, Quality degrades twice as fast
                    if ($item->sellIn < 0) {
                        $item->quality--;
                    }
                
                    // "Conjured" items degrade in Quality twice as fast as normal items
                    if ($item->name == "Conjured Mana Cake") {
                        $item->quality--;
    
                        if ($item->sellIn < 0) {
                            $item->quality--;
                        }
                    }

                }     
    
            } else {
                if ($item->name == "Backstage passes to a TAFKAL80ETC concert" ) {

                    // Quality drops to 0 after the concert
                    if ($item->sellIn <= 0) {
                        $item->quality = 0;
                    } else {
                        // Quality increases by 2 when there are 10 days or less and by 3 when there are 5 days or less
                        if ($item->sellIn <= 5) {
                            $item->quality += 3;
                        } else if ($item->sellIn <= 10) {
                            $item->quality += 2;
                        } else {
                            $item->quality++;
                        }
                    }
                }

                // "Aged Brie" actually increases in Quality the older it gets
                if ($item->name == "Aged Brie") {
                    $item->quality++;

                    if ($item->sellIn <= 0) {
                        $item->quality++;
                    }
                }
                
                // SellIn gets updated regardless for these items
                $item->sellIn--;
            }

            // The Quality of an item is never negative
            if ($item->quality < 0) {
                $item->quality = 0;
            }

            // The Quality of an item is never more than 50
            if ($item->quality > 50) {
                $item->quality = 50;
            }

        }
    }
}
