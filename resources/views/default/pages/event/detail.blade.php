@extends('default.layouts.app')
@section('title', $event->title)
@php
use App\Enums\PricingTypeEnum;

$pricingExternal = $event->pricing_type === PricingTypeEnum::EXTERNAL;
@endphp

@section('content')
<div class="min-h-screen bg-gray-50 pb-29">

    {{-- Cover --}}
    <div class="relative">
        <img
            src="{{ $event->cover?->getPath() ?? 'https://picsum.photos/1200/600' }}"
            class="aspect-[16/9] w-full object-cover">
    </div>

    <div class="-mt-6 relative rounded-t-3xl bg-white px-5 pt-6">
        {{-- Category --}}
        <div class="text-xs font-semibold uppercase text-primary">
            {{ $event->category?->name }}
        </div>

        {{-- Title --}}
        <h1 class="mt-2 text-3xl font-bold leading-tight">
            {{ $event->title }}
        </h1>

        {{-- Price --}}
        <div class="mt-4">
            <span class="text-3xl font-bold text-primary">
                @if($event->price)
                Rp {{ number_format($event->price,0,',','.') }}
                @else
                GRATIS
                @endif
            </span>
        </div>

        {{-- Information --}}
        <div class="mt-6 divide-y divide-gray-100 rounded-2xl border">
            <div class="flex items-start gap-4 p-4">
                <x-heroicon-o-calendar-days class="mt-0.5 h-5 w-5 text-primary" />
                <div>
                    <div class="font-medium">
                        Tanggal
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $event->event_date }}
                    </div>
                </div>
            </div>

            @if($event->stocks)
            <div class="flex items-start gap-4 p-4">
                <x-heroicon-o-user-group class="mt-0.5 h-5 w-5 text-primary" />
                <div>
                    <div class="font-medium">
                        Kuota
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ $event->stocks }} peserta
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Content --}}
        <div class="prose mt-8 max-w-none">
            {!! $event->content !!}
        </div>

    </div>

</div>

{{-- Bottom CTA --}}
<div class="fixed inset-x-0 bottom-18 z-40">
    <div class="mx-auto max-w-md px-4">
        <div class="rounded-2xl border bg-white p-4 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-500">
                        Harga
                    </div>

                    @if($event->price)
                    <div class="text-xl font-bold text-primary">
                        Rp {{ number_format($event->price,0,',','.') }}
                    </div>
                    @else
                    <div class="text-xl font-bold text-primary">
                        GRATIS
                    </div>
                    @endif
                </div>

                @if($event->pricing_type === 'external')
                <a
                    href="{{ $event->external_link }}"
                    target="_blank"
                    class="rounded-xl bg-primary px-6 py-3 font-medium text-white">
                    Daftar
                </a>
                @else
                <a
                    href="{{ $pricingExternal ? $event->external_link : '/events/'.$event->slug.'/reservation' }}"
                    class="rounded-xl bg-primary px-6 py-3 font-medium text-white">
                    Join Event
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection