<?php

namespace Database\Seeders;

use App\Models\Span;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Span::create(['nombre'=>120]);
        Span::create(['nombre'=>200]);
        Span::create(['nombre'=>250]);
        Span::create(['nombre'=>300]);
        Span::create(['nombre'=>600]);
    }
}
