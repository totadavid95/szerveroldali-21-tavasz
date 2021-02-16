@extends('layouts.base')
@section('title', 'Üdvözlő oldal')

@section('main-content')
    {{-- Tradícionális PHP blokkok --}}
    <?php
        echo '<p>Valami</p>';
    ?>

    <?= '<p>Valami</p>' ?>

    {{-- Blade stílusú PHP blokk --}}
    @php
        $something = 'Valami';
        echo '<p>' . $something . '</p>';

        $number = 33;

        $fruits = ['alma', 'szilva', 'barack'];
        $fruits_empty = [];
    @endphp

    {{-- Változók, változók kiírása --}}
    {{ $something }}

    @php
        if (isset($something)) {
            echo '<p>A $something létezik</p>';
        }
    @endphp

    @isset($something)
        <p>A $something létezik</p>
    @endisset

    @isset($something2)
        <p>A $something2 létezik</p>
    @endisset

    {{-- Elágazások --}}
    @if ($number == 1)
        <p>A $number az 1</p>
    @elseif ($number == 2)
        <p>A $number az 2</p>
    @else
        <p>A $number se nem 1 se nem 2</p>
    @endif

    @switch ($number)
        @case(1)
            <p>A $number az 1</p>
            @break
        @case(2)
            <p>A $number az 2</p>
            @break
        @default
            <p>A $number se nem 1 se nem 2</p>
    @endswitch

    {{-- Ciklusok --}}

    {{-- Hagyományos for ciklus --}}
    <ul>
        @for ($i = 0; $i < 5; $i++)
            <li>for ciklus: {{ $i }}</li>
        @endfor
    </ul>

    {{-- Iteráló ciklus --}}
    <ul>
        @foreach ($fruits as $fruit)
            {{-- A $loop változóból is ki lehet nyerni információkat, pl. az aktuális sorszámot --}}
            <li>{{ $loop->iteration }}. {{ $fruit }}</li>
        @endforeach
    </ul>

    {{-- Iteráló ciklus, üres ággal --}}
    <ul>
        @forelse ($fruits_empty as $fruit)
            <li>{{ $loop->iteration }}. {{ $fruit }}</li>
        @empty
            <p>A $fruits_empty üres</p>
        @endforelse
    </ul>

    @empty ($fruits_empty)
        <p>A $fruits_empty üres</p>
    @endempty

@endsection
