<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sua assinatura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">

                    @if(!$nuvemshopActive)
                        <x-alert-store>Você deve integrar sua loja Nuvemshop para validar sua assinatura.</x-alert-store>
                    @endif

                    @if( $nuvemshopActive )
                        @if( !$store || !$store->payments_sub_id )
                            @include('payments.partials.payments-form')
                        @else
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                
                                @if( !$storeActive )
                                    <p class="mb-6"><i class="fa-solid fa-circle-exclamation"></i> Sua assinatura ainda não está ativa!</p>
                                @else
                                    <p class="mb-6"><i class="fa-regular fa-circle-check"></i> Sua assinatura está ativa!</p>
                                @endif

                                @if( $store->payments_data )
                                <ul class="mb-6">
                                    <li>
                                        <strong>Status: </strong> {{ $store->payments_status }}
                                    </li>
                                    @if( $store->payments_status=="RECEIVED" )
                                    <li>
                                        <strong>Próximo pagamento: </strong> {{ $store->payments_next_date }}
                                    </li>
                                    @endif
                                    <li>
                                        @php
                                            $invoice = json_decode($store->payments_data)->invoiceUrl;
                                        @endphp
                                        <strong>Link para pagamento: </strong> <a href="{{ $invoice }}" class="underline" target="_blank">{{ $invoice }}</a>
                                    </li>
                                </ul>
                                @endif

                                <form action="{{ Route('payments.refresh') }}" method="post">
                                    @csrf
                                    <x-text-input id="refresh" name="refresh" type="hidden" value="1" />
                                    <x-primary-button>Verificar novamente</x-primary-button>
                                </form>
                                

                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
