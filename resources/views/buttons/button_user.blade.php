@if (Auth::user()->idtype == 1)
<a href="{{ route('editUser', Crypt::encryptString($id_user)) }}" class="btn btn-sm btn-outline-primary">Modificar Usuario</a>

@else
<a href="{{ route('editUser', Crypt::encryptString($id_user)) }}" class="btn btn-sm btn-outline-primary">Ver Usuario</a>

@endif
