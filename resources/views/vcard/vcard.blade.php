<x-layout>
    <div
        class="relative w-[700px] h-[400px] overflow-hidden text-white"
        style="
            background-image: url('{{ $member->package->vcard_background?->getPath() }}');
            background-size: cover;
            background-position: center;
            font-family: 'SpaceMonoRegular', monospace;
        ">
        <div class="absolute left-8 top-[150px] right-10">
            <div
                class="text-[32px] uppercase tracking-[4px] leading-none whitespace-nowrap">
                {{$member->name}}
            </div>

            <div class="mt-5 text-[24px] tracking-[4px]">
                {{$member->number}}
            </div>
        </div>

        {{-- Footer --}}
        <div class="absolute left-8 bottom-8 flex gap-24">
            <div>
                <div class="text-[14px] uppercase tracking-wider">
                    DIVISION
                </div>

                <div class="mt-2 text-[22px]">
                    Regional Jabodetabek
                </div>
            </div>
        </div>
    </div>
</x-layout>