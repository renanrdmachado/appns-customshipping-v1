<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('CEPs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 dark:bg-gray-800 dark:text-gray-100 bg-white">
                    <div class="flex justify-between">
                        <div class="col-md-6">
                            <strong>Edite as faixas de CEP para sua loja!</strong>

                            
                        </div>
                        <div class="text-sm text-gray-300">
                            <p>Em breve: upload de planilha CSV.</p>
                        </div>
                        <!--
                        <div class="col-md-12 mt-2">
                            <form class="js-shipping-import-xls" method="POST" enctype="multipart/form-data">
                                <strong>Importar CSV</strong><br/>
                                <small>*Ao importar os CEPs via CSV, todos os ceps cadastrados serão substituídos pelo CEPs do CSV.</small><br/>
                                <input type="file" name="my-csv">
                                <input type="submit" value="Enviar" class="btn btn-primary">
                            </form>
                        </div>
                        -->
                    </div>
                    
                </div>

                <div class="p-6 dark:bg-gray-800 border-gray-200 bg-white">

                    
                    @if(!$nuvemshopActive)
                        <x-alert-store>Você deve integrar sua loja Nuvemshop para cadastrar as faixas de Cep.</x-alert-store>
                    @endif

                    @if($nuvemshopActive)
                    <form id="form-zipcodes-range" method="POST">
                        <table id="zipcodes-range" class="w-full text-gray-200">
                            <tr class="text-gray-600 dark:text-gray-300">
                                <th>Nome</th>
                                <th>Ceps - De</th>
                                <th>Ceps - Até</th>
                                <th>Prazo - Min</th>
                                <th>Prazo - Max</th>
                                <th>Preço</th>
                                <th><i class="fa-solid fa-check"></i></th>
                                <th></th>
                            </tr>

                            @if($shippings)
                                @foreach( $shippings as $shipping )
                                    @php
                                        $active = (isset($shipping->active) && $shipping->active == "on") ? 1 : 0 ;
                                    @endphp
                                    @if( isset($shipping->name) )
                                    <tr class="zipcodes-range-item activated text-gray-600">
                                        <td><x-text-input type="text" name="name[]" placeholder="Nome" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" value="{{ $shipping->name }}" /></td>
                                        <td><x-text-input type="text" name="from[]" minlength="8" maxlength="8" placeholder="de" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" value="{{ $shipping->from }}"/></td>
                                        <td><x-text-input type="text" name="to[]" minlength="8" maxlength="8" placeholder="até" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" value="{{ $shipping->to }}"/></td>
                                        <td><x-text-input type="number" name="min_days[]" placeholder="Min" min="0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" value="{{ $shipping->min_days }}"/></td>
                                        <td><x-text-input type="number" name="max_days[]" placeholder="Max" min="0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" value="{{ $shipping->max_days }}"/></td>
                                        <td><x-text-input type="text" name="price[]" placeholder="Preço" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" value="{{ $shipping->price }}"/></td>
                                        <td width="90">
                                            <select name="active[]" value="{{ isset($shipping->active) ? $shipping->active : 'null' }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600">
                                                <option value="on" {{ isset($shipping->active) && $shipping->active == 'on' ? 'selected'  : '' }}>On</option>
                                                <option value="off" {{ isset($shipping->active) && $shipping->active == 'off' ? 'selected' : '' }}>Off</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="w-20 p-2">
                                                <a href="javascript:void(0)" class="d-inline ml-2 js-zipcode-table-remove" onclick="MVL.zipcodes.remove( this )"><i class="fa-regular fa-trash-can"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endif

                            <tr>
                                <td colspan="8" class="pt-6">
                                    <div class="zipcodes-table-new-buttons">
                                        <x-primary-link href="#" class="js-zipcode-table-new" onclick="MVL.zipcodes.addLine(this)"><i class="fa-solid fa-plus"></i> Adicionar</x-primary-link>
                                    </div>
                                </td>
                            </tr>

                            <tr class="zipcodes-range-item text-gray-600 new hidden">
                                <td><x-text-input type="text" name="name[]" placeholder="Nome" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" disabled/></td>
                                <td><x-text-input type="text" name="from[]" minlength="8" maxlength="8" placeholder="de" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" disabled/></td>
                                <td><x-text-input type="text" name="to[]"  minlength="8" maxlength="8" placeholder="até" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" disabled/></td>
                                <td><x-text-input type="number" name="min_days[]" placeholder="Min" min="0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" disabled/></td>
                                <td><x-text-input type="number" name="max_days[]" placeholder="Max" min="0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" disabled/></td>
                                <td><x-text-input type="text" name="price[]" placeholder="Preço" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600" disabled/></td>
                                <td>
                                    <select name="active[]" value="null" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-gray-600">
                                        <option value="on">On</option>
                                        <option value="off">Off</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="w-20 p-2">
                                        <a href="javascript:void(0)" class="d-inline ml-2 js-zipcode-table-remove" onclick="MVL.zipcodes.remove(this)"><i class="fa-regular fa-trash-can"></i></a>
                                    </div>
                                </td>
                            </tr>

                        </table>

                        <div class="zipcode-table-alert">
                               
                        </div>

                        <div class="mt-4 text-right">
                            <x-primary-button class="js-save"><i class="fa-regular fa-floppy-disk"></i> Salvar</x-primary-button>
                        </div>
                    </form>
                    @endif

                </div>
            </div>


            
        </div>
    </div>
</x-app-layout>