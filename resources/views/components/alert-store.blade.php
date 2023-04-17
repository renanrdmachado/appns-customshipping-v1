<div class="p-4 bg-red-600 text-white rounded-lg mb-2">

    @if($slot) 
        {{ $slot }}
    @else
        <i class="fa-regular fa-circle-xmark text-red-600"></i> Você deve ativar o APP em sua loja Nuvemshop para poder seguir!<br/>
        <a href="{{ env('NS_INSTALL_URL') }}" class="btn btn-primary mt-2 d-inline-block">Ativar APP</a>
    @endif

</div>