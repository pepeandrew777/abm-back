<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbmUsuario extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    protected $table = 'abm_usuario';
    const CREATED_AT = 'd_creacion';
    const UPDATED_AT = 'd_actualizacion';
    protected $dates = ['d_eliminacion'];
    const DELETED_AT = 'd_eliminacion';
    protected $primaryKey = 'n_id';
    protected $appends = ['fecha_recepcion'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'v_paterno',
        'v_materno',
        'v_nombres', 
        'n_ci',
        'n_item',
        'v_cargo',
        'n_interno',
        'v_telefonos',
        'n_id_solicitud',
        'v_perfil_papel_rol',
        'v_modulo_programa_carpeta',
        'v_observacion',
        'n_firma_solicitante',
        'v_nombre_gerente',
        'v_funcionalidad_permisos',
        'n_firma_gerente',
        'd_fecha_hora_recepcion',
        'n_numero_mesa_ayuda',
        'v_nombre_recibio',
        'n_firma_recibio',
        'v_observacion_recibio',
        'n_eventual',
        'd_inicio_contrato',
        'd_fin_contrato',
        'd_fecha',
        'n_id_area',
        'n_id_sucursal',
        'v_observacion_ejecucion',
        'v_otro',
        'v_departamento',
        'v_division'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'd_creacion',
        'd_actualizacion',
        'd_eliminacion',
    ];
    public function solicitud(){
        return $this->belongsTo(AbmSolicitud::class,'n_id_solicitud');
    }
    public function area(){
        return $this->belongsTo(AbmArea::class,'n_id_area');
    }
    public function sucursal(){
        return $this->belongsTo(AbmSucursal::class,'n_id_sucursal');
    }

    public function aplicacionesUsuario(){
        return $this->hasMany(AbmAplicacionUsuario::class,'n_id_usuario','n_id');
    }

    public function getFechaRecepcionAttribute(){
        return date('d/m/Y H:i:s', strtotime($this->d_fecha_hora_recepcion));
    }

    public function fechasEjecucion(){
        return $this->hasMany(AbmEjecucion::class,'n_id_usuario','n_id');
    }
}
