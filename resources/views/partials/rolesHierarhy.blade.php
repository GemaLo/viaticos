@php
$userRole = Auth::user()->idtype;
$rolesHierarchy = [
  'Admin_Global' => 4,
  'Admin_Sector' => 3,
  'Enlace' => 2,
  'user' => 1
];
@endphp
