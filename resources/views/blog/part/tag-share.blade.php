<div class="flex justify-between mt-20">
    <div class="flex items-center gap-2 text-[13px] font-normal">
        @if($post->tag)
        <div class="text-black">{{__('global.Tags')}}:</div>
        <div class="flex space-x-1">
            @foreach (explode(',', $post->tag) as $tag)
            <a class="bg-gray-200 rounded-md px-3 py-1 flex items-center hover:text-black"
                href="{{ url('/blog/tag', $tag) }}">{{$tag}}</a>
            @endforeach
        </div>
        @endif
    </div>
    <div class="flex items-center gap-2">
        <div class="flex gap-1">
            <a rel="nofollow" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url()->full()}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path
                        d="M18 2a1 1 0 0 1 .993 .883l.007 .117v4a1 1 0 0 1 -.883 .993l-.117 .007h-3v1h3a1 1 0 0 1 .991 1.131l-.02 .112l-1 4a1 1 0 0 1 -.858 .75l-.113 .007h-2v6a1 1 0 0 1 -.883 .993l-.117 .007h-4a1 1 0 0 1 -.993 -.883l-.007 -.117v-6h-2a1 1 0 0 1 -.993 -.883l-.007 -.117v-4a1 1 0 0 1 .883 -.993l.117 -.007h2v-1a6 6 0 0 1 5.775 -5.996l.225 -.004h3z"
                        stroke-width="0" fill="currentColor"></path>
                </svg>
            </a>
            <a rel="nofollow" target="_blank"
                href="https://twitter.com/intent/tweet?text={{$post->title}}&url={{url()->full()}}">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="18" height="18" viewBox="0,0,256,256">
                    <g fill="#656f7e" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt"
                        stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0"
                        font-family="none" font-weight="none" font-size="none" text-anchor="none"
                        style="mix-blend-mode: normal">
                        <g transform="scale(5.12,5.12)">
                            <path
                                d="M11,4c-3.866,0 -7,3.134 -7,7v28c0,3.866 3.134,7 7,7h28c3.866,0 7,-3.134 7,-7v-28c0,-3.866 -3.134,-7 -7,-7zM13.08594,13h7.9375l5.63672,8.00977l6.83984,-8.00977h2.5l-8.21094,9.61328l10.125,14.38672h-7.93555l-6.54102,-9.29297l-7.9375,9.29297h-2.5l9.30859,-10.89648zM16.91406,15l14.10742,20h3.06445l-14.10742,-20z">
                            </path>
                        </g>
                    </g>
                </svg>
            </a>
            <a rel="nofollow" target="_blank" href="mailto:?subject={{$post->title}}&body={{url()->full()}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path
                        d="M22 7.535v9.465a3 3 0 0 1 -2.824 2.995l-.176 .005h-14a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-9.465l9.445 6.297l.116 .066a1 1 0 0 0 .878 0l.116 -.066l9.445 -6.297z"
                        stroke-width="0" fill="currentColor"></path>
                    <path
                        d="M19 4c1.08 0 2.027 .57 2.555 1.427l-9.555 6.37l-9.555 -6.37a2.999 2.999 0 0 1 2.354 -1.42l.201 -.007h14z"
                        stroke-width="0" fill="currentColor"></path>
                </svg>
            </a>
        </div>
        <div class="text-gray-500 text-[11px] uppercase">{{__('global.Share on')}}</div>
    </div>
</div>