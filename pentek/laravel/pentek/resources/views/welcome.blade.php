@extends('layouts.base')

@section('title', 'Üdvözlet')

@section('main-content')
    {{-- Ez egy komment --}}
    <?php
        echo '<p>Teszt</p>';
    ?>

    <?= '<p>Teszt</p>' ?>

    {{-- Blade stílusú PHP blokkok --}}
    @php
        echo '<p>Teszt</p>';

        $name = 'David';

        echo '<p>' . $name . '</p>';

        $number = 1;
    @endphp

    {{-- Változók, létezik-e, kiíratás... --}}
    {{ $name }}

    @php
        if (isset($name)) {
            echo '<p>A $name létezik</p>';
        }
    @endphp

    @isset($name)
        <p>A $name létezik</p>
    @endisset

    {{-- Elágazások --}}
    @if ($number == 1)
        <p>A $number értéke 1</p>
    @elseif ($number == 2)
        <p>A $number értéke 2</p>
    @else
        <p>A $number értéke se nem 1, se nem 2</p>
    @endif

    @switch ($number)
        @case(1)
            <p>A $number értéke 1</p>
            @break
        @case(2)
            <p>A $number értéke 2</p>
            @break
        @default
            <p>A $number értéke se nem 1, se nem 2</p>
    @endswitch

    {{-- Ciklusok --}}
    <ul>
        @for ($i = 0; $i < 5; $i++)
            <li>for ciklus: {{ $i }}</li>
        @endfor
    </ul>

    @php
        $fruits = ['alma', 'szilva', 'barack'];
        $fruits_empty = [];
    @endphp

    <ul>
        @foreach ($fruits as $fruit)
            <li>{{ $fruit }}</li>
        @endforeach
    </ul>

    <ul>
        @foreach ($fruits as $fruit)
            <li>{{ $loop->iteration }}. {{ $fruit }}</li>
        @endforeach
    </ul>

    <ul>
        @foreach ($fruits_empty as $fruit)
            <li>{{ $loop->iteration }}. {{ $fruit }}</li>
        @endforeach
    </ul>

    <ul>
        @forelse ($fruits_empty as $fruit)
            <li>{{ $loop->iteration }}. {{ $fruit }}</li>
        @empty
        <li>A tömb üres.</li>
        @endforelse
    </ul>

    @empty ($fruits_empty)
        <p>A $fruits_empty üres</p>
    @endempty
@endsection
