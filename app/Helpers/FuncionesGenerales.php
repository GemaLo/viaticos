<?php

if (!function_exists('traducir_rol')) {
    function traducir_rol ($rol){
        $roles = [
            'Admin_Global' => 'Administrador Global',
            'Admin_Sector' => 'Administrador de Sector',
            'Enlace' => 'Enlace',
            'user' => 'Usuario',
        ];

        return $roles[$rol] ?? $rol;
    }
}