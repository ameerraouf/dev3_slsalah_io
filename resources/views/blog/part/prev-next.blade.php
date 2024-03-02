<div class="flex justify-between">
    <div>
        @if($previousPost)
            <a class="flex items-center" href="{{ url('/blog', $previousPost->slug) }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-4 text-black" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 12l14 0"></path>
                    <path d="M5 12l6 6"></path>
                    <path d="M5 12l6 -6"></path>
                 </svg>
                <div>
                    <div class="uppercase text-[11px] letter tracking-wider mb-2">{{__('global.Previus Article')}}</div>
                    <div class="text-black">{{$previousPost->title}}</div>
                </div>
            </a>
        @endif
    </div>
    <div>
        @if($nextPost)
            <a class="flex flex-row-reverse items-center text-right" href="{{ url('/blog', $nextPost->slug) }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-4 text-black" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 12l14 0"></path>
                    <path d="M13 18l6 -6"></path>
                    <path d="M13 6l6 6"></path>
                 </svg>
                <div>
                    <div class="uppercase text-[11px] letter tracking-wider mb-2">{{__('global.Next Article')}}</div>
                    <div class="text-black">{{$nextPost->title}}</div>
                </div>
            </a>
        @endif
    </div>
</div>