@formFullModal
    @slot('metodo','POST')
    @slot('action','putAluBecas')
    @slot('_csrf')
        @csrf
        {{method_field('PUT')}}
    @endslot
    @slot('titulo_full_modal')
        {{ $items->Fullname }}
    @endslot
    @slot('body_full_modal')
        @include('shared.user.__user_becas_edit')
    @endslot
@endformFullModal
