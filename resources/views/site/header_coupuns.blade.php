@if($coupons->isNotEmpty())
    <div class="max-w-6xl mx-auto mb-2 px-4 py-1.5 bg-white rounded-b-lg shadow-sm">
        <div class="flex gap-2 items-center overflow-x-auto whitespace-nowrap py-1 hide-scrollbar justify-center">
            {{-- Link Todos os Cupons --}}
            <a href="{{ route('site.coupun_view') }}"
               class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium transition
                   {{ !$selectedCompany ? 'bg-fuchsia-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-green-100 hover:text-green-700' }}">
                Todos os cupons
            </a>

            {{-- Loop pelas empresas --}}
            @foreach ($companys as $company)
                <a href="{{ route('site.coupun_view', $company->id) }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-medium transition
                       {{ $selectedCompany && $selectedCompany->id == $company->id ? 'bg-fuchsia-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-green-100 hover:text-green-700' }}">
                    {{-- Nome da empresa --}}
                    <span>{{ $company->name }}</span>

                    {{-- Logo da empresa --}}
                    @if ($company->soon)
                        <img src="{{ asset($company->soon ?? 'uploads/imgSem.jpg') }}" 
                             alt="{{ $company->name }}"
                             class="w-6 h-6 object-cover rounded-full bg-gray-100 border border-gray-200">
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endif
