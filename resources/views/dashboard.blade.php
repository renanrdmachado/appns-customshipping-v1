<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <ul>
                        <li class="p-6 flex justify-between hover:bg-gray-700 rounded-lg">
                            <div>
                                <header>
                                    @if( $nuvemshopActive )
                                        <span class="px-2 text-gray-500 text-green-600"><i class="fa-regular fa-circle-check"></i></span>
                                    @else
                                        <span class="px-2 text-gray-500 text-red-600"><i class="fa-solid fa-circle-exclamation"></i></span>
                                    @endif
                                    <strong>Nuvemshop</strong>
                                </header>
                            </div>
                            <div>
                                <x-primary-link href="{{ env('NS_INSTALL_URL') }}"><i class="fa-solid fa-share-from-square"></i></x-primary-link>
                            </div>
                        </li>
                        <li class="p-6 flex justify-between hover:bg-gray-700 rounded-lg">
                            <div>
                                <header>
                                    @if( $hasCeps )
                                        <span class="px-2 text-gray-500 text-green-600"><i class="fa-regular fa-circle-check"></i></span>
                                    @else
                                        <span class="px-2 text-gray-500 text-red-600"><i class="fa-solid fa-circle-exclamation"></i></span>
                                    @endif
                                    <strong>CEPs</strong>
                                </header>
                            </div>
                            <div>
                                <x-primary-link href="{{ Route('shipping') }}"><i class="fa-solid fa-share-from-square"></i></x-primary-link>
                            </div>
                        </li>
                        <li class="p-6 flex justify-between hover:bg-gray-700 rounded-lg">
                            <div>
                                <header>
                                    @if( $storeActive )
                                        <span class="px-2 text-gray-500 text-green-600"><i class="fa-regular fa-circle-check"></i></span>
                                    @else
                                        <span class="px-2 text-gray-500 text-red-600"><i class="fa-solid fa-circle-exclamation"></i></span>
                                    @endif
                                    <strong>Assinatura</strong>
                                </header>
                            </div>
                            <div>
                                <x-primary-link href="{{ Route('payments.edit') }}"><i class="fa-solid fa-share-from-square"></i></x-primary-link>
                            </div>
                        </li>
                    </ul>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
