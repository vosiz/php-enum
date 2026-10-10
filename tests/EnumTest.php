<?php

use PHPUnit\Framework\TestCase;
use Vosiz\Enums\Enum;
use Vosiz\Enums\EnumException;

class ElementEnum extends Enum {

    public static function Init(): void {

        $vals = [
            'fire'      => 0,
            'water'     => 1,
            'dark_air'  => [2, 'air of the dark'],
        ];
        self::AddValues($vals);
    }
}

class SeasonEnum extends Enum {

    public static function Init(): void {

        $vals = [
            'spring'    => 5,
        ];
        self::AddValues($vals);
    }
}

class PlanetEnum extends Enum {

    public static function Init(): void {

        $vals = [
            'mercury'   => 1,
            'venus'     => 2,
        ];
        self::AddValues($vals);
    }
}

class EnumTest extends TestCase {

    /** @test */
    public function ValueIsReadByKey() {

        $this->assertSame(1, ElementEnum::GetEnumVal('water'));
    }

    /** @test */
    public function EnumKeepsKeyValueAndDisplay() {

        $enum = ElementEnum::GetEnum('dark_air');

        $this->assertSame('dark_air', $enum->GetKey());
        $this->assertSame('dark_air', $enum->GetName());
        $this->assertSame(2, $enum->GetValue());
        $this->assertSame('air of the dark', $enum->GetDisplay());
        $this->assertSame('fire', ElementEnum::GetEnum('fire')->GetDisplay());
    }

    /** @test */
    public function AllValuesAreListedUnderCamelizedKeys() {

        $this->assertSame(['Fire', 'Water', 'DarkAir'], array_keys(ElementEnum::GetValues()->ToArray()));
        $this->assertSame(['Fire', 'Water', 'DarkAir'], array_keys(ElementEnum::GetAll()->ToArray()));
    }

    /** @test */
    public function EnumIsFoundByValueWithoutPreviousRead() {

        $this->assertSame('spring', SeasonEnum::Find(5)->GetKey());
        $this->assertNull(SeasonEnum::Find(6));
    }

    /** @test */
    public function EnumsOfSameTypeCompareByValue() {

        $water = ElementEnum::GetEnum('water');

        $this->assertTrue($water->Compare(ElementEnum::GetEnum('Water')));
        $this->assertFalse($water->Compare(ElementEnum::GetEnum('fire')));
    }

    /** @test */
    public function UnknownKeyThrowsEnumException() {

        $this->expectException(EnumException::class);

        ElementEnum::GetEnum('ether');
    }

    /** @test */
    public function ValuesAddedFromOutsideFollowPresetOnes() {

        PlanetEnum::AddValues(['earth' => 3]);

        $this->assertSame(['Mercury', 'Venus', 'Earth'], array_keys(PlanetEnum::GetValues()->ToArray()));
        $this->assertSame(3, PlanetEnum::GetEnumVal('earth'));
    }
}
