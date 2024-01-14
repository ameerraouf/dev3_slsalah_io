@extends(config('elseyyid-location.layout'))
@section(config('elseyyid-location.content_section'))

        <div class="col-md-12">

            @if ( $lang != 'edit' )
            @php $lang_region = LaravelLocalization::getSupportedLocales()[str_replace('_','-',$lang)]['regional']; @endphp
            @php $lang_native = LaravelLocalization::getSupportedLocales()[str_replace('_','-',$lang)]['native']; @endphp
            <h2 class="text-center mb-6">{{__('Editing Language')}}: <span class="text-3xl align-sub mr-2">{{ country2flag(substr($lang_region, strrpos($lang_region, '_') + 1)) }}</span>{{ucfirst($lang_native)}}<span class="ml-2 opacity-20 text-xs">{{$lang}}</h2>
            @else 

            <div class="alert alert-danger mb-6" role="alert">
                <strong>{{__('Take a backup before process!')}}</strong><br>
                {{__('Your changes will override en.json file!')}}
            </div>
            <h2 class="text-center mb-6">{{__('Editing Main Strings')}}</h2>
            @endif

            <input type="text" id="search_string" class="form-control rounded-xl mb-6" onkeyup="searchStrings()" placeholder="{{__('Filter strings...')}}">
            <div class="card">
                <div class="card-table table-responsive">
                <table class="table" id="strings">
                    <thead>
                        <tr>
                            <th>{{__('String')}}</th>
                            <th>{{__('Translation')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $key => $value)
                        <tr>
                            <td class="hidden" width="10px"><input type="checkbox" name="ids_to_edit[]" value="{{$value->id}}" /></td>
                            @foreach ($value->toArray() as $key => $element)
                                @if ($key !== 'code')
                                    @if ( $key === 'en' )
                                        <td class="w-[45%]">
                                            <div data-name="{{$key}}">{{$element}}</div>
                                        </td>
                                    @else
                                        <td class="w-[50%]">
                                            <input
                                                class="form-control py-2 px-2 rounded-md bg-[#F6F6F6] border-none w-full placeholder:text-gray-300 dark:border-solid dark:border-[--tblr-border-color] dark:placeholder:text-white/50"
                                                type="text"
                                                data-pk="{{$value->code}}"
                                                data-name="{{$key}}"
                                                value="{{$element}}"
                                                placeholder="{{__('enter string')}}"
                                            >
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
			<div class="fixed bottom-6 left-[--navbar-width] right-0">
				<div class="container">
					<div class="row">
						<div class="col-md-8 mx-auto !px-8">
							<div id="save_all" data-lang="{{$lang}}" class="btn btn-primary w-full">Save</div>
						</div>
					</div>
				</div>
			</div>
        </div>
@endsection
@section(config('elseyyid-location.scripts_section'))
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        "use strict";
        $('#save_all').click(function() {
            var demo = @json( env('APP_STATUS') == 'Demo' ? true : false );
            if ( demo == true ){
                toastr.info("{{ __('This feature is disabled in Demo version') }}");
                return false;
            }

            document.getElementById( "save_all" ).disabled = true;
            document.getElementById( "save_all" ).innerHTML = magicai_localize.please_wait;

            var formData = new FormData();
            var inputData = [];

            $('table input[type="text"]').each(function() {
                var value = $(this).val();
                inputData.push(value);
            });

            var jsonData = JSON.stringify(inputData);
            formData.append( 'data', jsonData );
            formData.append( 'lang', $(this).data('lang') );

            $.ajax( {
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                url: "/translations/lang/update-all",
                data: formData,
                contentType: false,
                processData: false,
                success: function ( data ) {
                    toastr.success("{{ __('Strings saved successfully') }}");
                    document.getElementById( "save_all" ).disabled = false;
                    document.getElementById( "save_all" ).innerHTML = "Save";
                },
                error: function ( data ) {
                    var err = data.responseJSON.errors;
                    $.each( err, function ( index, value ) {
                        toastr.error( value );
                    } );
                    document.getElementById( "save_all" ).disabled = false;
                    document.getElementById( "save_all" ).innerHTML = "Save";
                }
            } );
            return false;
        });
    });

    </script>

    <script>
        function searchStrings() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("search_string");
            filter = input.value.toUpperCase();
            table = document.getElementById("strings");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                var foundMatch = false;
                td = tr[i].getElementsByTagName("td");

                if (td.length > 0) {
                    for (j = 0; j < td.length; j++) {
                        var divElement = td[j].querySelector("div[data-name='en']");
                        var inputElement = td[j].querySelector("input");
                        if (divElement && divElement.textContent.toUpperCase().indexOf(filter) > -1) {
                            foundMatch = true;
                            break;
                        } else if (inputElement && inputElement.value.toUpperCase().indexOf(filter) > -1) {
                            foundMatch = true;
                            break;
                        }
                    }
                }

                if (foundMatch) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = tr[i].parentNode.tagName === 'THEAD' ? 'table-row' : 'none';
                }
            }
        }

    </script>

@endsection
